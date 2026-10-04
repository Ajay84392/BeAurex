<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lookups on the India Post directory (post_offices table): states, their districts, the PIN
 * codes in a district and the areas (post offices) of a PIN. The data never changes at runtime,
 * so every answer is cached.
 */
class IndiaPost
{
    private const CACHE = 'india-post:v1:';

    public static function available(): bool
    {
        return Cache::rememberForever(self::CACHE.'available', fn () => Schema::hasTable('post_offices') && DB::table('post_offices')->exists());
    }

    /** All 36 states and union territories. */
    public static function states(): array
    {
        if (! self::available()) {
            return [];
        }

        return Cache::rememberForever(self::CACHE.'states', fn () => DB::table('post_offices')->distinct()->orderBy('state')->pluck('state')->all());
    }

    public static function districts(string $state): array
    {
        return Cache::rememberForever(self::CACHE.'districts:'.md5($state), fn () => DB::table('post_offices')
            ->where('state', $state)->distinct()->orderBy('district')->pluck('district')->all());
    }

    /**
     * PIN codes of a district, each with the names of the areas it covers.
     *
     * @return array<int, array{pincode: string, areas: string}>
     */
    public static function pincodes(string $state, string $district): array
    {
        return Cache::rememberForever(self::CACHE.'pins:'.md5($state.'|'.$district), function () use ($state, $district) {
            return DB::table('post_offices')->where('state', $state)->where('district', $district)
                ->orderBy('pincode')->orderBy('area')->get(['pincode', 'area'])
                ->groupBy('pincode')
                ->map(fn ($offices, $pin) => ['pincode' => (string) $pin, 'areas' => $offices->pluck('area')->unique()->implode(', ')])
                ->values()->all();
        });
    }

    /**
     * Everything about one PIN code: its state(s), district(s) and areas, or null if unknown.
     *
     * @return array{pincode: string, state: string, district: string, areas: array<int, string>, places: array}|null
     */
    public static function lookup(string $pincode): ?array
    {
        if (! preg_match('/^[1-9]\d{5}$/', $pincode)) {
            return null;
        }

        return Cache::rememberForever(self::CACHE.'pin:'.$pincode, function () use ($pincode) {
            $offices = DB::table('post_offices')->where('pincode', $pincode)->orderBy('area')->get(['state', 'district', 'area']);
            if ($offices->isEmpty()) {
                return null;
            }
            // A PIN normally sits in one district; use the most common one as the suggestion.
            $main = $offices->groupBy(fn ($o) => $o->state.'|'.$o->district)->sortByDesc->count()->keys()->first();
            [$state, $district] = explode('|', $main);

            return [
                'pincode' => $pincode,
                'state' => $state,
                'district' => $district,
                'areas' => $offices->pluck('area')->unique()->values()->all(),
                'places' => $offices->map(fn ($o) => ['state' => $o->state, 'district' => $o->district])->unique()->values()->all(),
            ];
        });
    }

    /** Does this PIN belong to the given state and district (per India Post)? */
    public static function matches(string $pincode, string $state, string $district): bool
    {
        $info = self::lookup($pincode);

        return $info !== null && collect($info['places'])->contains(fn ($p) => $p['state'] === $state && $p['district'] === $district);
    }
}
