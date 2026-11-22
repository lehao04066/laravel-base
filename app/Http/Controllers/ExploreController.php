<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::with(['courts', 'pricingRules'])
            ->where('is_active', true);

        // Filter by keyword (name, address, district)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('district', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%");
            });
        }

        // Filter by district
        if ($request->filled('district') && $request->district !== 'all') {
            $query->where('district', $request->district);
        }

        $venues = $query->latest()->get();
        $districts = Venue::where('is_active', true)->distinct()->pluck('district')->filter();

        return view('customer.explore', compact('venues', 'districts'));
    }

    public function show(string $slug, Request $request)
    {
        return app(CourtBookingController::class)->showVenue($slug, $request);
    }
}
