<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'role', 'otp', 'otp_expires_at', 'last_name', 'phone', 'photo', 'username', 'language', 'timezone', 'date_format', 'onboarding_step', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The account a login, login code or password reset is for. The admin panel has
     * exactly one account (config auth.admin_email), so any other admin email finds nothing.
     */
    public static function forLogin(?string $email, ?string $role): ?self
    {
        if ($role === 'admin' && ! self::isAdminEmail($email)) {
            return null;
        }

        return self::where('email', $email)->where('role', $role)->first();
    }

    public static function isAdminEmail(?string $email): bool
    {
        return strtolower(trim((string) $email)) === strtolower((string) config('auth.admin_email'));
    }

    /** Referrals this user started by sharing their code. */
    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /** This user's personal referral code, created the first time it is needed. */
    public function referralCode(): string
    {
        if (! $this->referral_code) {
            // No look-alike characters (0/O, 1/I/L), so codes are easy to read out and type.
            $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
            do {
                $code = 'BA';
                for ($i = 0; $i < 6; $i++) {
                    $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
                }
            } while (self::where('referral_code', $code)->exists());

            $this->forceFill(['referral_code' => $code])->save();
        }

        return $this->referral_code;
    }

    /** The account that owns a referral code in the given portal, if any. */
    public static function byReferralCode(?string $code, string $role): ?self
    {
        $code = strtoupper(trim((string) $code));

        return $code === '' ? null : self::where('referral_code', $code)->where('role', $role)->first();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
