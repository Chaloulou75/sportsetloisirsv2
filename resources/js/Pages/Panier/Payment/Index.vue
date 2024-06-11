<script setup>
import { ref, onMounted } from "vue";
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
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
    returnUrl: String,
});

const page = usePage();
const stripePublicKey = page.props.stripe.public;
const stripe = ref(null);
const elements = ref(null);
const paymentElement = ref(null);
const isProcessing = ref(false);
// const cardHolderName = ref("");

const handlePayment = async () => {
    if (!stripe.value || !elements.value) {
        console.error("Stripe.js has not loaded yet.");
        return;
    }

    isProcessing.value = true;

    try {
        const { error, paymentIntent } = await stripe.value.confirmPayment({
            elements: elements.value,
            confirmParams: {
                return_url: route("panier.paiement.success"),
                payment_method_data: {
                    // billing_details: {
                    //     name: cardHolderName.value,
                    // },
                },
            },
        });

        if (error) {
            isProcessing.value = false;
            router.get(route("panier.paiement.cancel"));
        } else {
            console.log("Payment succeeded", paymentIntent);
        }
    } catch (err) {
        router.get(route("panier.paiement.cancel"));
    } finally {
        isProcessing.value = false;
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
                clientSecret: props.clientSecret,
                appearance,
            };
            elements.value = stripe.value.elements(options);
            paymentElement.value = elements.value.create("payment");
            paymentElement.value.mount("#payment-element");
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
                <form id="payment-form">
                    <!-- <div class="w-full">
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
                    </div> -->

                    <!-- Stripe Elements Placeholder -->
                    <div class="my-2 py-2" id="payment-element"></div>

                    <button
                        class="mx-auto flex w-full max-w-sm items-center justify-center self-end rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                        id="card-button"
                        :class="{
                            'opacity-25': isProcessing,
                        }"
                        :disabled="isProcessing"
                        @click="handlePayment"
                    >
                        <LoadingSVG v-if="isProcessing" />
                        Procéder au paiement de
                        <span class="ml-1 text-white"> {{ totalPrice }} €</span>
                    </button>
                </form>
            </div>
        </div>
    </ResultLayout>
</template>
