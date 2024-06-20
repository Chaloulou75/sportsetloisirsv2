<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";
import {
    XCircleIcon,
    PlusCircleIcon,
    ChevronLeftIcon,
} from "@heroicons/vue/24/outline";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    errors: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    user_can: Object,
});

const attachDiscipline = (disciplineNotIn) => {
    router.post(
        route("discipline-similaire.store", {
            discipline: props.discipline,
        }),
        {
            disciplineNotIn: disciplineNotIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const detachDiscipline = (disciplineIn) => {
    router.put(
        route("discipline-similaire.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            disciplineIn: disciplineIn.discipline_similaire_id,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <Head :title="`Administration de la discipline ${discipline.name}`">
        <meta
            head-key="description"
            name="description"
            :content="`Administration de la discipline ${discipline.name}`"
        />
    </Head>
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.disciplines.edit', discipline)"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Disciplines similaires associées à la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />

        <div class="space-y-16 px-2 py-6 md:px-6">
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 md:flex-row md:items-start md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les disciplines similaires à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la discipline)</span
                        >
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="disciplineIn in disciplinesSimilaires"
                            :key="disciplineIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="detachDiscipline(disciplineIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ disciplineIn.name }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-2/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Ajouter une discipline similaire à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                    </h3>
                    <ul
                        class="flex flex-wrap items-stretch justify-center gap-2"
                    >
                        <li
                            v-for="disciplineNotIn in listDisciplines.data"
                            :key="disciplineNotIn.discipline_similaire_id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachDiscipline(disciplineNotIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ disciplineNotIn.name }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                        <Pagination
                            :links="listDisciplines.meta.links"
                            :only="['listDisciplines']"
                        />
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
