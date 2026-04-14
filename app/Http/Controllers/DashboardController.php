<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Za prodavca — njegovi aktivni oglasi
        $myProperties = collect();
        $myAuctions   = collect();

        if ($user->isSeller()) {
            $myProperties = $user->properties()
                ->with(['auction', 'primaryImage'])
                ->latest()
                ->get();

            $myAuctions = $user->properties()
                ->with(['auction.bids'])
                ->whereHas('auction', fn($q) => $q->where('status', 'active'))
                ->get()
                ->pluck('auction');
        }

        // Za kupca — njegove ponude
        $myBids = $user->bids()
            ->with(['auction.property.primaryImage'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // Nepročitane notifikacije
        $notifications = $user->notifications()
            ->whereNull('read_at')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'myProperties',
            'myAuctions',
            'myBids',
            'notifications'
        ));
    }
}
