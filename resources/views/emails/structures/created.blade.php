<x-mail::message>
# Création de votre structure {{ $structureName }}

Votre structure {{ $structureName }} a été créée sur Sport-et-loisirs.fr

<x-mail::button :url="$url" color="success">
{{ $structureName }}
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
