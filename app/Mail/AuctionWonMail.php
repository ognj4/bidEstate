<?php

namespace App\Mail;

use App\Models\Auction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionWonMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Auction $auction,
        public float $amount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cestitamo! Pobjedili ste aukciju — ' . $this->auction->property->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auction-won',
        );
    }
}
