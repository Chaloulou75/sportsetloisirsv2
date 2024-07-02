<script setup>
import Calendar from "primevue/calendar";
import { ref, watch } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel({ monthStart: null, monthEnd: null });
const props = defineProps({
    name: String,
});

const isShowing = ref(true);
const monthStart = ref();
const monthEnd = ref();

watch(
    () => monthStart.value,
    (newVal) => {
        model.value = { monthStart: newVal, monthEnd: monthEnd.value };
    }
);

watch(
    () => monthEnd.value,
    (newVal) => {
        model.value = { monthStart: monthStart.value, monthEnd: newVal };
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
                    v-model="monthStart"
                    view="month"
                    dateFormat="mm/yy"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-start`"
                    showButtonBar
                />
            </div>

            <div class="card flex justify-start">
                <Calendar
                    v-model="monthEnd"
                    view="month"
                    dateFormat="mm/yy"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-end`"
                    showButtonBar
                />
            </div>

            <!-- <VueDatePicker
                v-model="model"
                :transitions="true"
                month-picker
                range
                locale="fr"
                cancelText="Annuler"
                selectText="Confirmer"
                placeholder="Selectionnez vos mois d'ouvertures"
            >
            </VueDatePicker> -->
        </div>
    </TransitionRoot>
</template>
