<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    errors: Object,
    name: String,
    description: String,
});

const emit = defineEmits(["update:name", "update:description"]);

const name = ref(props.name);
const description = ref(props.description);

watch(name, (value) => {
    emit("update:name", value);
});

watch(description, (value) => {
    emit("update:description", value);
});
</script>
<template>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <!-- name -->
        <div class="col-span-1 md:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700">
                Nom de la discipline
                <span class="text-xs italic"></span>
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    v-model="name"
                    type="text"
                    name="name"
                    id="name"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.name" class="mt-2 text-xs text-red-500">
                {{ errors.name }}
            </div>
        </div>

        <!-- description -->
        <div class="col-span-1 md:col-span-3">
            <label
                for="description"
                class="block text-sm font-medium text-gray-700"
            >
                Description de la discipline
                <span class="text-xs italic"></span>
            </label>
            <div class="mt-1 flex rounded-md">
                <textarea
                    v-model="description"
                    id="description"
                    class="mt-2 w-full rounded-lg border-gray-200 align-top shadow-sm sm:text-sm"
                    rows="4"
                    placeholder=""
                ></textarea>
            </div>
            <div v-if="errors.description" class="mt-2 text-xs text-red-500">
                {{ errors.description }}
            </div>
        </div>
    </div>
</template>
