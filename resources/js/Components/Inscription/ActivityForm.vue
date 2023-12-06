<script setup>
const props = defineProps({
    "activite.activite_name": "",
    activitesType: Object,
    niveaux: Object,
    publictypes: Object,
    errors: Object,
});
</script>
<template>
    <div>
        <div class="col-span-3 sm:col-span-2">
            <!-- Nom activité -->
            <label
                for="activite_name"
                class="block text-sm font-medium text-gray-700"
            >
                Nom de l'activité
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    ref="activiteName"
                    v-model="activite_name"
                    type="text"
                    name="activite_name"
                    id="activite_name"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
                <button
                    type="button"
                    @click="removeActivite(index)"
                    class="ml-6 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
            </div>
            <div v-if="errors.activite_name" class="mt-2 text-xs text-red-500">
                {{ errors.name }}
            </div>
        </div>
        <!-- Niveau -->
        <div class="col-span-3 sm:col-span-2">
            <label
                for="nivel_id"
                class="block text-sm font-medium text-gray-700"
            >
                Niveaux
            </label>
            <div class="mt-1">
                <select
                    name="nivel_id"
                    id="nivel_id"
                    v-model="form.nivel_id"
                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                >
                    <option
                        v-for="nivel in niveaux"
                        :key="nivel.id"
                        :value="nivel.id"
                    >
                        {{ nivel.name }}
                    </option>
                </select>
            </div>
            <div v-if="errors.nivel_id" class="mt-2 text-xs text-red-500">
                {{ errors.nivel_id }}
            </div>
        </div>
    </div>
</template>
