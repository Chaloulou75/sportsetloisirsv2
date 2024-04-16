<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    customer: Object,
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
                <h2 class="text-center text-lg font-normal md:text-left">
                    Merci
                    <span v-if="user" class="font-semibold text-indigo-500">{{
                        user.name
                    }}</span
                    >, le paiement est réussi!
                </h2>
                <p class="text-base font-normal">
                    Un email de confirmation vous a été envoyé.
                </p>
            </div>
        </div>
    </ResultLayout>
</template>
