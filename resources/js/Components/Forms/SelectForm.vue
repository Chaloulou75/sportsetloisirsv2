<script setup>
import Dropdown from "primevue/dropdown";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    options: [Array, Object],
    name: String,
    classes: String,
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
        <div>
            <label
                :for="name"
                class="mb-1 block text-sm font-medium normal-case text-gray-700"
            >
                {{ name }}
            </label>
            <Dropdown
                v-model="model"
                :options="options"
                optionLabel="valeur"
                :placeholder="name"
                :name="name"
                class="w-full text-sm md:w-[14rem]"
                :ptOptions="{ mergeProps: true }"
                :pt="{ item: 'text-sm' }"
                showClear
            />
        </div>
    </TransitionRoot>
</template>
