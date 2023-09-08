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
        <main>
            <MainNavigation />
            <!-- Page Heading -->
            <header
                class="h-full w-full bg-gradient-to-br from-green-100 to-blue-100 shadow-sm"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
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
                <slot />
                <FlashMessages />
            </TransitionRoot>
            <!-- Page Footer -->
            <Footer />
        </main>
    </div>
</template>
