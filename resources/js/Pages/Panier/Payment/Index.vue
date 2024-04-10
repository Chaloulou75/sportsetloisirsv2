<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
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
    clientSecret: String,
});

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);

const isLoading = ref(false);
const messages = ref([]);
const cardError = ref(null);
let stripe;
let elements;

onMounted(async () => {
    stripe = await stripePromise;
    const clientSecret = props.clientSecret;
    const appearance = {
        theme: "stripe",
        labels: "floating",
    };
    const options = { mode: "billing" };
    elements = stripe.elements({ clientSecret, appearance });
    const addressElement = elements.create("address", options);
    const paymentElement = elements.create("payment", {
        defaultValues: {
            billingDetails: {
                name: props.user.name,
                email: props.user.email,
            },
        },
    });

    addressElement.mount("#address-element");
    paymentElement.mount("#payment-element");

    isLoading.value = false;
});

const processPayment = async () => {
    if (isLoading.value) {
        return;
    }

    isLoading.value = true;
    if (!stripe) {
        return;
    }

    // Trigger form validation and wallet collection
    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: route("panier.paiement.success"),
        },
    });

    if (error.type === "card_error" || error.type === "validation_error") {
        messages.value.push(error.message);
    } else {
        messages.value.push("An unexpected error occured.");
    }

    isLoading.value = false;
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
                <form @submit.prevent="processPayment" class="space-y-3">
                    <div id="address-element" ref="addressElement"></div>
                    <div id="payment-element" ref="paymentElement"></div>
                    <div v-if="cardError" role="alert">{{ cardError }}</div>
                    <button
                        class="mx-auto flex w-full max-w-full items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                        type="submit"
                        :class="{
                            'opacity-25': isLoading,
                        }"
                        :disabled="isLoading"
                    >
                        <LoadingSVG v-if="isLoading" />
                        Payer {{ totalPrice }} €
                    </button>
                </form>
            </div>
        </div>
    </ResultLayout>
</template>
