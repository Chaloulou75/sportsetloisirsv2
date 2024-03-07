<script setup>
import { Link } from "@inertiajs/vue3";
import {
    CreditCardIcon,
    QueueListIcon,
    IdentificationIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
</script>
<template>
    <ol
        class="flex items-center px-4 py-5 border-gray-200 whitespace-nowrap border-y md:px-8"
        aria-label="Breadcrumb"
    >
        <li class="inline-flex items-center">
            <Link
                :href="route('panier.index')"
                class="flex items-center text-sm font-bold text-gray-600 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
            >
                <QueueListIcon class="flex-shrink-0 text-current me-3 size-4" />
                Récapitulatif
            </Link>
            <ChevronRightIcon
                class="flex-shrink-0 mx-2 overflow-visible text-gray-400 size-4"
            />
        </li>
        <li class="inline-flex items-center">
            <Link
                :href="route('panier.index')"
                class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
            >
                <IdentificationIcon
                    class="flex-shrink-0 text-current me-3 size-4"
                />

                Coordonnées
                <ChevronRightIcon
                    class="flex-shrink-0 mx-2 overflow-visible text-gray-400 size-4"
                />
            </Link>
        </li>
        <li
            class="inline-flex items-center text-sm text-gray-800 truncate"
            aria-current="page"
        >
            <Link
                :href="route('panier.index')"
                class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
                ><CreditCardIcon class="flex-shrink-0 me-3 size-4" />
                Paiement
            </Link>
        </li>
    </ol>
</template>
