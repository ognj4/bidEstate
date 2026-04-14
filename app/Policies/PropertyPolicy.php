<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id
            && ! $property->hasActiveAuction();
    }

    public function view(User $user, Property $property): bool
    {
        if ($property->status === 'draft') {
            return $user->id === $property->user_id;
        }

        return true;
    }
}
