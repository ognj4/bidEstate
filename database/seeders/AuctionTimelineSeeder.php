<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AuctionTimelineSeeder extends Seeder
{
    private User $seller;
    private User $buyer1;
    private User $buyer2;

    private array $sourceImages = [];
    private int   $imageIndex   = 0;

    public function run(): void
    {
        // Učitaj slike iz database/seeders/images/
        $imgDir = database_path('seeders/images');
        if (is_dir($imgDir)) {
            $files = glob($imgDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            natsort($files); // sortira 1.jpg, 2.jpg ... 22.jpg ispravno
            $this->sourceImages = array_values($files);
        }

        // Briše samo oglase/aukcije — korisnici ostaju!
        Schema::disableForeignKeyConstraints();
        Notification::truncate();
        Bid::truncate();
        Auction::truncate();
        PropertyImage::truncate();
        Property::truncate();
        Schema::enableForeignKeyConstraints();

        $this->seller = User::firstOrCreate(
            ['email' => 'prodavac@test.com'],
            ['name' => 'Marko Jovanović', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'prodavac', 'email_notifications' => true]
        );

        $this->buyer1 = User::firstOrCreate(
            ['email' => 'kupac@test.com'],
            ['name' => 'Nikola Petrović', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'kupac', 'email_notifications' => true]
        );

        // Tvoj Gmail — prima pravi Resend email na odbrani!
        $this->buyer2 = User::firstOrCreate(
            ['email' => 'stepanovicognjenos@gmail.com'],
            ['name' => 'Ognjen Stepanović', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'kupac', 'email_notifications' => true]
        );

        // Admin nalog
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Administrator', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'admin', 'email_notifications' => false]
        );

        // ── JUČE (28.09) — završene aukcije, istorija ──────────────────
        $this->finishedAuction(
            title: 'Trosoban stan na Vračaru',
            city: 'Beograd', address: 'Ulica Kralja Milana 24',
            type: 'stan', area: 95, rooms: 3, floor: 4, total: 5, year: 2018,
            desc: 'Potpuno renoviran trosoban stan u srcu Vračara. Parket od hrastovine, Schüco stolarija, podno grejanje u kupatilu. Lift, video-nadzor. Uknjižen bez tereta.',
            startPrice: 195000, finalPrice: 219500, increment: 2500, buyNow: 280000,
            endedAt: now()->subDay()->setTime(20, 0),
            winner: $this->buyer1,
            loser: $this->buyer2,
        );

        $this->finishedAuction(
            title: 'Dvosoban stan na Novom Beogradu — blok 62',
            city: 'Beograd', address: 'Tošin Bunar 274',
            type: 'stan', area: 56, rooms: 2, floor: 3, total: 12, year: 1987,
            desc: 'Svetao dvosoban stan u blok 62, 3/12 sprat, lift. Centralno grejanje, daljinomer. Odlična infrastruktura — tržni centar, škola i autobus u krugu od 200m.',
            startPrice: 89000, finalPrice: 103000, increment: 1000, buyNow: 125000,
            endedAt: now()->subDay()->setTime(22, 30),
            winner: $this->buyer2,
            loser: $this->buyer1,
        );

        // ── DANAS (29.09) — završavaju se večeras, korisnik treba da doda ponude ──
        $this->activeAuction(
            title: 'Garsonjera u Zemunu — pored Gardoša',
            city: 'Beograd', address: 'Karamatina 7',
            type: 'stan', area: 28, rooms: 1, floor: 2, total: 4, year: 1975,
            desc: 'Funkcionalna garsonjera u živopisnom Zemunu, 5 minuta peške od Gardoša. Renovirana 2023: novo kupatilo, nova kuhinja, parket. Idealna za mladu osobu ili investiciju.',
            startPrice: 38000, currentPrice: 42500, increment: 500, buyNow: 52000,
            endsAt: now()->setTime(22, 0),
            bids: [
                [$this->buyer2->id, 38500, '-5 hours'],
                [$this->buyer1->id, 40000, '-3 hours'],
                [$this->buyer2->id, 42500, '-1 hour'],
            ]
        );

        $this->activeAuction(
            title: 'Jednosoban stan u Novom Sadu — Liman IV',
            city: 'Novi Sad', address: 'Bulevar cara Lazara 18',
            type: 'stan', area: 40, rooms: 1, floor: 5, total: 8, year: 1982,
            desc: 'Lep jednosoban stan u mirnom delu Limana IV. Kompletno renoviran 2021. Centralno grejanje, PVC stolarija, renovirano kupatilo. U blizini Limanskog parka.',
            startPrice: 62000, currentPrice: 69000, increment: 1000, buyNow: 82000,
            endsAt: now()->setTime(22, 30),
            bids: [
                [$this->buyer1->id, 63000, '-4 hours'],
                [$this->buyer2->id, 66000, '-2 hours'],
                [$this->buyer1->id, 69000, '-30 minutes'],
            ]
        );

        $this->activeAuction(
            title: 'Porodična kuća u Paliluli sa dvorištem',
            city: 'Beograd', address: 'Kamenička 32',
            type: 'kuca', area: 175, rooms: 4, floor: 0, total: 2, year: 1965,
            desc: 'Solidna porodična kuća na parceli od 320m² u mirnoj ulici u Paliluli. Prizemlje + sprat, velika ostava, garaža. Bašta sa voćnjakom. Potrebno renoviranje — odlična investicija.',
            startPrice: 165000, currentPrice: 178000, increment: 2000, buyNow: null,
            endsAt: now()->setTime(23, 0),
            bids: [
                [$this->buyer2->id, 167000, '-6 hours'],
                [$this->buyer1->id, 172000, '-4 hours'],
                [$this->buyer2->id, 178000, '-2 hours'],
            ]
        );

        // ── SUTRA (30.09) ──────────────────────────────────────────────
        $this->activeAuction(
            title: 'Dvosoban stan u centru Kragujevca',
            city: 'Kragujevac', address: 'Kneza Mihaila 15',
            type: 'stan', area: 64, rooms: 2, floor: 2, total: 4, year: 2005,
            desc: 'Lepo uređen dvosoban stan u centru Kragujevca. Kompletno namešteno, spreman za useljenje. Visoki plafoni, parket, PVC stolarija, renovirano kupatilo 2022.',
            startPrice: 64000, currentPrice: 69500, increment: 500, buyNow: 82000,
            endsAt: now()->addDay()->setTime(20, 0),
            bids: [
                [$this->buyer1->id, 64500, '-22 hours'],
                [$this->buyer2->id, 67000, '-18 hours'],
                [$this->buyer1->id, 69500, '-10 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Poslovni prostor na Savskom vencu',
            city: 'Beograd', address: 'Kneza Miloša 58',
            type: 'poslovni', area: 88, rooms: null, floor: 2, total: 8, year: 2008,
            desc: 'Reprezentativni poslovni prostor u poslovnoj zgradi na Savskom vencu. 3 kancelarije + sala + recepcija. Klima, video-nadzor, protivprovalna vrata, parking za 2 auta.',
            startPrice: 148000, currentPrice: 159000, increment: 2000, buyNow: 200000,
            endsAt: now()->addDay()->setTime(21, 30),
            bids: [
                [$this->buyer2->id, 150000, '-20 hours'],
                [$this->buyer1->id, 154000, '-14 hours'],
                [$this->buyer2->id, 159000, '-8 hours'],
            ]
        );

        // ── 01.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Jednosoban stan u centru Niša',
            city: 'Niš', address: 'Obrenovićeva 48',
            type: 'stan', area: 42, rooms: 1, floor: 4, total: 8, year: 1990,
            desc: 'Ugodan jednosoban stan u centru Niša, peške do Tvrđave i Ćele-kule. Renoviran, centralno grejanje. Odlična lokacija za iznajmljivanje — prinos ~280€/mesec.',
            startPrice: 48000, currentPrice: 54000, increment: 1000, buyNow: 68000,
            endsAt: now()->addDays(2)->setTime(19, 0),
            bids: [
                [$this->buyer1->id, 49000, '-30 hours'],
                [$this->buyer2->id, 52000, '-26 hours'],
                [$this->buyer1->id, 54000, '-20 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Drvena vikendica na Zlatiboru — Mačkat',
            city: 'Zlatibor', address: 'Mačkat bb',
            type: 'kuca', area: 75, rooms: 3, floor: 0, total: 2, year: 2016,
            desc: 'Autentična drvena vikendica na Zlatiboru, 500m od ski-staze Tornik. Kamin, terasa, šumski pogled. Prinos od kratkoročnog iznajmljivanja ~1.800€/mesec u sezoni. Sopstveni bunar.',
            startPrice: 75000, currentPrice: 83500, increment: 1000, buyNow: 108000,
            endsAt: now()->addDays(2)->setTime(21, 0),
            bids: [
                [$this->buyer2->id, 76000, '-35 hours'],
                [$this->buyer1->id, 79000, '-28 hours'],
                [$this->buyer2->id, 83500, '-22 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Trosoban stan u Subotici — centar',
            city: 'Subotica', address: 'Trg slobode 5',
            type: 'stan', area: 82, rooms: 3, floor: 1, total: 3, year: 1920,
            desc: 'Prostran trosoban stan u secesijskoj zgradi u centru Subotice, tačno na Trgu slobode. Visoki plafoni 3.8m, originalni parket, dvokrilni prozori. Idealan za renoviranje i preprodaju.',
            startPrice: 72000, currentPrice: 78000, increment: 1000, buyNow: null,
            endsAt: now()->addDays(2)->setTime(22, 0),
            bids: [
                [$this->buyer1->id, 73000, '-40 hours'],
                [$this->buyer2->id, 76000, '-32 hours'],
                [$this->buyer1->id, 78000, '-24 hours'],
            ]
        );

        // ── 02.10 (dan odbrane) — aktivne tokom prezentacije ───────────
        $this->activeAuction(
            title: 'Trosoban stan na Zvezdari sa garažom',
            city: 'Beograd', address: 'Vojvode Dobrnjca 12',
            type: 'stan', area: 88, rooms: 3, floor: 3, total: 5, year: 2015,
            desc: 'Odličan trosoban stan na Zvezdari sa garažnim mestom u cenu. Potpuno renoviran, nova stolarija, renovirano kupatilo i kuhinja 2022. Centralno grejanje na gas, miran kvart.',
            startPrice: 145000, currentPrice: 157000, increment: 2000, buyNow: 195000,
            endsAt: now()->addDays(3)->setTime(18, 0),
            bids: [
                [$this->buyer2->id, 147000, '-44 hours'],
                [$this->buyer1->id, 151000, '-38 hours'],
                [$this->buyer2->id, 157000, '-30 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Moderna kuća sa baštom na Voždovcu',
            city: 'Beograd', address: 'Vojvode Stepanovića 8',
            type: 'kuca', area: 220, rooms: 5, floor: 0, total: 2, year: 2020,
            desc: 'Moderna porodična kuća na parceli od 520m². Garaža za 2 auta, bašta sa terasom. Solarni paneli, rekuperacija vazduha, grejanje na gas. 10 minuta peške od metroa (u izgradnji).',
            startPrice: 325000, currentPrice: 347000, increment: 5000, buyNow: 450000,
            endsAt: now()->addDays(3)->setTime(20, 0),
            bids: [
                [$this->buyer1->id, 330000, '-50 hours'],
                [$this->buyer2->id, 337000, '-42 hours'],
                [$this->buyer1->id, 342000, '-35 hours'],
                [$this->buyer2->id, 347000, '-28 hours'],
            ]
        );

        // ── 03.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Dvosoban stan u Novom Sadu — Detelinara',
            city: 'Novi Sad', address: 'Puskinova 22',
            type: 'stan', area: 54, rooms: 2, floor: 2, total: 4, year: 1978,
            desc: 'Dvosoban stan u mirnom kvartu Detelinara, blizu škole i pijace. Renoviran 2020, parket, nova stolarija. Centralno grejanje, miran suteren za bicikle.',
            startPrice: 82000, currentPrice: 89500, increment: 1000, buyNow: 108000,
            endsAt: now()->addDays(4)->setTime(19, 30),
            bids: [
                [$this->buyer2->id, 83000, '-55 hours'],
                [$this->buyer1->id, 86000, '-48 hours'],
                [$this->buyer2->id, 89500, '-40 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Porodična kuća u Nišu — Mediana',
            city: 'Niš', address: 'Bulevar Nemanjića 44',
            type: 'kuca', area: 155, rooms: 4, floor: 0, total: 2, year: 2000,
            desc: 'Solidna porodična kuća u kvartnom naselju Mediana. Prizemlje + sprat, dvorište 240m². Grejanje na gas, garaža. Škola i vrtić u krugu od 300m.',
            startPrice: 142000, currentPrice: 151000, increment: 2000, buyNow: null,
            endsAt: now()->addDays(4)->setTime(21, 0),
            bids: [
                [$this->buyer1->id, 144000, '-60 hours'],
                [$this->buyer2->id, 148000, '-52 hours'],
                [$this->buyer1->id, 151000, '-44 hours'],
            ]
        );

        // ── 04.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Četvorosoban stan na Banovom brdu',
            city: 'Beograd', address: 'Šumadijska 15',
            type: 'stan', area: 118, rooms: 4, floor: 4, total: 6, year: 2010,
            desc: 'Prostran četvorosoban stan u modernoj zgradi na Banovom brdu. Dva kupatila, ostava, podrum. Lift, video-nadzor, parking. Mirna ulica, odlični uslovi za porodicu.',
            startPrice: 198000, currentPrice: 212000, increment: 2000, buyNow: 265000,
            endsAt: now()->addDays(5)->setTime(20, 0),
            bids: [
                [$this->buyer2->id, 200000, '-65 hours'],
                [$this->buyer1->id, 206000, '-58 hours'],
                [$this->buyer2->id, 212000, '-50 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Poslovni prostor u Starom gradu',
            city: 'Beograd', address: 'Skadarlija 8',
            type: 'poslovni', area: 95, rooms: null, floor: 0, total: 3, year: 1995,
            desc: 'Atraktivni poslovni prostor u samom srcu Skadarlije — idealan za kafić, galeriju ili butik. Velika izlog-površina, visoki plafoni, podrumski magacin 25m². Posetioci zagarantovani.',
            startPrice: 185000, currentPrice: 197000, increment: 3000, buyNow: 250000,
            endsAt: now()->addDays(5)->setTime(21, 30),
            bids: [
                [$this->buyer1->id, 188000, '-70 hours'],
                [$this->buyer2->id, 194000, '-62 hours'],
                [$this->buyer1->id, 197000, '-55 hours'],
            ]
        );

        // ── 05.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Jednosoban stan na Vračaru — idealan za iznajmljivanje',
            city: 'Beograd', address: 'Svetosavska 31',
            type: 'stan', area: 40, rooms: 1, floor: 3, total: 5, year: 2005,
            desc: 'Prelepo uređen jednosoban stan na Vračaru u novijoj zgradi sa liftom. Kompletno namešteno, klima, internet. Trenutno iznajmljen po 550€/mesec — prinos odmah od dana kupovine.',
            startPrice: 92000, currentPrice: 103500, increment: 1500, buyNow: 130000,
            endsAt: now()->addDays(6)->setTime(18, 30),
            bids: [
                [$this->buyer2->id, 93500, '-75 hours'],
                [$this->buyer1->id, 97000, '-68 hours'],
                [$this->buyer2->id, 103500, '-60 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Kuća sa bazenom u Zrenjaninu',
            city: 'Zrenjanin', address: 'Kralja Aleksandra 22',
            type: 'kuca', area: 175, rooms: 5, floor: 0, total: 2, year: 2008,
            desc: 'Luksuzna porodična kuća sa unutrašnjim bazenom na parceli od 650m². Otvoreni plan prizemlja, 3 spavaće sobe, 2 kupaonice. Sauna, teretana, garaža za 3 auta. Alarm, klima.',
            startPrice: 195000, currentPrice: 208000, increment: 3000, buyNow: 280000,
            endsAt: now()->addDays(6)->setTime(20, 0),
            bids: [
                [$this->buyer1->id, 198000, '-80 hours'],
                [$this->buyer2->id, 202000, '-72 hours'],
                [$this->buyer1->id, 208000, '-64 hours'],
            ]
        );

        // ── 06.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Građevinsko zemljište na Zlatiboru — 620m²',
            city: 'Zlatibor', address: 'Partizanske vode bb',
            type: 'zemljiste', area: 620, rooms: null, floor: null, total: null, year: null,
            desc: 'Atraktivna parcela od 620m² na Zlatiboru u zoni vikendica, 300m od skijalice Tornik. Uređen pristupni put, struja i voda na parceli. Dozvola za izgradnju vikendice P+1 može se pribaviti.',
            startPrice: 42000, currentPrice: 48500, increment: 500, buyNow: 65000,
            endsAt: now()->addDays(7)->setTime(19, 0),
            bids: [
                [$this->buyer2->id, 42500, '-85 hours'],
                [$this->buyer1->id, 45000, '-78 hours'],
                [$this->buyer2->id, 48500, '-70 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Dvosoban stan u Čačku — nova zgrada',
            city: 'Čačak', address: 'Bulevar Oslobođenja 12',
            type: 'stan', area: 60, rooms: 2, floor: 3, total: 6, year: 2022,
            desc: 'Nov dvosoban stan u novoizgrađenoj zgradi u centru Čačka. Nikad niko nije stanovao. Terasa 8m², podno grejanje, klima. Parking mesto u podzemnoj garaži uključeno u cenu.',
            startPrice: 82000, currentPrice: 88000, increment: 1000, buyNow: 105000,
            endsAt: now()->addDays(7)->setTime(20, 30),
            bids: [
                [$this->buyer1->id, 83000, '-90 hours'],
                [$this->buyer2->id, 86000, '-82 hours'],
                [$this->buyer1->id, 88000, '-74 hours'],
            ]
        );

        // ── 07.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Trosoban stan na Voždovcu — renoviran',
            city: 'Beograd', address: 'Vojvode Dragomira 45',
            type: 'stan', area: 84, rooms: 3, floor: 5, total: 7, year: 2001,
            desc: 'Kompletno renoviran trosoban stan na Voždovcu, 5/7 sprat. Nova stolarija, nova kupaonica, nova kuhinja 2023. Pogled na park, miran kvart. Blizu centra i autoputa E75.',
            startPrice: 138000, currentPrice: 148000, increment: 2000, buyNow: 185000,
            endsAt: now()->addDays(8)->setTime(19, 30),
            bids: [
                [$this->buyer2->id, 140000, '-95 hours'],
                [$this->buyer1->id, 144000, '-88 hours'],
                [$this->buyer2->id, 148000, '-80 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Moderna kuća u Novom Sadu — Telep',
            city: 'Novi Sad', address: 'Vojvođanska 33',
            type: 'kuca', area: 195, rooms: 4, floor: 0, total: 2, year: 2019,
            desc: 'Moderna kuća u popularnom kvartu Telep. Otvoreni plan prizemlja, 3 spavaće sobe na spratu, 2 kupatila. Terasa 30m², garaža, lepa bašta. Rekuperacija vazduha, podno grejanje.',
            startPrice: 265000, currentPrice: 278000, increment: 3000, buyNow: 360000,
            endsAt: now()->addDays(8)->setTime(21, 0),
            bids: [
                [$this->buyer1->id, 268000, '-100 hours'],
                [$this->buyer2->id, 273000, '-92 hours'],
                [$this->buyer1->id, 278000, '-84 hours'],
            ]
        );

        // ── 08.10 ──────────────────────────────────────────────────────
        $this->activeAuction(
            title: 'Luksuzna kuća na Dedinju',
            city: 'Beograd', address: 'Užička 18',
            type: 'kuca', area: 380, rooms: 6, floor: 0, total: 3, year: 2017,
            desc: 'Ekskluzivna reprezentativna vila na Dedinju, u najzelenijem i najmirnijim delu Beograda. Bazen, teretana, vinski podrum, sala za projekciju. Garaža za 4 auta, 24/7 obezbeđenje, pametna kuća. Parcela 850m².',
            startPrice: 1200000, currentPrice: 1265000, increment: 15000, buyNow: 1650000,
            endsAt: now()->addDays(9)->setTime(20, 0),
            bids: [
                [$this->buyer2->id, 1215000, '-105 hours'],
                [$this->buyer1->id, 1235000, '-98 hours'],
                [$this->buyer2->id, 1250000, '-90 hours'],
                [$this->buyer1->id, 1265000, '-82 hours'],
            ]
        );

        $this->activeAuction(
            title: 'Penthouse u centru Beograda',
            city: 'Beograd', address: 'Terazije 5',
            type: 'stan', area: 135, rooms: 4, floor: 9, total: 9, year: 2023,
            desc: 'Jedinstven penthouse na zadnjem spratu luksuzne novogradnje na Terazijama. Terasa 60m² sa panoramskim pogledom na ceo Beograd. Wellness zona (sauna, džakuzi), pametna kuća KNX, parking za 2 auta.',
            startPrice: 620000, currentPrice: 652000, increment: 8000, buyNow: 850000,
            endsAt: now()->addDays(9)->setTime(22, 0),
            bids: [
                [$this->buyer1->id, 628000, '-110 hours'],
                [$this->buyer2->id, 636000, '-102 hours'],
                [$this->buyer1->id, 644000, '-95 hours'],
                [$this->buyer2->id, 652000, '-88 hours'],
            ]
        );

        $this->command->newLine();
        $this->command->info('✅ Vremenski raspored aukcija uspešno kreiran!');
        $this->command->newLine();
        $this->command->table(
            ['Period', 'Aukcija', 'Status'],
            [
                ['28.09 (juče)',  'Trosoban stan na Vračaru',                   'Završena — pobedio kupac@'],
                ['28.09 (juče)',  'Dvosoban stan Novi Beograd blok 62',         'Završena — pobedio kupac2@'],
                ['29.09 (danas)', 'Garsonjera u Zemunu',                         'Ističe u 22:00 ⚠️'],
                ['29.09 (danas)', 'Jednosoban stan Novi Sad — Liman IV',         'Ističe u 22:30 ⚠️'],
                ['29.09 (danas)', 'Porodična kuća u Paliluli',                   'Ističe u 23:00 ⚠️'],
                ['30.09 (sutra)', 'Dvosoban stan Kragujevac',                    'Ističe 20:00'],
                ['30.09 (sutra)', 'Poslovni prostor Savski venac',               'Ističe 21:30'],
                ['01.10',         'Jednosoban stan Niš',                         'Aktivan'],
                ['01.10',         'Vikendica Zlatibor',                          'Aktivan'],
                ['01.10',         'Trosoban stan Subotica',                      'Aktivan'],
                ['02.10',         'Trosoban stan Zvezdara',                      'Aktivan tokom odbrane'],
                ['02.10',         'Kuća sa baštom Voždovac',                    'Aktivan tokom odbrane'],
                ['03–08.10',      '12 aukcija (razne nekretnine)',               'Aktivne'],
            ]
        );
        $this->command->newLine();
        $this->command->warn('📸 Dodaj slike kroz UI: prodavac@test.com → Dashboard → za svaki oglas klikni Izmeni');
        $this->command->warn('💸 Licitiraj sa kupac@ i kupac2@ na aukcijama koje ističu uskoro');
        $this->command->warn('⏰ Zatvori istekle aukcije: php artisan tinker → (new \App\Jobs\CloseExpiredAuctions)->handle();');
    }

    private function activeAuction(
        string $title, string $city, string $address,
        string $type, float $area, ?int $rooms, ?int $floor, ?int $total, ?int $year,
        string $desc,
        float $startPrice, float $currentPrice, float $increment, ?float $buyNow,
        \Carbon\Carbon $endsAt,
        array $bids
    ): void {
        $property = Property::create([
            'user_id'      => $this->seller->id,
            'title'        => $title,
            'description'  => $desc,
            'type'         => $type,
            'area_m2'      => $area,
            'rooms'        => $rooms,
            'floor'        => $floor,
            'total_floors' => $total,
            'city'         => $city,
            'address'      => $address,
            'year_built'   => $year,
            'status'       => 'active',
        ]);

        $this->addImagesToProperty($property);

        $auction = Auction::create([
            'property_id'   => $property->id,
            'start_price'   => $startPrice,
            'current_price' => $currentPrice,
            'min_increment' => $increment,
            'buy_now_price' => $buyNow,
            'ends_at'       => $endsAt,
            'status'        => 'active',
        ]);

        foreach ($bids as [$userId, $amount, $offset]) {
            \DB::table('bids')->insert([
                'auction_id' => $auction->id,
                'user_id'    => $userId,
                'amount'     => $amount,
                'created_at' => now()->modify($offset)->toDateTimeString(),
            ]);
        }
    }

    private function finishedAuction(
        string $title, string $city, string $address,
        string $type, float $area, ?int $rooms, ?int $floor, ?int $total, ?int $year,
        string $desc,
        float $startPrice, float $finalPrice, float $increment, ?float $buyNow,
        \Carbon\Carbon $endedAt,
        User $winner, User $loser
    ): void {
        $property = Property::create([
            'user_id'      => $this->seller->id,
            'title'        => $title,
            'description'  => $desc,
            'type'         => $type,
            'area_m2'      => $area,
            'rooms'        => $rooms,
            'floor'        => $floor,
            'total_floors' => $total,
            'city'         => $city,
            'address'      => $address,
            'year_built'   => $year,
            'status'       => 'sold',
        ]);

        $this->addImagesToProperty($property);

        $auction = Auction::create([
            'property_id'   => $property->id,
            'start_price'   => $startPrice,
            'current_price' => $finalPrice,
            'min_increment' => $increment,
            'buy_now_price' => $buyNow,
            'ends_at'       => $endedAt,
            'status'        => 'finished',
            'winner_id'     => $winner->id,
        ]);

        // Istorija licitiranja
        $step = ($finalPrice - $startPrice) / 6;
        for ($i = 1; $i <= 6; $i++) {
            $bidder = $i % 2 === 0 ? $winner : $loser;
            \DB::table('bids')->insert([
                'auction_id' => $auction->id,
                'user_id'    => $bidder->id,
                'amount'     => round($startPrice + $step * $i, -2),
                'created_at' => $endedAt->copy()->subHours(24 - ($i * 3))->toDateTimeString(),
            ]);
        }

        Notification::create([
            'user_id' => $winner->id,
            'type'    => 'auction_won',
            'message' => 'Čestitamo! Pobedili ste aukciju za ' . $title . '.',
            'data'    => json_encode(['auction_id' => $auction->id, 'amount' => $finalPrice]),
            'read_at' => now()->subHours(2),
        ]);

        Notification::create([
            'user_id' => $loser->id,
            'type'    => 'auction_lost',
            'message' => 'Aukcija za ' . $title . ' je završena. Neko drugi je pobedio.',
            'data'    => json_encode(['auction_id' => $auction->id]),
            'read_at' => now()->subHours(2),
        ]);
    }

    // ── Kopiranje slika iz database/seeders/images/ ─────────────────────
    private function addImagesToProperty(Property $property): void
    {
        if (empty($this->sourceImages)) {
            return;
        }

        $destDir = storage_path('app/public/properties');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Uzmi sledeću sliku ciklično (1.jpg, 2.jpg ... 22.jpg, pa opet 1.jpg ...)
        $src      = $this->sourceImages[$this->imageIndex % count($this->sourceImages)];
        $this->imageIndex++;

        $filename = 'prop_' . $property->id . '_' . basename($src);
        copy($src, $destDir . '/' . $filename);

        $property->images()->create([
            'path'       => 'properties/' . $filename,
            'is_primary' => true,
            'order'      => 0,
        ]);
    }
}
