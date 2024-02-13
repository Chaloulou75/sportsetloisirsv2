<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, onMounted } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    name: String,
});

const isShowing = ref(true);

onMounted(() => {
    if (!model.value) {
        // const currentDate = {
        //     month: new Date().getMonth(),
        //     year: new Date().getFullYear(),
        // };
        // const endMonth = {
        //     month: currentDate.month,
        //     year: currentDate.year,
        // };
        // model.value = [currentDate, endMonth];
    }
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
