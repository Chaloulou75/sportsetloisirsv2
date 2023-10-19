<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, computed, onMounted } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Date, Object, Array, String],
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

onMounted(() => {
    const currentDate = new Date();
    const startMonth = currentDate.getMonth();
    const endMonthDate = new Date(currentDate);
    endMonthDate.setMonth(startMonth + 2);
    const endMonth = endMonthDate.getMonth();
    model.value = [startMonth, endMonth];
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
                :transitions="true"
                month-picker
                range
                locale="fr"
                cancelText="Annuler"
                selectText="Confirmer"
                placeholder="Selectionnez vos mois d'ouvertures"
            >
            </VueDatePicker>
        </div>
    </TransitionRoot>
</template>
