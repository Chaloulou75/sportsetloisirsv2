<script setup>
import { CheckCircleIcon, MailIcon } from "@heroicons/vue/24/solid";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <div
        class="bg-midnight mx-auto h-full max-w-full px-2 py-16 sm:px-3 lg:px-6"
    >
        <div
            class="mx-auto grid max-w-7xl grid-cols-1 place-items-start gap-6 border-b border-gray-700 px-2 pb-6 text-sm text-gray-700 sm:grid-cols-2 lg:grid-cols-4"
        >
            <div class="flex flex-col items-start justify-between space-y-4">
                <h4 class="font-semibold">Annoncer sur le site</h4>
                <Link :href="route('register')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Inscription annuaire (100 % gratuit)
                </Link>
                <Link href="#" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Publicité sur le site
                </Link>
                <Link href="#" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Partenariats
                </Link>
            </div>
            <div class="flex flex-col items-start justify-between space-y-4">
                <h4 class="font-semibold">Mentions Legales</h4>
                <Link :href="route('mentions.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Protection des données
                </Link>
                <Link :href="route('mentions.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Droit d'auteur
                </Link>
                <Link :href="route('mentions.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Mentions légales
                </Link>
            </div>
            <div class="flex flex-col items-start justify-between space-y-4">
                <h4 class="font-semibold">Aide</h4>
                <Link :href="route('faq.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Comment trouver un club ?
                </Link>
                <Link :href="route('faq.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Comment s'inscrire dans l'annuaire ?
                </Link>
                <Link :href="route('faq.index')" class="flex items-center">
                    <CheckCircleIcon class="mr-3 h-5 w-5 text-green-700" />
                    Comment mettre à jour ma fiche ?
                </Link>
            </div>
            <div class="flex flex-col items-start justify-between space-y-4">
                <h4 class="font-semibold">Qui Sommes Nous?</h4>
                <p>
                    Sports et loisirs propose un annuaire détaillé de clubs et
                    cours collectifs de sports et loisirs.
                </p>
                <Link href="#" class="flex items-center">
                    <MailIcon class="mr-3 h-5 w-5 text-green-700" />
                    Contactez nous
                </Link>
            </div>
        </div>
        <div
            class="mx-auto flex max-w-7xl flex-col items-start justify-between space-y-4 px-2 pt-8 text-sm text-gray-600 sm:flex-row sm:items-center sm:space-y-0 sm:px-3 lg:px-6"
        >
            <p>2006-{{ new Date().getFullYear() }} © sports-et-loisirs.fr</p>

            <div class="flex shrink-0 items-center">
                <Link :href="route('welcome')">
                    <BreezeApplicationLogo class="block h-14 w-auto" />
                </Link>
            </div>
        </div>
    </div>
</template>
