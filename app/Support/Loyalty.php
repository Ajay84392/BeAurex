<?php

namespace App\Support;

use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\Offer;
use App\Models\RewardRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * The coin economy in one place:
 *  - each QR scan at a business earns 1 coin there (customer_visits.stamp_awarded),
 *  - claiming one of that business's offers spends its coin cost (reward_requests.coins_spent),
 *  - a declined claim gives the coins back.
 * Coins are per business: coins earned at one shop can only be spent on that shop's offers.
 */
class Loyalty
{
    /** Claim statuses that keep the coins spent (a declined claim refunds them). */
    public const SPENDING_STATUSES = ['pending', 'approved'];

    private static ?bool $tracksSpending = null;

    /**
     * Whether reward_requests has the customer/offer/coin columns (migration 2026_10_01_120000).
     * Until that migration has run on a server, scanning keeps working and claiming is paused,
     * instead of every scan failing with "Unknown column coins_spent".
     */
    public static function tracksSpending(): bool
    {
        return self::$tracksSpending ??= Schema::hasColumns('reward_requests', ['customer_id', 'offer_id', 'coins_spent']);
    }

    /** This customer's claims (empty until the claim columns exist). */
    public static function requestsFor(Customer $customer)
    {
        return self::tracksSpending()
            ? RewardRequest::where('customer_id', $customer->id)
            : RewardRequest::whereRaw('1 = 0');
    }

    /**
     * Coins earned, spent and available at every business this customer has visited,
     * with that business's offers. Most recently visited business first.
     *
     * @return Collection<int, array{business: Business, earned: int, spent: int, balance: int, offers: Collection, nextOffer: ?Offer, readyOffers: Collection, lastVisit: ?string}>
     */
    public static function cards(Customer $customer): Collection
    {
        $earned = CustomerVisit::where('customer_id', $customer->id)->where('stamp_awarded', true)
            ->selectRaw('business_id, COUNT(*) as coins, MAX(scanned_at) as last_visit')
            ->groupBy('business_id')->get()->keyBy('business_id');

        if ($earned->isEmpty()) {
            return collect();
        }

        $spent = self::tracksSpending()
            ? RewardRequest::where('customer_id', $customer->id)->whereIn('status', self::SPENDING_STATUSES)
                ->selectRaw('business_id, SUM(coins_spent) as coins')->groupBy('business_id')->pluck('coins', 'business_id')
            : collect();

        $businesses = Business::whereIn('id', $earned->keys())->get()->keyBy('id');
        $offers = Offer::whereIn('business_id', $earned->keys())->orderBy('orex_coins')->get()->groupBy('business_id');

        return $earned->map(function ($row, $businessId) use ($spent, $businesses, $offers) {
            $business = $businesses->get($businessId);
            if (! $business) {
                return null;
            }
            $balance = max(0, (int) $row->coins - (int) ($spent[$businessId] ?? 0));
            $businessOffers = $offers->get($businessId, collect());

            return [
                'business' => $business,
                'earned' => (int) $row->coins,
                'spent' => (int) ($spent[$businessId] ?? 0),
                'balance' => $balance,
                'offers' => $businessOffers,
                'readyOffers' => $businessOffers->filter(fn ($o) => $o->orex_coins <= $balance)->values(),
                'nextOffer' => $businessOffers->first(fn ($o) => $o->orex_coins > $balance),
                'lastVisit' => $row->last_visit,
            ];
        })->filter()->sortByDesc('lastVisit')->values();
    }

    /** Coins this customer can still spend at one business. */
    public static function balance(Customer $customer, int $businessId): int
    {
        $earned = CustomerVisit::where('customer_id', $customer->id)->where('business_id', $businessId)
            ->where('stamp_awarded', true)->count();
        $spent = self::tracksSpending()
            ? RewardRequest::where('customer_id', $customer->id)->where('business_id', $businessId)
                ->whereIn('status', self::SPENDING_STATUSES)->sum('coins_spent')
            : 0;

        return max(0, $earned - (int) $spent);
    }

    /**
     * Spend coins on an offer. Creates the reward request the merchant approves at the counter
     * (or approves it straight away when the business has auto reward approval on).
     *
     * @throws RuntimeException with a customer-facing message when the claim is not allowed
     */
    public static function claim(Customer $customer, Offer $offer): RewardRequest
    {
        if (! self::tracksSpending()) {
            throw new RuntimeException('Claiming rewards will be available shortly. Your coins are safe — please try again later.');
        }

        if ($customer->status && $customer->status !== 'Active') {
            throw new RuntimeException('Your account cannot claim rewards right now. Please contact support.');
        }

        return DB::transaction(function () use ($customer, $offer) {
            // Lock this customer's claims so two quick taps can't spend the same coins twice.
            RewardRequest::where('customer_id', $customer->id)->lockForUpdate()->get(['id']);

            $balance = self::balance($customer, $offer->business_id);
            if ($balance < $offer->orex_coins) {
                $missing = $offer->orex_coins - $balance;
                throw new RuntimeException("You need {$missing} more ".Str::plural('coin', $missing).' to claim this reward.');
            }

            $business = Business::find($offer->business_id);

            return RewardRequest::create([
                'business_id' => $offer->business_id,
                'customer_id' => $customer->id,
                'offer_id' => $offer->id,
                'coins_spent' => $offer->orex_coins,
                'customer_name' => $customer->name,
                'reward_type' => 'REWARD',
                'reward_title' => Str::limit($offer->title ?: 'Reward', 60, ''),
                'reward_description' => $offer->description,
                'code' => self::newCode(),
                'status' => $business?->auto_reward_approval ? 'approved' : 'pending',
                'expires_at' => now()->addDays(max(1, (int) ($offer->expiry ?: 30))),
            ]);
        });
    }

    private static function newCode(): string
    {
        do {
            $code = 'BX-'.strtoupper(Str::random(6));
        } while (RewardRequest::where('code', $code)->exists());

        return $code;
    }

    /**
     * The shop whose QR code a logged-out visitor just scanned (Laravel keeps the collect URL as the
     * "intended" page while they log in or sign up), or null.
     */
    public static function pendingScanBusiness(): ?Business
    {
        $path = parse_url((string) session('url.intended'), PHP_URL_PATH);

        return $path && preg_match('#^/customer/collect/(\d+)/?$#', $path, $m) ? Business::find($m[1]) : null;
    }

    /** Customer loyalty tier from all coins ever earned. */
    public static function tier(int $totalEarned): array
    {
        return match (true) {
            $totalEarned >= 50 => ['name' => 'Gold Member', 'icon' => '👑', 'next' => null],
            $totalEarned >= 20 => ['name' => 'Silver Member', 'icon' => '🥈', 'next' => ['name' => 'Gold', 'at' => 50]],
            default => ['name' => 'Bronze Member', 'icon' => '🥉', 'next' => ['name' => 'Silver', 'at' => 20]],
        };
    }
}
