<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A valid 10-digit Indian mobile number (starts with 6-9).
 * Spaces, dashes and a leading +91 / 91 / 0 are ignored.
 */
class MobileNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (self::normalize($value) === null) {
            $fail('Enter a valid 10-digit mobile number.');
        }
    }

    /**
     * Return the bare 10 digits, or null if the value isn't a valid mobile number.
     */
    public static function normalize(mixed $value): ?string
    {
        $digits = preg_replace('/[\s\-()]/', '', (string) $value);
        $digits = preg_replace('/^(\+91|91(?=\d{10}$)|0(?=\d{10}$))/', '', $digits);

        return preg_match('/^[6-9]\d{9}$/', $digits) ? $digits : null;
    }

    /**
     * Stored format used across the app: "+91 9876543210".
     */
    public static function format(mixed $value): ?string
    {
        $digits = self::normalize($value);

        return $digits ? '+91 '.$digits : null;
    }
}
