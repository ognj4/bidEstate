<?php

namespace App\Providers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Property;
use App\Models\Review;
use App\Policies\AuctionPolicy;
use App\Policies\BidPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(Auction::class, AuctionPolicy::class);
        Gate::policy(Bid::class, BidPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
    }
}
