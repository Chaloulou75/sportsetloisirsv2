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
                <template v-slot:title> Paiement annulé</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container flex flex-col gap-4 py-6 mx-auto">
            <div class="w-full max-w-sm px-2 mx-auto space-y-4 md:px-0">
                <h2 class="text-lg font-semibold text-center md:text-left">
                    Paiement annulé:
                </h2>
                <Link
                    :href="route('panier.index')"
                    :only="['reservations']"
                    class="flex items-center justify-center w-full max-w-full px-4 py-3 mx-auto text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                    >Retourner au panier</Link
                >
            </div>
        </div>
    </ResultLayout>
</template>
