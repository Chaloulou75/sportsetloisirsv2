<script setup>
import { ref, onMounted } from "vue";
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { loadStripe } from "@stripe/stripe-js";

const props = defineProps({
    errors: Object,
    user: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    reservations: Object,
    totalPrice: Number,
    clientSecret: String,
    returnUrl: String,
});

const goToCheckout = () => {
    router.post(route("create.checkout.session"));
};

const page = usePage();
const stripePublicKey = page.props.stripe.public;
const stripe = ref(null);
const elements = ref(null);
const cardElement = ref(null);
const isProcessing = ref(false);
const cardHolderName = ref(null);
const cardButton = ref(null);

const handlePayment = async () => {
    if (!stripe.value || !elements.value) {
        console.error("Stripe.js has not loaded yet.");
        return;
    }

    isProcessing.value = true;

    const { error } = await stripe.value.confirmSetup({
        elements: elements.value,
        confirmParams: {
            return_url: props.returnUrl,
            payment_method_data: {
                billing_details: { name: cardHolderName.value },
            },
        },
    });

    if (error) {
        console.error("Payment confirmation failed:", error);
        isProcessing.value = false;
        // Display error.message to the user
    } else {
        console.log("Payment confirmed successfully.");
        // Handle successful payment confirmation
    }
};

onMounted(async () => {
    try {
        const stripeInstance = await loadStripe(stripePublicKey);
        if (stripeInstance) {
            stripe.value = stripeInstance;

            const appearance = {
                /* appearance options */
            };
            const options = {
                /* other options */
            };
            elements.value = stripe.value.elements({
                clientSecret: props.clientSecret,
                appearance,
            });
            cardElement.value = elements.value.create("payment", options);
            cardElement.value.mount("#payment-element");
        }
    } catch (error) {
        console.error("Failed to load Stripe.js", error);
    }
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
                <template v-slot:title> Paiement</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container mx-auto flex flex-col gap-4 py-6">
            <div
                class="mx-auto w-full max-w-lg border border-gray-200 bg-gray-50 p-3 shadow-sm"
            >
                <!-- <form @submit.prevent="goToCheckout">
                    <button
                        type="submit"
                        class="mx-auto flex w-full max-w-full items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                    >
                        Procéder au paiement de {{ totalPrice }} €
                    </button>
                </form> -->

                <div class="w-full">
                    <InputLabel
                        for="card-holder-name"
                        value="Nom du titulaire"
                    />
                    <TextInput
                        class="w-full"
                        id="card-holder-name"
                        type="text"
                        v-model="cardHolderName"
                    />
                </div>

                <!-- Stripe Elements Placeholder -->
                <div class="my-2 py-2" id="payment-element"></div>

                <button
                    class="mx-auto flex w-full max-w-sm items-center justify-center self-end rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                    id="card-button"
                    :disabled="isProcessing"
                    @click="handlePayment"
                >
                    Procéder au paiement
                </button>
            </div>
        </div>
    </ResultLayout>
</template>
