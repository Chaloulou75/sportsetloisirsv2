<script setup>
import InscriptionNavigation from "@/Components/Navigation/InscriptionNavigation.vue";
import ProNavigation from "@/Components/Navigation/ProNavigation.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["eventFromChild"]);

const handleButtonEvent = (message) => {
    if (message === "Mon planning") {
        displayActivity.value = false;
        displayTarif.value = false;
        displayPlanning.value = true;
    } else if (message === "Mes tarifs") {
        displayActivity.value = false;
        displayPlanning.value = false;
        displayTarif.value = true;
    } else if (message === "Mes activites") {
        displayActivity.value = true;
        displayPlanning.value = false;
        displayTarif.value = false;
    } else if (message === "Mes adresses") {
        displayAdresses.value = true;
    }
};

const handleCloseEvent = () => {
    displayAdresses.value = false;
};

const displayAdresses = ref(true);
const isShowing = ref(true);
const displayActivity = ref(true);
const displayTarif = ref(false);
const displayPlanning = ref(false);

const props = defineProps({
    structure: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    can: Object,
});
</script>

<template>
    <div class="min-h-screen w-full bg-white">
        <div class="flex">
            <!-- Page Heading -->
            <InscriptionNavigation
                :structure="structure"
                :allReservationsCount="allReservationsCount"
                :pendingReservationsCount="pendingReservationsCount"
                :confirmedReservationsCount="confirmedReservationsCount"
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
                            v-bind="{
                                displayAdresses,
                                displayActivity,
                                displayPlanning,
                                displayTarif,
                                handleCloseEvent,
                            }"
                        />
                    </main>
                </TransitionRoot>
            </div>
        </div>
        <!-- Page Footer -->
        <Footer />
    </div>
</template>
