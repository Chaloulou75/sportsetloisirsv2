<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { TrashIcon, PlusIcon } from "@heroicons/vue/24/outline";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";

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
                class="absolute right-2 top-2 bg-red-500 p-1"
                type="button"
                @click="emit('openDeleteModal', activite)"
            >
                <span class="sr-only">supprimer discipline</span>
                <TrashIcon class="h-6 w-6 text-red-100 hover:text-white" />
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
                <div class="flex items-center justify-between py-1">
                    <Link
                        :href="
                            route('structures.disciplines.show', {
                                structure: structure.slug,
                                discipline: activite.disciplineSlug,
                            })
                        "
                        class="px-2 py-2 text-center text-base font-semibold uppercase tracking-wide text-gray-600 hover:text-indigo-600"
                    >
                        {{ activite.disciplineName }}
                    </Link>
                    <BreezeDropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center border border-transparent bg-green-500 px-2 py-2 text-white transition duration-150 ease-in-out hover:bg-green-600 focus:outline-none"
                                >
                                    <PlusIcon class="h-5 w-5" />
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <BreezeDropdownLink
                                :href="
                                    route('structures.categories.show', {
                                        structure: structure.slug,
                                        discipline: activite.disciplineSlug,
                                        categorie: category.id,
                                    })
                                "
                                v-for="category in activite.missingCategories"
                                :key="category.id"
                            >
                                {{ category.nom_categorie_pro }}
                            </BreezeDropdownLink>
                        </template>
                    </BreezeDropdown>
                </div>
                <div class="w-full divide-y divide-slate-200">
                    <div
                        class="flex items-center justify-between pl-2 odd:bg-white even:bg-slate-50"
                        v-for="categorie in activite.categories"
                        :key="categorie.categorie_id"
                    >
                        <Link
                            :href="
                                route('structures.categories.show', {
                                    structure: structure.slug,
                                    discipline: activite.disciplineSlug,
                                    categorie: categorie.categorie_id,
                                })
                            "
                            class="py-2 text-sm hover:text-indigo-500"
                            >{{ categorie.name }}

                            <span class="text-sm">
                                ({{ categorie.count }})</span
                            >
                        </Link>
                        <button
                            type="button"
                            @click="emit('openDeleteCategorieModal', categorie)"
                            class="self-end bg-red-500 p-2 text-white hover:bg-red-600"
                        >
                            <span class="sr-only">supprimer categorie</span>
                            <TrashIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </dl>
        </div>
    </div>
</template>