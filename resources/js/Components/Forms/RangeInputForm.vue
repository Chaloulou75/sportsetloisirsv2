<script setup>
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Object, String, Number],
    },
    name: String,
    min: [String, Number],
    max: [String, Number],
    metric: String,
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
        <div>
            <label :for="name" class="block text-sm font-medium text-gray-700">
                {{ name }}
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    :id="name"
                    v-model="model"
                    type="range"
                    :min="min"
                    :max="max"
                />
            </div>
            <span class="text-sm font-semibold text-gray-700"
                >{{ model }} {{ metric }}</span
            >
        </div>
    </TransitionRoot>
</template>
