<script setup>
import MainNavigation from "@/Components/MainNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const isShowing = ref(true);
</script>

<template>
    <div class="min-h-screen bg-white">
        <MainNavigation />
        <!-- Page Heading -->
        <header
            class="bg-white shadow-lg shadow-sky-600/20"
            v-if="$slots.header"
        >
            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
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
            <main>
                <FlashMessages />
                <slot />
            </main>
        </TransitionRoot>
        <!-- Page Footer -->
        <Footer />
    </div>
</template>
