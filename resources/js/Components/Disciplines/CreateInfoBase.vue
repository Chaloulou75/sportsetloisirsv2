<script setup>
const name = defineModel("name");
const description = defineModel("description");
const theme = defineModel("theme");

const props = defineProps({
    errors: Object,
});
</script>
<template>
    <div class="grid max-w-sm grid-cols-1 gap-6 md:grid-cols-4">
        <!-- name -->
        <div class="col-span-1 md:col-span-4">
            <label
                for="name"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Nom de la discipline:
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
        <div class="col-span-1 md:col-span-4">
            <label
                for="description"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Description de la discipline:
                <span class="text-xs italic"></span>
            </label>
            <div class="mt-1 flex rounded-md">
                <textarea
                    v-model="description"
                    id="description"
                    class="w-full rounded-lg border-gray-200 align-top shadow-sm sm:text-sm"
                    rows="4"
                    placeholder=""
                ></textarea>
            </div>
            <div v-if="errors.description" class="mt-2 text-xs text-red-500">
                {{ errors.description }}
            </div>
        </div>

        <!-- thème -->
        <div class="col-span-1 md:col-span-3">
            <label
                for="theme"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Thème:
            </label>

            <div class="mt-1 flex space-x-4 rounded-md text-sm">
                <label for="light" class="inline-flex items-center">
                    <input
                        v-model="theme"
                        type="radio"
                        class="form-radio"
                        id="light"
                        name="light"
                        value="light"
                    />
                    <span class="ml-2">Light</span>
                </label>

                <label for="dark" class="inline-flex items-center">
                    <input
                        v-model="theme"
                        type="radio"
                        class="form-radio"
                        id="dark"
                        name="dark"
                        value="dark"
                    />
                    <span class="ml-2">Dark</span>
                </label>
            </div>
        </div>
    </div>
</template>
