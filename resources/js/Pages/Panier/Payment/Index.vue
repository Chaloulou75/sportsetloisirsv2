<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, router } from "@inertiajs/vue3";
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
    router.post(route("create-checkout-session"));
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
            <div class="w-full px-2 space-y-4 md:px-0">
                <h2 class="text-lg font-semibold text-center md:text-left">
                    Remplissez vos informations:
                </h2>
            </div>
            <div
                class="w-full max-w-lg p-3 mx-auto border border-gray-200 shadow-sm bg-gray-50"
            >
                <button
                    class="flex items-center justify-center w-full max-w-full px-4 py-3 mx-auto text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                    type="submit"
                    @click.prevent="goToCheckout"
                >
                    Procéder au paiement de {{ totalPrice }} €
                </button>

                <!-- <form @submit.prevent="processPayment" class="space-y-3">
                    <div id="address-element" ref="addressElement"></div>
                    <div id="payment-element" ref="paymentElement"></div>
                    <div v-if="cardError" role="alert">{{ cardError }}</div>
                    <button
                        class="flex items-center justify-center w-full max-w-full px-4 py-3 mx-auto text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                        type="submit"
                        :class="{
                            'opacity-25': isLoading,
                        }"
                        :disabled="isLoading"
                    >
                        <LoadingSVG v-if="isLoading" />
                        Payer {{ totalPrice }} €
                    </button>
                </form> -->
            </div>
        </div>
    </ResultLayout>
</template>
