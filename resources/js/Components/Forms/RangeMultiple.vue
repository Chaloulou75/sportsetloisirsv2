<script setup>
import Slider from "primevue/slider";
import { ref, watch, computed } from "vue";

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

const model = defineModel();
// Ensure min and max are numbers
const min = computed(() => Number(props.min));
const max = computed(() => Number(props.max));
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
watch(
    () => value,
    (newValue) => {
        if (newValue) {
            model.value = ensureMinMax(newValue);
        }
    }
);
</script>

<template>
    <div
        class="card flex w-[14rem] max-w-sm flex-col items-start justify-start space-y-2"
    >
        <label
            v-if="name"
            :for="name"
            class="mb-1 block text-sm font-medium normal-case text-gray-700"
            >{{ name }} ({{ unite }})</label
        >
        <div class="px-2">
            <Slider
                v-model="model"
                :min="min"
                :max="max"
                :name="name"
                :id="name"
                range
            />
        </div>

        <p class="w-full text-sm text-gray-700" v-if="model">
            De {{ model[0] }} à {{ model[1] }} {{ unite }}
        </p>
    </div>
</template>
