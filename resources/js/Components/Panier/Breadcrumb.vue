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
    <div class="mx-auto w-full border-y border-gray-200 px-2 py-5 md:px-8">
        <ol
            class="container mx-auto flex items-center whitespace-nowrap"
            aria-label="Breadcrumb"
        >
            <li class="inline-flex items-center">
                <Link
                    :href="route('panier.index')"
                    :class="{ 'font-bold': route().current('panier.index') }"
                    class="flex items-center text-sm text-gray-600 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
                >
                    <QueueListIcon
                        class="me-3 size-4 flex-shrink-0 text-current"
                    />
                    Récapitulatif
                </Link>
                <ChevronRightIcon
                    class="mx-2 size-4 flex-shrink-0 overflow-visible text-gray-400"
                />
            </li>
            <li
                class="inline-flex items-center truncate text-sm text-gray-800"
                aria-current="page"
            >
                <Link
                    :href="route('customers.create')"
                    :class="{
                        'font-bold': route().current('customers.create'),
                    }"
                    class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
                >
                    <CreditCardIcon class="me-3 size-4 flex-shrink-0" />
                    Coordonnées
                </Link>
                <ChevronRightIcon
                    class="mx-2 size-4 flex-shrink-0 overflow-visible text-gray-400"
                />
            </li>
            <li
                class="inline-flex items-center truncate text-sm text-gray-800"
                aria-current="page"
            >
                <Link
                    :href="route('panier.paiement.index')"
                    :class="{
                        'font-bold': route().current('panier.paiement.index'),
                    }"
                    class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:text-blue-600 focus:outline-none"
                >
                    <CreditCardIcon class="me-3 size-4 flex-shrink-0" />
                    Paiement
                </Link>
            </li>
        </ol>
    </div>
</template>
