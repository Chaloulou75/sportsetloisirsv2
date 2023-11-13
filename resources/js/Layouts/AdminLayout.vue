<script setup>
import { ref } from "vue";
import AdminSideNavigation from "@/Components/Navigation/AdminSideNavigation.vue";
import AdminNavigation from "@/Components/Navigation/AdminNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["eventFromChild"]);
const props = defineProps({});
const isShowing = ref(true);
</script>

<template>
    <div class="min-h-screen w-full bg-white">
        <main>
            <div class="flex flex-col md:flex-row">
                <!-- Page Heading -->
                <AdminSideNavigation />

                <div class="md:flex-1">
                    <AdminNavigation />

                    <header
                        class="h-auto w-full bg-gradient-to-br from-green-100 to-blue-100 shadow-sm"
                        v-if="$slots.header"
                    >
                        <div class="mx-auto max-w-full">
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
                        <slot name="default" />
                        <FlashMessages />
                    </TransitionRoot>
                </div>
            </div>
            <!-- Page Footer -->
            <Footer />
        </main>
    </div>
</template>
