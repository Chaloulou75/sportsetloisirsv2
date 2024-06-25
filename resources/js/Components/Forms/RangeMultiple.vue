<script setup>
import Slider from "primevue/slider";
import { ref, watch } from "vue";

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
    unite: String,
});

const model = defineModel("valeur");
const value = ref([Number(props.min), Number(props.max)]);

const ensureMinMax = (values) => {
    const [min, max] = values.map(Number);
    if (min >= max) {
        return [min, min + 1];
    }
    return values;
};

watch(
    () => model.value,
    (newValue) => {
        if (newValue) {
            value.value = ensureMinMax(newValue);
        } else {
            value.value = [Number(props.min), Number(props.max)];
        }
    },
    { immediate: true }
);

// Update model when value array changes
watch(value, (newValue) => {
    model.value = ensureMinMax(newValue);
});
</script>

<template>
    <div
        class="card flex w-[14rem] flex-col items-start justify-start space-y-3"
    >
        <label
            v-if="name"
            :for="name"
            class="block text-sm font-medium normal-case text-gray-700"
            >{{ name }}</label
        >
        <Slider
            v-model="model"
            :min="min"
            :max="max"
            :name="name"
            range
            class="w-full"
        />
        <p class="w-full text-sm" v-if="model">
            De {{ model[0] }} à {{ model[1] }} {{ unite }}
        </p>
    </div>
</template>
