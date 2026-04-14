<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $auctions = Auction::query()
            ->with(['property.primaryImage', 'property.user', 'bids'])
            ->where('status', 'active')
            ->orderBy('ends_at')
            ->paginate(12);

        return view('auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        $auction->load([
            'property.images',
            'property.user',
            'bids.user',
            'winner',
        ]);

        return view('auctions.show', compact('auction'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Auction::class);

        // Samo nekretnine koje nemaju aukciju
        $properties = $request->user()
            ->properties()
            ->where('status', 'active')
            ->whereDoesntHave('auction')
            ->get();

        return view('auctions.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Auction::class);

        $validated = $request->validate([
            'property_id'   => ['required', 'exists:properties,id'],
            'start_price'   => ['required', 'numeric', 'min:1'],
            'min_increment' => ['required', 'numeric', 'min:1'],
            'buy_now_price' => ['nullable', 'numeric', 'gt:start_price'],
            'ends_at'       => ['required', 'date', 'after:+1 hour'],
        ]);

        // Provjeri da je nekretnina vlasnikova
        $property = Property::findOrFail($validated['property_id']);
        abort_if($property->user_id !== auth()->id(), 403);

        $auction = Auction::create([
            ...$validated,
            'current_price' => $validated['start_price'],
            'status'        => 'active',
        ]);

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Aukcija je uspjesno kreirana!');
    }

    public function edit(Auction $auction)
    {
        $this->authorize('update', $auction);

        return view('auctions.edit', compact('auction'));
    }

    public function update(Request $request, Auction $auction)
    {
        $this->authorize('update', $auction);

        $validated = $request->validate([
            'min_increment' => ['required', 'numeric', 'min:1'],
            'buy_now_price' => ['nullable', 'numeric'],
            'ends_at'       => ['required', 'date', 'after:now'],
        ]);

        $auction->update($validated);

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Aukcija je azurirana!');
    }

    public function destroy(Auction $auction)
    {
        $this->authorize('cancel', $auction);

        $auction->update(['status' => 'cancelled']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Aukcija je otkazana.');
    }
}
