<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Property;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users'      => User::count(),
            'prodavci'   => User::where('role', 'prodavac')->count(),
            'kupci'      => User::where('role', 'kupac')->count(),
            'properties' => Property::count(),
            'auctions'   => Auction::count(),
            'active'     => Auction::where('status', 'active')->count(),
            'finished'   => Auction::where('status', 'finished')->count(),
        ];

        $recentUsers    = User::latest()->take(5)->get();
        $recentAuctions = Auction::with('property')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAuctions'));
    }
}
