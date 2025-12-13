<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display active and upcoming offers
     */
    public function index()
    {
        // Get active offers
        $activeOffers = Offer::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_percentage', 'desc')
            ->get();

        // Get upcoming offers
        $upcomingOffers = Offer::where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('offers', compact('activeOffers', 'upcomingOffers'));
    }
}
