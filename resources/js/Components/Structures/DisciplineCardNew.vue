<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";
import { TrashIcon, PlusIcon } from "@heroicons/vue/24/outline";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["openDeleteModal", "openDeleteCategorieModal"]);

const props = defineProps({
    discipline: Object,
    structure: Object,
});

const isShowing = ref(true);

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    if (props.discipline && props.discipline.id) {
        const disciplineId = props.discipline.id;
        if (classMapping[disciplineId]) {
            return classMapping[disciplineId];
        } else {
            return defaultClass;
        }
    } else {
        return defaultClass;
    }
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
        <div
            class="block h-full shadow-sm shadow-indigo-200 ring ring-gray-300 transition duration-300 ease-in-out hover:shadow-lg"
        >
            <div
                class="relative h-56 w-full bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light"
                :class="headerClass"
            >
                <button
                    class="absolute right-2 top-2 bg-red-500 p-1"
                    type="button"
                    @click.prevent="emit('openDeleteModal', discipline)"
                >
                    <span class="sr-only">supprimer discipline</span>
                    <TrashIcon class="h-6 w-6 text-red-100 hover:text-white" />
                </button>
            </div>

            <div class="mt-2">
                <dl class="flex flex-col">
                    <div class="flex items-center justify-between py-1">
                        <Link
                            :href="
                                route('structures.disciplines.show', {
                                    structure: structure,
                                    discipline: discipline,
                                })
                            "
                            class="px-2 py-2 text-center text-base font-semibold uppercase tracking-wide text-gray-600 hover:text-indigo-600"
                        >
                            {{ discipline.name }}
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
                                            discipline: discipline.slug,
                                            categorie: categorie.pivot.id,
                                        })
                                    "
                                    v-for="categorie in discipline.categories"
                                    :key="categorie.id"
                                >
                                    {{ categorie.pivot.nom_categorie_pro }}
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </div>
                    <div class="w-full divide-y divide-slate-200">
                        <div
                            class="flex items-center justify-between pl-2 odd:bg-white even:bg-slate-50"
                            v-for="categorie in discipline.str_categories"
                            :key="categorie.id"
                        >
                            <Link
                                :href="
                                    route('structures.categories.show', {
                                        structure: structure.slug,
                                        discipline: discipline.slug,
                                        categorie: categorie.id,
                                    })
                                "
                                class="py-2 text-sm hover:text-indigo-500"
                                >{{ categorie.nom_categorie_pro }}

                                <span class="text-sm">
                                    ({{ categorie.str_activites_count }})</span
                                >
                            </Link>
                            <button
                                type="button"
                                @click="
                                    emit('openDeleteCategorieModal', categorie)
                                "
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
    </TransitionRoot>
</template>
