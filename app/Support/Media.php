<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Uploaded images (profile photos, business logos, offer images, brand files).
 *
 * New files are saved straight into public/uploads/…, which every web server can serve. The old
 * approach (storage/app/public + the public/storage symlink) does not work on the live host:
 * Hostinger answers 403 Forbidden for anything behind that symlink, so new logos and photos
 * were saved but never shown. Files uploaded the old way are still served through the
 * /media/{path} route, so nothing that already exists breaks.
 *
 * Stored values:  "uploads/merchant_logos/x.png"  (new)
 *                 "merchant_logos/x.png" or "/storage/profiles/x.png"  (old, served via /media)
 *                 "https://…"  (external image, used as is)
 */
class Media
{
    /** Folders that may be served from the old storage disk. */
    public const LEGACY_FOLDERS = ['merchant_logos', 'profiles', 'offers', 'settings'];

    public static function store(UploadedFile $file, string $folder): string
    {
        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'png');

        return self::put($folder, $extension, $file->getContent());
    }

    /** Save raw image bytes (e.g. a decoded base64 image). */
    public static function put(string $folder, string $extension, string $contents): string
    {
        $dir = public_path('uploads/'.$folder);
        File::ensureDirectoryExists($dir, 0755);
        $name = Str::random(32).'.'.preg_replace('/[^a-z0-9]/', '', strtolower($extension));
        File::put($dir.'/'.$name, $contents);

        return 'uploads/'.$folder.'/'.$name;
    }

    /** Public URL for a stored value, on whichever host the page is being viewed. */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'uploads/')) {
            return asset($path);
        }

        // Uploaded before public/uploads existed: lives on the storage disk.
        return url('media/'.Str::after($path, 'storage/'));
    }

    public static function delete(?string $path): void
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'uploads/')) {
            $full = public_path($path);
            if (File::exists($full) && Str::startsWith(realpath($full), realpath(public_path('uploads')))) {
                File::delete($full);
            }

            return;
        }

        $legacy = Str::after($path, 'storage/');
        if (in_array(Str::before($legacy, '/'), self::LEGACY_FOLDERS, true) && Storage::disk('public')->exists($legacy)) {
            Storage::disk('public')->delete($legacy);
        }
    }
}
