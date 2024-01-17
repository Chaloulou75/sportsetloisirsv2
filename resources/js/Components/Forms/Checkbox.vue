<script setup>
const checked = defineModel("checked");
const props = defineProps({
    value: {
        default: null,
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="checked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
