<script setup>
import InscriptionNavigation from "@/Components/Navigation/InscriptionNavigation.vue";
import ProNavigation from "@/Components/Navigation/ProNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["eventFromChild"]);

const handleButtonEvent = (message) => {
    if (message === "Mes adresses") {
        displayAdresses.value = true;
    }
};

const handleCloseEvent = () => {
    displayAdresses.value = false;
};

const displayAdresses = ref(false);
const isShowing = ref(true);

const props = defineProps({
    structure: Object,
    can: Object,
});
</script>

<template>
    <div class="min-h-screen w-full bg-white">
        <div class="flex">
            <!-- Page Heading -->
            <InscriptionNavigation
                :structure="structure"
                :can="can"
                @eventFromChild="handleButtonEvent"
            />

            <div class="flex-1">
                <ProNavigation :structure="structure" />
                <header
                    class="h-auto w-full bg-gradient-to-br from-green-100 to-blue-100 shadow-sm"
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
                    <main>
                        <FlashMessages />
                        <slot
                            name="default"
                            v-bind="{ displayAdresses, handleCloseEvent }"
                        />
                    </main>
                </TransitionRoot>
            </div>
        </div>
        <!-- Page Footer -->
        <Footer />
    </div>
</template>
