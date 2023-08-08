<x-mail::message>
# Demande de réservation pour *{{ $activiteName }}*

Vous avez reçu une demande de réservation concernant votre structure ***{{ $structureName }}***.

- Activité: **{{ $activiteName }}**
- Début: **{{ $planningStart }}**
- Fin: **{{ $planningEnd }}**
- Tarif: **{{ $tarifAmount }}** €.

Vous pouvez joindre **{{ $userName }}** via son email: [{{ $userEmail }}](mailto:{{ $userEmail }}).

<x-mail::button :url="$url" color="success">
{{ $activiteName }}
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
