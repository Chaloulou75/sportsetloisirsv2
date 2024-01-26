<script setup>
import ResultNavigation from "@/Components/Navigation/ResultNavigation.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref, computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    listDisciplines: Object,
    allCities: Object,
    discipline: Object,
    city: Object,
    citiesAround: Object,
    departement: Object,
    currentCategory: Object,
    categories: Object,
    familles: Object,
    isCategoriesVisible: Boolean,
    isCriteresVisible: Boolean,
});
const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    if (props.discipline && props.discipline.id) {
        const disciplineId = props.discipline.id;
        if (classMapping[disciplineId]) {
            return classMapping[disciplineId];
        } else {
            return defaultClass;
        }
    } else {
        return defaultClass;
    }
});

const isShowing = ref(true);
</script>

<template>
    <div class="relative min-h-screen bg-white">
        <main>
            <!-- Page Heading -->
            <header
                class="mx-auto h-full w-full bg-slate-100/20 bg-cover bg-center bg-no-repeat pt-16 bg-blend-soft-light shadow-lg"
                v-if="$slots.header"
                :class="headerClass"
            >
                <ResultNavigation
                    :list-disciplines="listDisciplines"
                    :all-cities="allCities"
                    :categories="categories"
                    :current-discipline="discipline"
                    :current-city="city"
                    :current-category="currentCategory"
                    :is-criteres-visible="isCriteresVisible"
                    :is-categories-visible="isCategoriesVisible"
                />
                <FamilleResultNavigation
                    :familles="familles"
                    :current-discipline="discipline"
                    :current-city="city"
                    :current-departement="departement"
                    :current-category="currentCategory"
                    :cities-around="citiesAround"
                />

                <slot name="header" />
            </header>

            <!-- Page Content -->
            <TransitionRoot
                appear
                :show="isShowing"
                enter="transition-opacity duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="transition-opacity duration-150"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <slot name="default" />
                <FlashMessages />
            </TransitionRoot>
            <!-- Page Footer -->
            <Footer />
        </main>
    </div>
</template>
