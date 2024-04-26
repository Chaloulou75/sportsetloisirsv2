<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";

const props = defineProps({
    errors: Object,
    user: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    reservations: Object,
    totalPrice: Number,
});

const goToCheckout = () => {
    router.post(route("create.checkout.session"));
};
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
                <template v-slot:title> Paiement</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container flex flex-col gap-4 py-6 mx-auto">
            <div
                class="w-full max-w-lg p-3 mx-auto border border-gray-200 shadow-sm bg-gray-50"
            >
                <form @submit.prevent="goToCheckout">
                    <button
                        type="submit"
                        class="flex items-center justify-center w-full max-w-full px-4 py-3 mx-auto text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                    >
                        Procéder au paiement de {{ totalPrice }} €
                    </button>
                </form>
            </div>
        </div>
    </ResultLayout>
</template>
