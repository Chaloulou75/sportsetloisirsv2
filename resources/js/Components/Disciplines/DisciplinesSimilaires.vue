<script setup>
import { defineAsyncComponent } from "vue";

const props = defineProps({
    disciplinesSimilaires: Object,
});

const DisciplineSmallCard = defineAsyncComponent(() =>
    import("@/Components/Disciplines/DisciplineSmallCard.vue")
);
</script>
<template>
    <div
        class="mx-auto w-full rounded bg-gray-50 px-4 py-4 text-gray-600 shadow-lg"
    >
        <h3 class="mb-4 text-center font-semibold md:text-xl">
            Les disciplines similaires
        </h3>
        <div
            class="flex w-full flex-col flex-wrap items-stretch justify-start gap-4 md:flex-row"
        >
            <DisciplineSmallCard
                v-for="discipline in disciplinesSimilaires"
                :key="discipline.id"
                :link="route('disciplines.show', discipline.slug)"
                :discipline="discipline"
            />
        </div>
    </div>
</template>
