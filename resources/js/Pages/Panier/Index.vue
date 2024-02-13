<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    reservations: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    produitsDesired: Object,
});
</script>
<template>
    <Head title="Panier" :description="'Mon panier.'" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Mon Panier</template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex text-gray-600 border border-gray-200 rounded-lg"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="w-4 h-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
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
                                    :href="route('favoris.index')"
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    Favoris
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <div class="container py-6 mx-auto">
            <h2 class="text-base">
                Votre panier avec produits, tarif lié, formulaire avant
                check-out
            </h2>
            <div
                class="text-sm"
                v-for="produit in produitsDesired"
                :key="produit.id"
            >
                Produit numero {{ produit.id }} - {{ produit.activite.titre }}
            </div>
        </div>
    </ResultLayout>
</template>
