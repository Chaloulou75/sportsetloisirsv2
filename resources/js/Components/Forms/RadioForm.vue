<script setup>
import RadioButton from "primevue/radiobutton";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    options: Array,
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
        <div class="flex flex-col gap-2">
            <span v-if="name" class="text-sm font-medium text-gray-700">{{
                name
            }}</span>
            <div
                v-for="option in options"
                :key="option.id"
                class="flex items-center"
            >
                <RadioButton
                    v-model="model"
                    :inputId="option.valeur"
                    :name="option.valeur"
                    :value="option"
                />
                <label
                    class="ml-2 text-sm font-medium normal-case text-gray-700"
                    :for="option.valeur"
                    >{{ option.valeur }}</label
                >
            </div>
        </div>
    </TransitionRoot>
</template>
