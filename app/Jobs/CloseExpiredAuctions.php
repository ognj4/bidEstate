<?php

namespace App\Jobs;

use App\Mail\AuctionLostMail;
use App\Mail\AuctionWonMail;
use App\Models\Auction;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CloseExpiredAuctions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredAuctions = Auction::query()
            ->where('status', 'active')
            ->where('ends_at', '<=', now())
            ->with(['bids.user', 'property.user'])
            ->get();

        foreach ($expiredAuctions as $auction) {
            $this->closeAuction($auction);
        }
    }

    private function closeAuction(Auction $auction): void
    {
        $highestBid = $auction->bids()->orderByDesc('amount')->first();

        if ($highestBid) {
            // Ima pobjednika
            $winner = $highestBid->user;

            $auction->update([
                'status'    => 'finished',
                'winner_id' => $winner->id,
            ]);

            $auction->property->update(['status' => 'sold']);

            // Email pobjedniku
            if ($winner->email_notifications) {
                Mail::to($winner->email)
                    ->send(new AuctionWonMail($auction, $highestBid->amount));
            }

            // Notifikacija u sistemu za pobjednika
            Notification::create([
                'user_id' => $winner->id,
                'type'    => 'auction_won',
                'message' => 'Cestitamo! Pobjedili ste aukciju za ' . $auction->property->title,
                'data'    => [
                    'auction_id' => $auction->id,
                    'amount'     => $highestBid->amount,
                ],
            ]);

            // Email svim gubitnicima
            $losers = $auction->bids()
                ->with('user')
                ->where('user_id', '!=', $winner->id)
                ->get()
                ->unique('user_id');

            foreach ($losers as $bid) {
                if ($bid->user->email_notifications) {
                    Mail::to($bid->user->email)
                        ->send(new AuctionLostMail($auction));
                }

                Notification::create([
                    'user_id' => $bid->user->id,
                    'type'    => 'auction_lost',
                    'message' => 'Aukcija za ' . $auction->property->title . ' je zavrsena. Neko drugi je pobijedio.',
                    'data'    => ['auction_id' => $auction->id],
                ]);
            }
        } else {
            // Nema ponuda — aukcija završena bez pobjednika
            $auction->update(['status' => 'finished']);
        }
    }
}
