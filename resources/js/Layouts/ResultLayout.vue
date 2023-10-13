<script setup>
import ResultNavigation from "@/Components/Navigation/ResultNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    listDisciplines: Object,
    allCities: Object,
    discipline: Object,
    city: Object,
    isCategoriesVisible: Boolean,
    isCriteresVisible: Boolean,
    currentCategory: Object,
    categories: Object,
});
const isShowing = ref(true);
</script>

<template>
    <div class="relative min-h-screen bg-white">
        <main>
            <!-- Page Heading -->
            <header
                class="mx-auto h-full w-full pt-16 bg-blend-soft-light shadow-lg"
                v-if="$slots.header"
            >
                <ResultNavigation
                    :listDisciplines="listDisciplines"
                    :allCities="allCities"
                    :categories="categories"
                    :current-discipline="discipline"
                    :current-city="city"
                    :current-category="currentCategory"
                    :is-criteres-visible="isCriteresVisible"
                    :is-categories-visible="isCategoriesVisible"
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
