<script setup>
// import VueDatePicker from "@vuepic/vue-datepicker";
// import "@vuepic/vue-datepicker/dist/main.css";
import Calendar from "primevue/calendar";
import { ref, watch } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel({ debut: null, fin: null });
const props = defineProps({
    name: String,
});
const isShowing = ref(true);
const debut = ref();
const fin = ref();

watch(
    () => debut.value,
    (newVal) => {
        model.value = { debut: newVal, fin: fin.value };
    }
);

watch(
    () => fin.value,
    (newVal) => {
        model.value = { debut: debut.value, fin: newVal };
    }
);
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
        <div class="flex w-full max-w-sm flex-col items-start space-y-1">
            <label
                :for="name"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                {{ name }}
            </label>
            <div class="card flex justify-start">
                <Calendar
                    v-model="debut"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-debut`"
                    timeOnly
                    showButtonBar
                />
            </div>
            <div class="card flex justify-start">
                <Calendar
                    v-model="fin"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-fin`"
                    timeOnly
                    showButtonBar
                />
            </div>
            <!-- <VueDatePicker
                v-model="model"
                :transitions="true"
                time-picker
                range
                locale="fr"
                cancelText="annuler"
                selectText="confirmer"
                :placeholder="name"
            >
            </VueDatePicker> -->
        </div>
    </TransitionRoot>
</template>
