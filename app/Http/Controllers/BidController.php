<?php

namespace App\Http\Controllers;

use App\Mail\AuctionWonMail;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        Mail::to('bidestate1@gmail.com')
            ->send(new AuctionWonMail($auction, $auction->buy_now_price));

        Notification::create([
            'user_id' => $request->user()->id,
            'type'    => 'auction_won',
            'message' => 'Cestitamo! Kupili ste nekretninu ' . $auction->property->title . ' putem Buy Now.',
            'data'    => [
                'auction_id' => $auction->id,
                'amount'     => $auction->buy_now_price,
            ],
        ]);

        return redirect()
            ->route('auctions.show', $auction)
            ->with('success', 'Cestitamo! Uspesno ste kupili nekretninu.');
    }
}
