<script setup>
import ResultNavigation from "@/Components/Navigation/ResultNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    listDisciplines: Object,
    allCities: Object,
});
const isShowing = ref(true);
</script>

<template>
    <div class="min-h-screen bg-white">
        <main>
            <!-- Page Heading -->
            <header
                class="relative mx-auto h-full w-full bg-slate-200/80 bg-running-paris bg-cover bg-center bg-no-repeat bg-blend-soft-light"
                v-if="$slots.header"
            >
                <ResultNavigation
                    :listDisciplines="listDisciplines"
                    :allCities="allCities"
                />

                <slot name="header" />
            </header>

            <!-- Page Content -->
            <TransitionRoot
                appear
                :show="isShowing"
                enter="transition-opacity duration-200"
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
