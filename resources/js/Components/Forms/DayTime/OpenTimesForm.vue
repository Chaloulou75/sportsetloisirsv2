<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Object, String],
        required: true,
    },
    name: String,
});

const emit = defineEmits(["update:modelValue"]);

const isShowing = ref(true);

const model = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
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
        <div class="z-10 w-full">
            <label :for="name" class="block text-sm font-medium text-gray-700">
                {{ name }}
            </label>
            <VueDatePicker
                v-model="model"
                time-picker
                range
                locale="fr"
                cancelText="annuler"
                selectText="confirmer"
                placeholder="Selectionnez vos horaires"
            >
            </VueDatePicker>
        </div>
    </TransitionRoot>
</template>
