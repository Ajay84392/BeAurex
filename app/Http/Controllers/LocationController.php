<?php

namespace App\Http\Controllers;

use App\Support\IndiaPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * JSON for the State → District → PIN code → Area dropdowns (India Post directory).
 */
class LocationController extends Controller
{
    public function districts(Request $request): JsonResponse
    {
        $state = (string) $request->query('state');
        abort_unless(in_array($state, IndiaPost::states(), true), 404);

        return $this->json(IndiaPost::districts($state));
    }

    public function pincodes(Request $request): JsonResponse
    {
        $state = (string) $request->query('state');
        $district = (string) $request->query('district');
        abort_unless(in_array($state, IndiaPost::states(), true) && in_array($district, IndiaPost::districts($state), true), 404);

        return $this->json(IndiaPost::pincodes($state, $district));
    }

    public function pincode(string $pincode): JsonResponse
    {
        $info = IndiaPost::lookup($pincode);
        abort_if($info === null, 404, 'PIN code not found');

        return $this->json($info);
    }

    private function json($data): JsonResponse
    {
        // Public directory data that never changes: let browsers cache it for a day.
        return response()->json($data)->header('Cache-Control', 'public, max-age=86400');
    }
}
