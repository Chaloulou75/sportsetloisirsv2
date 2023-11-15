<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    ChevronLeftIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    otherCategories: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    familles: Object,
    user_can: Object,
    listeCriteres: Object,
});
</script>
<template>
    <Head
        :title="`Administration de la discipline ${discipline.name}`"
        :description="`Administration de la discipline ${discipline.name}`"
    />
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Administration de la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
            </div>
        </template>

        <div class="flex flex-col space-y-8 px-2 py-6 md:px-6">
            <ul class="list-inside list-disc space-y-3">
                <li>
                    <Link
                        :href="
                            route(
                                'admin.disciplines.informations.edit',
                                discipline
                            )
                        "
                        class="font-semibold text-slate-600 hover:text-indigo-600 hover:underline"
                    >
                        <span
                            >Gérer les
                            <span class="uppercase text-indigo-700">
                                informations
                            </span>
                            liées au
                            {{ discipline.name }}</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        :href="
                            route('admin.disciplines.familles.edit', discipline)
                        "
                        class="font-semibold text-slate-600 hover:text-indigo-600 hover:underline"
                    >
                        <span
                            >Gérer les
                            <span class="uppercase text-indigo-700">
                                familles
                            </span>
                            associées au
                            {{ discipline.name }}</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        :href="
                            route(
                                'admin.disciplines.similaires.edit',
                                discipline
                            )
                        "
                        class="font-semibold text-slate-600 hover:text-indigo-600 hover:underline"
                    >
                        <span
                            >Gérer les
                            <span class="uppercase text-indigo-700">
                                disciplines similaires
                            </span>
                            au
                            {{ discipline.name }}</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        :href="
                            route(
                                'admin.disciplines.categories.edit',
                                discipline
                            )
                        "
                        class="font-semibold text-slate-600 hover:text-indigo-600 hover:underline"
                    >
                        <span
                            >Gérer les
                            <span class="uppercase text-indigo-700">
                                catégories et critères
                            </span>
                            associés au
                            {{ discipline.name }}</span
                        >
                    </Link>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
