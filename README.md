# BidEstate

**Online real-estate auction platform built with Laravel.**

BidEstate lets sellers list a property and run their own auction for it, while registered buyers compete by placing bids until the deadline. Most property sites only show a fixed price and leave negotiation to private contact. BidEstate moves the whole bidding process onto the site, where every participant sees the offers.

> Bachelor's thesis project (Academy of Applied Studies Šumadija, Kragujevac).

<!-- Add screenshots here, e.g.:
![Home page](docs/screenshots/home.png)
![Auction page](docs/screenshots/auction.png)
![Admin panel](docs/screenshots/admin.png)
-->

## Features

**Listings**
- Create, edit and delete property listings (apartment, house, land, commercial space)
- Details: title, description, area (m²), rooms, floor, total floors, city, address, year built
- Multiple photo upload with an instant preview (Alpine.js) and a primary image
- A listing with an active auction cannot be deleted

**Auctions**
- Seller sets a starting price, minimum bid increment, end date/time and an optional **Buy Now** price
- One active auction per listing at a time
- Auction page with image gallery, current price, time remaining and the full bid history

**Bidding**
- Bidding is handled by a Livewire component (`AuctionBidPanel`) with no page reloads
- Server-side validation: user must be logged in, the auction must be active, sellers cannot bid on their own listing, and each bid must be at least the current price plus the minimum increment
- Buy Now closes the auction immediately and declares the buyer the winner

**Automatic auction closing**
- A scheduled Laravel Job (`CloseExpiredAuctions`) runs every minute, so auctions close without any user being online
- It finds the highest bid, sets the winner, updates auction and property statuses, and creates notifications
- Auctions with no bids are closed without a winner and the listing stays active

**Notifications**
- In-app notifications for the winner and for the other participants
- Email notifications via [Resend](https://resend.com) (`AuctionWonMail`, `AuctionLostMail`), which each user can turn off in their profile

**Roles and security**

| Role | Can do |
| --- | --- |
| Visitor | Browse active auctions and view auction details |
| Buyer (`kupac`) | Place bids, save favorites, track own bids on the dashboard |
| Seller (`prodavac`) | Publish listings, start auctions, track bids and current price |
| Admin (`admin`) | Manage all users, listings and auctions; change roles; cancel active auctions |

- Route protection with middleware (`auth`, `EnsureUserIsAdmin`)
- Per-resource authorization with Policy classes (`PropertyPolicy`, `AuctionPolicy`, `BidPolicy`, `ReviewPolicy`)
- Hashed passwords (bcrypt) and CSRF protection on all forms

**Admin panel** (`/admin`)
- Separate dashboard with system totals
- Manage users, listings and auctions, with a guard that prevents an admin from deleting their own account

## Tech Stack

| Area | Technology |
| --- | --- |
| Backend | PHP, Laravel (MVC), Eloquent ORM |
| Database | MySQL |
| Frontend | Blade, Livewire (incl. Volt), Alpine.js, Tailwind CSS, Vite |
| Auth | Laravel Breeze |
| Background jobs | Laravel Jobs and Scheduler |
| Email | Resend (`resend/resend-laravel`) |

## Architecture

BidEstate is a monolithic Laravel application organized in layers:

- **Routes**: public, authenticated and admin groups (fixed routes are declared before parameterized ones)
- **Controllers**: `PropertyController`, `AuctionController`, `BidController`, `FavoriteController`, `NotificationController`, `DashboardController`, `ProfileController`, plus admin controllers
- **Models**: Eloquent classes with relations and business helpers such as `Auction::isActive()`, `isFinished()` and `minimumNextBid()`
- **Views**: Blade templates and reusable components (e.g. `<x-auction-card>`)
- **Livewire**: interactive parts of the UI, most importantly `AuctionBidPanel`
- **Jobs / Scheduler**: `CloseExpiredAuctions`, run every minute

## Database

Eight tables connected with foreign keys, cascade deletes and unique constraints:

`users` · `properties` · `property_images` · `auctions` · `bids` · `favorites` · `reviews` · `notifications`

```
users 1──* properties 1──* property_images
properties 1──* auctions 1──* bids *──1 users
users *──* properties            (favorites)
users 1──* reviews *──1 auctions
users 1──* notifications
```

<!-- Add the database diagram image here:
![Database schema](docs/screenshots/db-schema.png)
-->

## Getting Started

### Requirements

- PHP 8.2 or newer and [Composer](https://getcomposer.org/)
- Node.js and npm
- MySQL

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/ognj4/bidEstate.git
cd bidEstate

# 2. Install dependencies
composer install
npm install

# 3. Environment file and app key
cp .env.example .env
php artisan key:generate
```

### Configure `.env`

Set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bidestate
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

To send auction result emails, configure Resend (optional; without it, in-app notifications still work):

```env
MAIL_MAILER=resend
RESEND_API_KEY=your_resend_api_key
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
```

### Set up the database and storage

```bash
php artisan migrate
php artisan storage:link
```

### Run the application

Run these in separate terminals:

```bash
php artisan serve          # app at http://localhost:8000
npm run dev                # Vite (Tailwind / assets)
php artisan schedule:work  # runs the scheduler locally (closes expired auctions)
```

> In production the scheduler is run by the system cron instead of `schedule:work`:
> `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`

### Creating an admin

Users choose **buyer** or **seller** at registration. To create an admin, change a user's `role` column to `admin` in the database (for example with `php artisan tinker`):

```php
\App\Models\User::where('email', 'you@example.com')->update(['role' => 'admin']);
```

## Roadmap

- [ ] Search and filters (property type, city, price range, area)
- [ ] User interface for seller reviews (the database table and policy already exist)
- [ ] Deployment to a public server with cron-based scheduling
- [ ] Automated tests

## What I Learned

Livewire and the Laravel Scheduler/Jobs were new to me, so I learned them from the official documentation while building the project. Designing the database schema before writing code paid off the most, because the rest of the application fit naturally on top of it.

## Author

**Ognjen Stepanović**
[LinkedIn](https://www.linkedin.com/in/ognjen-stepanovic/) · [GitHub](https://github.com/ognj4)

## License

Built with [Laravel](https://laravel.com), which is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
