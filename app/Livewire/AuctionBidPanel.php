<?php

namespace App\Livewire;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AuctionBidPanel extends Component
{
    public Auction $auction;
    public float $bidAmount = 0;
    public string $message = '';
    public string $messageType = '';

    public function mount(Auction $auction): void
    {
        $this->auction = $auction;
        $this->bidAmount = $auction->minimumNextBid();
    }

    public function placeBid(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        if (! Gate::allows('create', [Bid::class, $this->auction])) {
            $this->message = 'Nemate pravo da licitirate na ovoj aukciji.';
            $this->messageType = 'error';
            return;
        }

        $this->validate([
            'bidAmount' => ['required', 'numeric', 'min:' . $this->auction->minimumNextBid()],
        ]);

        $this->auction->bids()->create([
            'user_id' => auth()->id(),
            'amount'  => $this->bidAmount,
        ]);

        $this->auction->update(['current_price' => $this->bidAmount]);
        $this->auction->refresh();

        $this->bidAmount = $this->auction->minimumNextBid();
        $this->message = 'Vasa ponuda je prihvacena!';
        $this->messageType = 'success';
    }

    public function render()
    {
        $this->auction->refresh();

        return view('livewire.auction-bid-panel');
    }
}
