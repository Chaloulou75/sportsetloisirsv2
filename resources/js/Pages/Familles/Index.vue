<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    disciplinesCount: Number,
    famillesCount: Number,
    structuresCount: Number,
});
</script>

<template>
    <Head title="Familles d'activités" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Familles </template>
            </ResultsHeader>
        </template>

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('familles.show', famille.slug)"
                        :active="route().current('familles.show', famille.slug)"
                        v-for="famille in familles"
                        :key="famille.id"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        {{ famille.name }}
                    </Link>
                </div>
            </div>
        </div>
    </ResultLayout>
</template>
