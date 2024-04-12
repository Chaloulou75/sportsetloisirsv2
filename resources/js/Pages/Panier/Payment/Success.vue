<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    customer: Array,
});
</script>

<template>
    <Head title="Paiement Succès" description="Succès paiement" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Paiement réussi</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container mx-auto flex flex-col gap-4 py-6">
            <div class="w-full space-y-4 px-2 md:px-0">
                <h2 class="text-center text-lg font-semibold md:text-left">
                    Merci <span v-if="customer">{{ customer.name }}</span
                    >, le paiement est réussi!
                </h2>
                <p>Un email de confirmation vous a été envoyé.</p>
            </div>
        </div>
    </ResultLayout>
</template>
