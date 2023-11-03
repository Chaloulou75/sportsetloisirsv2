<script setup>
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Object, String, Number],
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
        <div>
            <label :for="name" class="block text-sm font-medium text-gray-700">
                {{ name }}
            </label>
            <div class="mt-1 flex rounded-md">
                <select
                    ref="select"
                    :name="name"
                    :id="name"
                    v-model="model"
                    class="block w-full rounded-md border-gray-300 text-sm text-gray-800 shadow-sm"
                >
                    <option disabled value="">
                        Selectionner un
                        {{ name }}
                    </option>
                    <option
                        v-for="(option, index) in options"
                        :key="option.id"
                        :value="option"
                    >
                        {{ option.valeur }}
                    </option>
                </select>
            </div>
        </div>
    </TransitionRoot>
</template>
