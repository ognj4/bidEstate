<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'winner_id',
        'start_price',
        'current_price',
        'min_increment',
        'buy_now_price',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'ends_at'       => 'datetime',
        'start_price'   => 'decimal:2',
        'current_price' => 'decimal:2',
        'min_increment' => 'decimal:2',
        'buy_now_price' => 'decimal:2',
    ];

    // Relacije
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderByDesc('amount');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Helper metode
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function minimumNextBid(): float
    {
        return $this->current_price + $this->min_increment;
    }

    public function timeRemaining(): string
    {
        return $this->ends_at->diffForHumans();
    }
}
