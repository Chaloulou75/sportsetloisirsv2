<script setup>
import { ref } from "vue";
import { PlusIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    displayActivity: Boolean,
    displayTarif: Boolean,
    displayPlanning: Boolean,
    discipline: Object,
});

const emit = defineEmits(["openAddModal"]);

const emitEvent = (message) => {
    emit("openAddModal", message);
};

const handleClick = (type) => {
    switch (type) {
        case "activite":
            emitEvent("add-activite");
            break;
        case "planning":
            emitEvent("add-planning");
            break;
        case "tarif":
            emitEvent("add-tarif");
            break;
        default:
            break;
    }
};
</script>
<template>
    <div class="flex w-full max-w-sm items-center justify-start gap-4 px-2">
        <p class="text-lg font-medium leading-6 text-gray-800">
            Ajouter
            <span v-if="displayActivity">une activité</span>
            <span v-if="displayTarif">un tarif</span>
            <span v-if="displayPlanning">un créneau</span> à
            <span class="text-indigo-500">{{ discipline.name }}</span>
        </p>
        <button
            v-if="displayActivity"
            type="button"
            @click="handleClick('activite')"
            class="inline-flex w-auto items-center justify-between bg-green-500 px-3 py-3 text-lg text-white shadow-lg ring-2 ring-green-500 transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
        >
            <PlusIcon class="h-6 w-6" />
        </button>
        <button
            v-if="displayPlanning"
            type="button"
            @click="handleClick('planning')"
            class="inline-flex w-auto items-center justify-between bg-green-500 px-3 py-3 text-lg text-white shadow-lg ring-2 ring-green-500 transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
        >
            <PlusIcon class="h-6 w-6" />
        </button>
        <button
            v-if="displayTarif"
            type="button"
            @click="handleClick('tarif')"
            class="inline-flex w-auto items-center justify-between bg-green-500 px-3 py-3 text-lg text-white shadow-lg ring-2 ring-green-500 transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
        >
            <PlusIcon class="h-6 w-6" />
        </button>
    </div>
</template>
