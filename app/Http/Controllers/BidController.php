<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $this->authorize('create', [Bid::class, $auction]);

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $auction->minimumNextBid(),
            ],
        ]);

        $bid = $auction->bids()->create([
            'user_id' => $request->user()->id,
            'amount'  => $validated['amount'],
        ]);

        // Ažuriraj trenutnu cijenu aukcije
        $auction->update(['current_price' => $validated['amount']]);

        return back()->with('success', 'Vaša ponuda je prihvaćena!');
    }

    public function buyNow(Request $request, Auction $auction)
    {
        $this->authorize('create', [Bid::class, $auction]);

        if (! $auction->buy_now_price) {
            return back()->with('error', 'Ova aukcija nema Buy Now opciju.');
        }

        $auction->bids()->create([
            'user_id' => $request->user()->id,
            'amount'  => $auction->buy_now_price,
        ]);

        $auction->update([
            'current_price' => $auction->buy_now_price,
            'winner_id'     => $request->user()->id,
            'status'        => 'finished',
        ]);

        $auction->property->update(['status' => 'sold']);

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Cestitamo! Uspjesno ste kupili nekretninu.');
    }
}
