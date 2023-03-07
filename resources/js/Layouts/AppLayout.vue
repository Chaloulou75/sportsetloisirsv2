<script setup>
import MainNavigation from "@/Components/MainNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-darkblue to-midnight">
        <main-navigation />
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
        <main>
            <!-- <flash-messages /> -->
            <transition name="fade" mode="out-in" appear>
                <slot />
            </transition>
        </main>
        <!-- Page Footer -->
        <Footer />
    </div>
</template>
