<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    /**
     * The loyalty record for a logged-in customer account (linked by email).
     */
    public static function forUser(User $user): self
    {
        return self::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name, 'phone' => self::availablePhone($user->phone), 'status' => 'Active']
        );
    }

    /**
     * A phone number that can be stored for this customer: phone is optional but unique, so an
     * empty value or one another customer already has is stored as null instead of failing.
     */
    public static function availablePhone(?string $phone, ?int $exceptId = null): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $taken = self::where('phone', $phone)->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))->exists();

        return $taken ? null : $phone;
    }

    public function visits()
    {
        return $this->hasMany(CustomerVisit::class);
    }

    public function rewardRequests()
    {
        return $this->hasMany(RewardRequest::class);
    }
}
