<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;

class AdminAuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with(['property.user', 'bids'])
            ->latest()
            ->paginate(20);

        return view('admin.auctions', compact('auctions'));
    }

    public function cancel(Auction $auction)
    {
        $auction->update(['status' => 'cancelled']);

        return back()->with('success', 'Aukcija je otkazana.');
    }
}
