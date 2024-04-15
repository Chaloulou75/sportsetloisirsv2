<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, onMounted } from "vue";
import { Head, router } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import { loadStripe } from "@stripe/stripe-js";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

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
        <div class="container mx-auto flex flex-col gap-4 py-6">
            <div class="w-full space-y-4 px-2 md:px-0">
                <h2 class="text-center text-lg font-semibold md:text-left">
                    Remplissez vos informations:
                </h2>
            </div>
            <div
                class="mx-auto w-full max-w-lg border border-gray-200 bg-gray-50 p-3 shadow-sm"
            >
                <button
                    class="mx-auto flex w-full max-w-full items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
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
