<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookingsCount = Booking::count();
        $confirmedBookingsCount = Booking::where('status', 'confirmed')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('final_amount');
        $venuesCount = Venue::count();
        $activeCourtsCount = Court::where('is_active', true)->count();

        $recentBookings = Booking::with(['user', 'venue', 'items.court'])
            ->latest()
            ->take(8)
            ->get();

        $venues = Venue::withCount('courts')->get();

        return view('admin.dashboard', compact(
            'totalBookingsCount',
            'confirmedBookingsCount',
            'totalRevenue',
            'venuesCount',
            'activeCourtsCount',
            'recentBookings',
            'venues'
        ));
    }
}
