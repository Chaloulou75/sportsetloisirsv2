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
        v-if="disciplinesSimilaires.length > 0"
        class="mx-auto w-full px-4 py-4 text-gray-600"
    >
        <h2
            class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
        >
            Les disciplines similaires
        </h2>
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
