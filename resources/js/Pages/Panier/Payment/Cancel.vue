<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import { loadStripe } from "@stripe/stripe-js";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});
</script>

<template>
    <Head title="Paiement" description="Mes Coordonnées de paiement" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Echec du paiement</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container mx-auto flex flex-col gap-4 py-6">
            <div class="mx-auto w-full max-w-sm space-y-4 px-2 md:px-0">
                <h2 class="text-center text-lg font-semibold md:text-left">
                    Echec lors de la tentative de paiement:
                </h2>
                <p class="text-center text-base font-medium md:text-left">
                    Une erreur est survenue durant le paiement, veuillez
                    réessayer en accèdant à votre panier:
                </p>
                <Link
                    :href="route('panier.index')"
                    :only="['reservations']"
                    class="mx-auto flex w-full max-w-full items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                    >Retourner au panier</Link
                >
            </div>
        </div>
    </ResultLayout>
</template>
