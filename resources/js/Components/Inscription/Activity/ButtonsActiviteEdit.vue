<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    displayActivity: Boolean,
    displayTarif: Boolean,
    displayPlanning: Boolean,
    structure: Object,
});
const emit = defineEmits(['eventFromChild']);

const emitEvent = (message) => {
    emit('eventFromChild', message);
};

</script>
<template>
    <div
        class="flex flex-col items-start justify-start space-x-0 space-y-2 px-2 md:flex-row md:space-x-4 md:space-y-0 md:px-0 my-4"
    >
        <button
            type="button"
            @click="emitEvent('Mes activites')"
            :class="{
                'bg-green-600 text-white': displayActivity,
            }"
            class="flex w-full items-center justify-between rounded-sm px-4 py-3 text-lg shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
        >
            Mes activités
        </button>
        <button
            type="button"
            @click="emitEvent('Mon planning')"
            :class="{
                'bg-green-600 text-white': displayPlanning,
            }"
            class="flex w-full items-center justify-between rounded-sm px-4 py-3 text-lg  shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
        >
            Mon planning
        </button>
        <button
            type="button"
            @click="emitEvent('Mes tarifs')"
            :class="{
                'bg-green-600 text-white': displayTarif,
            }"
            class="flex w-full items-center justify-between rounded-sm px-4 py-3 text-lg  shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
        >
            Mes tarifs
        </button>
        <!-- <Link
            :href="route('structures.plannings.index', structure)"
            class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
        >
            Mon planning
        </Link> -->
        <!-- <Link
            :href="route('structures.tarifs.index', structure)"
            class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
        >
            Mes tarifs
        </Link> -->

    </div>
</template>
