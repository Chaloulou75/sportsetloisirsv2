<x-mail::message>
# Demande de réservation pour *{{ $activiteName }}*

Votre réservation a été confirmée:

- Votre numéro de confirmation est le **{{ $reservationCode }}**
- Activité: **{{ $activiteName }}**
- Tarif: **{{ $tarifAmount }}** €.
- Quantité: **{{ $quantity }}** €.

<x-mail::button :url="$url" color="success">
{{ $activiteName }}
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
