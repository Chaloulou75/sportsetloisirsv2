<script setup>
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Object, String, Number],
        required: true,
    },
    options: Array,
    name: String,
});

const emit = defineEmits(["update:modelValue"]);

const select = ref(null);
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
        <label :for="name" class="block text-sm font-medium text-gray-700">
            {{ name }}
        </label>

        <div class="mt-1 flex rounded-md">
            <div>
                <label
                    class="inline-flex items-center"
                    v-for="(option, index) in options"
                    :key="option.id"
                >
                    <input
                        v-model="model"
                        type="radio"
                        class="form-radio"
                        :name="option.valeur"
                        :value="option.valeur"
                        checked
                    />
                    <span class="ml-2">{{ option.valeur }}</span>
                </label>
            </div>
        </div>
    </TransitionRoot>
</template>
