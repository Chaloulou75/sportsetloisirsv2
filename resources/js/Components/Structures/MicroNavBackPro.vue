<script setup>
import { ref } from "vue";

const emit = defineEmits(["eventFromChild"]);
const activeButton = ref("Edit Structure");

const emitEvent = (message) => {
    emit("eventFromChild", message);
    activeButton.value = message;
};
const getButtonClass = (buttonName) => {
    return {
        "bg-indigo-500": activeButton.value === buttonName,
        "text-white": activeButton.value === buttonName,
        "px-4 py-2": true,
        "text-base": true,
        "font-semibold": true,
        "text-gray-500": true,
        "hover:bg-gray-100": true,
        "hover:text-gray-700": true,
    };
};
</script>
<template>
    <div class="flex w-full border border-b border-gray-200">
        <button
            type="button"
            @click="emitEvent('Edit Structure')"
            :class="getButtonClass('Edit Structure')"
        >
            Ma structure
        </button>
        <button
            type="button"
            @click="emitEvent('Mes adresses')"
            :class="getButtonClass('Mes adresses')"
        >
            Mes adresses
        </button>
        <button
            type="button"
            @click="emitEvent('Partenaires')"
            :class="getButtonClass('Partenaires')"
        >
            Partenaires / Instructeurs
        </button>
    </div>
</template>
