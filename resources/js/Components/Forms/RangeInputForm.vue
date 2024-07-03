<script setup>
import { ref, computed } from "vue";
import Slider from "primevue/slider";
import { TransitionRoot } from "@headlessui/vue";

const model = defineModel();
const props = defineProps({
    name: String,
    min: {
        type: [String, Number],
        default: 0,
    },
    max: {
        type: [String, Number],
        default: 100,
    },
    unite: {
        type: String,
        default: null,
    },
});

const min = computed(() => Number(props.min));
const max = computed(() => Number(props.max));

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
        <div
            class="card flex w-[14rem] flex-col items-start justify-start space-y-2"
        >
            <label
                v-if="name"
                :for="name"
                class="mb-1 block text-sm font-medium normal-case text-gray-700"
                >{{ name }} <span v-if="unite">({{ unite }})</span></label
            >
            <div class="px-2">
                <Slider v-model="model" :min="min" :max="max" :name="name" />
            </div>

            <p class="w-full text-sm text-gray-700" v-if="model">
                {{ model }} {{ unite }}
            </p>
        </div>
    </TransitionRoot>
</template>
