<script setup>
import Checkbox from "primevue/checkbox";
const checked = defineModel("checked");
const props = defineProps({
    value: {
        default: null,
    },
    name: String,
});
</script>

<template>
    <div class="flex items-center">
        <Checkbox
            v-model="checked"
            :inputId="name"
            :name="name"
            :id="name"
            :value="value"
            :binary="true"
        />
        <label :for="name" class="ml-2 text-sm font-medium text-gray-700">
            {{ name }}
        </label>
    </div>

    <!-- <input
        type="checkbox"
        :value="value"
        v-model="checked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    /> -->
</template>
