<script setup>
import Calendar from "primevue/calendar";
import { ref, watch, onMounted } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel({ debut: null, fin: null });
const props = defineProps({
    name: String,
});
const isShowing = ref(true);
const start = ref();
const end = ref();

// watch(
//     () => model.value,
//     (newVal) => {
//         start.value = newVal.debut;
//         end.value = newVal.fin;
//     },
//     { immediate: true }
// );

watch(
    () => start.value,
    (newVal) => {
        model.value = { debut: newVal, fin: end.value };
    }
);

watch(
    () => end.value,
    (newVal) => {
        model.value = { debut: start.value, fin: newVal };
    }
);

onMounted(() => {
    if (model.value) {
        start.value = model.value.debut;
        end.value = model.value.fin;
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
        <div class="flex w-full max-w-sm flex-col items-start space-y-1">
            <label
                :for="name"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                {{ name }}
            </label>
            <div class="card flex justify-start">
                <Calendar
                    v-model="start"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-debut`"
                    timeOnly
                    showButtonBar
                />
            </div>
            <div class="card flex justify-start">
                <Calendar
                    v-model="end"
                    showIcon
                    iconDisplay="input"
                    :id="`${name}-fin`"
                    timeOnly
                    showButtonBar
                />
            </div>
        </div>
    </TransitionRoot>
</template>
