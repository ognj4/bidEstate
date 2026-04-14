<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;

class BidPolicy
{
    public function create(User $user, Auction $auction): bool
    {
        $auction->loadMissing('property');

        if ($user->id === $auction->property->user_id) {
            return false;
        }

        if (! $auction->isActive()) {
            return false;
        }

        return true;
    }
}
