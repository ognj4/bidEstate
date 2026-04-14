<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #4f46e5;">Cestitamo na pobjedi!</h2>
    <p>Pobjedili ste aukciju za <strong>{{ $auction->property->title }}</strong>.</p>
    <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; margin: 20px 0;">
        <p style="margin: 0; font-size: 14px; color: #166534;">Vaša pobjednička ponuda</p>
        <p style="margin: 4px 0 0; font-size: 28px; font-weight: bold; color: #15803d;">€{{ number_format($amount, 0, ',', '.') }}</p>
    </div>
    <p><strong>Nekretnina:</strong> {{ $auction->property->title }}</p>
    <p><strong>Lokacija:</strong> {{ $auction->property->address }}, {{ $auction->property->city }}</p>
    <p><strong>Prodavac:</strong> {{ $auction->property->user->name }}</p>
    <p style="color: #6b7280; font-size: 14px;">Prodavac će Vas uskoro kontaktirati sa daljnjim detaljima o transakciji.</p>
    <a href="{{ route('auctions.show', $auction) }}"
       style="display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-top: 16px;">
        Pogledaj aukciju
    </a>
</body>
</html>