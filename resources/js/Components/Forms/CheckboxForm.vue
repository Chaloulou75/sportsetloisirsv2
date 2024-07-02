<script setup>
import Checkbox from "primevue/checkbox";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    options: Array,
    name: String,
    classes: String,
    critere: Object,
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
        <div class="card flex justify-start">
            <div class="flex flex-col gap-2">
                <span v-if="name" class="text-sm font-medium text-gray-700">{{
                    name
                }}</span>
                <div
                    v-for="option in options"
                    :key="option.id"
                    class="flex items-center"
                >
                    <Checkbox
                        v-model="model"
                        :inputId="option.valeur"
                        name="option"
                        :value="option"
                    />
                    <label
                        class="ml-2 text-sm font-medium text-gray-700"
                        :for="option.valeur"
                        >{{ option.valeur }}</label
                    >
                </div>
            </div>
        </div>
    </TransitionRoot>
</template>
