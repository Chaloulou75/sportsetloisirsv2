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
        <div class="container flex flex-col gap-4 py-6 mx-auto">
            <div class="w-full px-2 space-y-4 md:px-0">
                <h2 class="text-lg font-normal text-center md:text-left">
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
