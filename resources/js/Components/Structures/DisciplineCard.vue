<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { TrashIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["openDeleteModal", "openDeleteCategorieModal"]);

const openDeleteModal = (activite) => {
    emit("openDeleteModal", activite);
};

const openDeleteCategorieModal = (categorie) => {
    emit("openDeleteCategorieModal", categorie);
};
const props = defineProps({
    activite: Object,
    structure: Object,
});
</script>

<template>
    <div
        class="block rounded-lg shadow-sm shadow-indigo-200 transition duration-300 ease-in-out hover:shadow-2xl"
    >
        <div class="relative">
            <button
                class="absolute right-2 top-2 bg-transparent"
                type="button"
                @click="emit('openDeleteModal', activite)"
            >
                <span class="sr-only">supprimer discipline</span>
                <TrashIcon
                    class="mr-1 h-6 w-6 text-red-500 hover:text-red-600"
                />
            </button>

            <!-- Image -->
            <img
                alt="Home"
                src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                class="h-56 w-full rounded-md bg-opacity-70 object-cover"
            />
        </div>

        <div class="mt-2">
            <dl class="flex flex-col">
                <Link
                    :href="
                        route('structures.activites.edit', {
                            structure: structure.slug,
                            activite: activite.id,
                        })
                    "
                    class="px-2 py-1.5 text-center text-base font-semibold uppercase tracking-wide text-gray-600 hover:text-indigo-600"
                >
                    {{ activite.disciplineName }}
                </Link>

                <div class="w-full divide-y divide-slate-200">
                    <div
                        class="flex justify-between px-2 py-3 odd:bg-white even:bg-slate-50"
                        v-for="categorie in activite.categories"
                        :key="categorie.id"
                    >
                        <Link
                            :href="
                                route('structures.activites.edit', {
                                    structure: structure.slug,
                                    activite: activite.id,
                                })
                            "
                            class="text-sm hover:text-indigo-500"
                            >{{ categorie.name }}

                            <span class="text-sm">
                                ({{ categorie.count }})</span
                            >
                        </Link>
                        <button
                            type="button"
                            @click="emit('openDeleteCategorieModal', categorie)"
                            class="self-end text-red-500 hover:text-red-600"
                        >
                            <span class="sr-only">supprimer categorie</span>
                            <TrashIcon class="mr-1 h-5 w-5" />
                        </button>
                    </div>
                </div>
            </dl>
        </div>
    </div>
</template>
