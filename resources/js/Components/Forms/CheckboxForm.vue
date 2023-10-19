<script setup>
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    modelValue: {
        type: [Object, String, Number, Boolean],
    },
    options: Array,
    name: String,
    critere: Object,
    isCheckboxSelected: Function,
});

const emit = defineEmits(["update:modelValue", "updateSelectedCheckboxes"]);

const checkbox = ref(null);
const isShowing = ref(true);

const model = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});

const updateSelectedCheckboxes = (id, valeur, checked) => {
    emit("updateSelectedCheckboxes", id, valeur, checked);
};
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
        <div class="block">
            <span class="text-sm font-medium text-gray-700">{{ name }}</span>
            <div class="mt-2">
                <div v-for="(option, index) in options" :key="option.id">
                    <label
                        class="inline-flex items-center"
                        :for="option.valeur"
                    >
                        <input
                            :checked="
                                isCheckboxSelected(critere.id, option.valeur)
                            "
                            @change="
                                updateSelectedCheckboxes(
                                    critere.id,
                                    option.valeur,
                                    $event.target.checked
                                )
                            "
                            :id="option.valeur"
                            :value="option.valeur"
                            :name="option.valeur"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                        />
                        <span class="ml-2 text-sm font-medium text-gray-700">{{
                            option.valeur
                        }}</span>
                    </label>
                </div>
            </div>
        </div>
    </TransitionRoot>
</template>
