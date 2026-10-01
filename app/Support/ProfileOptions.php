<?php

namespace App\Support;

/**
 * Choices offered on the profile pages, shared by the views and the validation rules.
 */
class ProfileOptions
{
    public const LANGUAGES = [
        'English' => 'English',
        'Hindi' => 'Hindi',
    ];

    public const TIMEZONES = [
        'Asia/Kolkata' => '(GMT+05:30) Asia/Kolkata (IST)',
        'Asia/Dubai' => '(GMT+04:00) Asia/Dubai',
        'Asia/Singapore' => '(GMT+08:00) Asia/Singapore',
        'Europe/London' => '(GMT+00:00) Europe/London',
        'America/New_York' => '(GMT-05:00) America/New_York',
        'UTC' => '(GMT+00:00) UTC',
    ];

    public const DATE_FORMATS = ['d M, Y', 'd/m/Y', 'Y-m-d', 'm/d/Y'];

    /** Older rows stored short codes ("en", "hi"); map them onto the current keys. */
    private const LEGACY_LANGUAGES = ['en' => 'English', 'hi' => 'Hindi'];

    public static function language(?string $value): string
    {
        $value = self::LEGACY_LANGUAGES[$value] ?? $value;

        return isset(self::LANGUAGES[$value]) ? $value : 'English';
    }

    public static function timezone(?string $value): string
    {
        return isset(self::TIMEZONES[$value]) ? $value : 'Asia/Kolkata';
    }

    public static function dateFormat(?string $value): string
    {
        return in_array($value, self::DATE_FORMATS, true) ? $value : 'd M, Y';
    }

    /** Date formats keyed by format, labelled with today's date as an example. */
    public static function dateFormatOptions(): array
    {
        $today = now();

        return collect(self::DATE_FORMATS)->mapWithKeys(fn ($f) => [$f => $today->format($f)])->all();
    }
}
