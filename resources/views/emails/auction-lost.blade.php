<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #374151;">Aukcija je završena</h2>
    <p>Aukcija za <strong>{{ $auction->property->title }}</strong> je završena.</p>
    <p style="color: #6b7280;">Nažalost, neko drugi je dao veću ponudu. Ne odustajte — svaki dan su dostupne nove aukcije!</p>
    <a href="{{ route('auctions.index') }}"
       style="display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-top: 16px;">
        Pogledaj nove aukcije
    </a>
</body>
</html>