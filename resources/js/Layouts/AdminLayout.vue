<script setup>
import { ref } from "vue";
import AdminSideNavigation from "@/Components/Navigation/AdminSideNavigation.vue";
import AdminNavigation from "@/Components/Navigation/AdminNavigation.vue";
import ToastMessages from "@/Components/ToastMessages.vue";
import Footer from "@/Components/Footer.vue";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["eventFromChild"]);
const props = defineProps({});
const isShowing = ref(true);
</script>

<template>
    <div class="relative w-full min-h-screen bg-white">
        <main>
            <div class="flex flex-col md:flex-row">
                <!-- Page Heading -->
                <AdminSideNavigation />

                <div class="md:flex-1">
                    <AdminNavigation />

                    <header
                        class="w-full h-auto shadow-sm bg-gradient-to-br from-green-100 to-blue-100"
                        v-if="$slots.header"
                    >
                        <div class="max-w-full mx-auto">
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
                        <ToastMessages />
                    </TransitionRoot>
                </div>
            </div>
            <!-- Page Footer -->
            <Footer />
        </main>
    </div>
</template>
