<script setup>
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    name: String,
    min: {
        type: [String, Number],
        default: 0,
    },
    max: {
        type: [String, Number],
        default: 100,
    },
    metric: {
        type: String,
        default: "Km",
    },
});

const isShowing = ref(true);
</script>

<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <label
            :for="name"
            class="block text-sm font-medium normal-case text-gray-700"
            >{{ name }}</label
        >
        <input
            type="range"
            v-model="model"
            class="[&::-webkit-slider-thumb]: [&::-webkit-slider-runnable-track]: w-full cursor-pointer appearance-none bg-transparent focus:outline-none disabled:pointer-events-none disabled:opacity-50 [&::-moz-range-thumb]:h-2.5 [&::-moz-range-thumb]:w-2.5 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-4 [&::-moz-range-thumb]:border-blue-600 [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:transition-all [&::-moz-range-thumb]:duration-150 [&::-moz-range-thumb]:ease-in-out [&::-moz-range-track]:h-2 [&::-moz-range-track]:w-full [&::-moz-range-track]:rounded-full [&::-moz-range-track]:bg-gray-100 [&::-webkit-slider-runnable-track]:h-2 [&::-webkit-slider-runnable-track]:w-full [&::-webkit-slider-runnable-track]:rounded-full [&::-webkit-slider-runnable-track]:bg-gray-100 [&::-webkit-slider-thumb]:-mt-0.5 [&::-webkit-slider-thumb]:h-2.5 [&::-webkit-slider-thumb]:w-2.5 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:shadow-[0_0_0_4px_rgba(37,99,235,1)] [&::-webkit-slider-thumb]:transition-all [&::-webkit-slider-thumb]:duration-150 [&::-webkit-slider-thumb]:ease-in-out"
            :id="name"
            :min="min"
            :max="max"
        />
        <span class="text-sm font-medium text-gray-700"
            >{{ model }} <span v-if="metric">{{ metric }}</span></span
        >
    </TransitionRoot>
</template>
