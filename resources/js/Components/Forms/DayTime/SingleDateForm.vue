<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, computed, onMounted } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    name: String,
});

const isShowing = ref(true);

onMounted(() => {
    if (!model.value) {
        // const date_debut = new Date();
        // model.value = date_debut;
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
                locale="fr"
                :transitions="true"
                :format="'dd/MM/yyyy'"
                :enableTimePicker="false"
                cancelText="annuler"
                selectText="confirmer"
                :placeholder="name"
            >
            </VueDatePicker>
        </div>
    </TransitionRoot>
</template>
