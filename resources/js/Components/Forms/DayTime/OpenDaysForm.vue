<script setup>
import Calendar from "primevue/calendar";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    name: String,
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
        <div class="w-full max-w-sm">
            <label
                :for="name"
                class="mb-1 block text-sm font-medium normal-case text-gray-700"
            >
                {{ name }}
            </label>
            <div class="card flex justify-start">
                <Calendar
                    :id="name"
                    v-model="model"
                    showIcon
                    iconDisplay="input"
                    dateFormat="dd/mm/yy"
                    showButtonBar
                    selectionMode="range"
                    :manualInput="false"
                />
            </div>
        </div>
    </TransitionRoot>
</template>
