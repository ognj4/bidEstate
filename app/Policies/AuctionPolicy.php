<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;

class AuctionPolicy
{
    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    public function update(User $user, Auction $auction): bool
    {
        return $user->id === $auction->property->user_id
            && $auction->status === 'pending';
    }

    public function cancel(User $user, Auction $auction): bool
    {
        return $user->id === $auction->property->user_id
            && in_array($auction->status, ['pending', 'active']);
    }
}
