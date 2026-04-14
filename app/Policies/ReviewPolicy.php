<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Auction $auction): bool
    {
        return $auction->winner_id === $user->id
            && $auction->isFinished()
            && ! $auction->review()->exists();
    }
}
