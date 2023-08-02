<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    MapPinIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    structure: Object,
    logoUrl: String,
    familles: Object,
    activite: Object,
});
</script>

<template>
    <Head
        :title="activite.titre + ' - ' + structure.name"
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
    />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-center text-xl font-semibold uppercase leading-tight tracking-widest text-gray-800"
                >
                    {{ activite.titre }}
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol
                        class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                    >
                        <li class="flex items-center">
                            <Link
                                preserve-scroll
                                :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
                                    Accueil
                                </span>
                            </Link>
                        </li>

                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                            >
                            </span>

                            <Link
                                preserve-scroll
                                :href="route('structures.show', structure.slug)"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ structure.name }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
        </template>

        <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
            <div
                class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 shadow-lg shadow-sky-700 md:flex-row md:space-x-6"
            >
                <div class="w-full md:w-1/3">
                    <div class="my-4 flex items-center justify-center md:mb-8">
                        <h3 class="text-base font-semibold uppercase">
                            Coordonnées de la structure
                        </h3>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <h3 class="text-base">Localisation</h3>
                        <p class="py-4 font-semibold">
                            {{ structure.adresses[0].city }},
                            {{ structure.adresses[0].zip_code }}
                        </p>
                        <LeafletMap :item="structure" />
                    </div>
                    <div class="my-4 space-y-3">
                        <p class="text-base font-semibold text-gray-700">
                            <UserIcon class="inline-block h-4 w-4" />
                            {{ structure.creator.name }}
                        </p>
                        <p
                            v-if="structure.website"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Site web:
                            <a
                                :href="structure.website"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.website }}
                            </a>
                        </p>
                        <p
                            v-if="structure.facebook"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Facebook:
                            <a
                                :href="structure.facebook"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.facebook }}
                            </a>
                        </p>
                        <p
                            v-if="structure.instagram"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Instagram:
                            <a
                                :href="structure.instagram"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.instagram }}
                            </a>
                        </p>
                        <p
                            v-if="structure.youtube"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Youtube:
                            <a
                                :href="structure.youtube"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.youtube }}
                            </a>
                        </p>

                        <p
                            v-if="structure.phone1"
                            class="text-base font-medium text-gray-700"
                        >
                            <PhoneIcon class="inline-block h-4 w-4" />
                            {{ structure.phone1 }}
                        </p>
                        <p
                            v-if="structure.email"
                            class="text-base font-medium text-gray-700"
                        >
                            <AtSymbolIcon class="inline-block h-4 w-4" />
                            {{ structure.email }}
                        </p>
                    </div>
                </div>
                <div class="w-full space-y-8 md:w-2/3 md:pr-10">
                    <div class="relative mb-4 md:mb-6">
                        <p
                            class="mb-2 text-lg font-medium uppercase tracking-wider text-gray-500"
                        >
                            {{ structure.structuretype.name }}
                        </p>
                        <div
                            class="my-4 flex items-center justify-start space-x-4"
                        >
                            <div v-if="structure.logo">
                                <img
                                    alt="img"
                                    :src="structure.logo"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                            </div>
                            <h2
                                class="inline-block text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ activite.titre }}
                            </h2>
                        </div>
                        <div>
                            <div class="w-full divide-y divide-slate-200">
                                <dt class="sr-only">Produits</dt>
                                <div
                                    class="px-2 py-3 odd:bg-white even:bg-slate-50"
                                    v-for="produit in activite.produits"
                                    :key="produit.id"
                                >
                                    <p class="text-xs">
                                        Produit n° {{ produit.id }}:
                                    </p>
                                    <div
                                        class="flex items-center py-1.5 text-xs"
                                    >
                                        <dt class="sr-only">Ville</dt>
                                        <MapPinIcon
                                            class="mr-1 h-4 w-4 text-indigo-700"
                                        />
                                        <p class="font-semibold">
                                            {{ produit.adresse.city }} ({{
                                                produit.adresse.zip_code
                                            }})
                                        </p>
                                    </div>
                                    <p
                                        class="text-sm"
                                        v-for="critere in produit.criteres"
                                        :key="critere.id"
                                    >
                                        {{ critere.critere.nom }}:
                                        <span class="font-semibold">{{
                                            critere.valeur
                                        }}</span>
                                    </p>
                                    <p class="mt-2 text-sm">Tarifs:</p>
                                    <p
                                        class="text-sm"
                                        v-for="tarif in produit.tarifs"
                                        :key="tarif.id"
                                    >
                                        <span class="font-semibold">
                                            {{ tarif.titre }}:
                                            {{ tarif.amount }} € /
                                            {{ tarif.tarif_type.type }}</span
                                        >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
