<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import { XCircleIcon, PlusCircleIcon } from "@heroicons/vue/24/outline";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    discipline: Object,
    categories: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    can: Object,
});

const attach = (disciplineNotIn) => {
    router.post(
        route("discipline-similaire.store", {
            discipline: props.discipline,
        }),
        {
            disciplineNotIn: disciplineNotIn.id,
        }
    );
};

const detach = (disciplineIn) => {
    router.put(
        route("discipline-similaire.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            disciplineIn: disciplineIn.discipline_similaire_id,
        }
    );
};
</script>
<template>
    <Head
        :title="`Administration de la discipline ${discipline.name}`"
        :description="`Administration de la discipline ${discipline.name}`"
    />
    <AppLayout>
        <template #header>
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-xl font-semibold leading-tight tracking-widest text-gray-800"
                >
                    Administration de la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol
                        class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                    >
                        <li class="flex items-center">
                            <Link
                                preserve-scroll
                                :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
                                    Accueil
                                </span>
                            </Link>
                        </li>

                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                            >
                            </span>

                            <Link
                                preserve-scroll
                                :href="route('admin.index')"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                Admin
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Page d'administration de la discipline
                {{ discipline.name }} sports-et-loisirs.fr. Disciplines et
                Catégories.
            </p>
        </template>

        <div class="space-y-8 py-6">
            <div class="px-2 md:px-6">
                <h2 class="text-center text-2xl text-indigo-600">
                    {{ discipline.name }}
                </h2>
                <div class="flex items-center gap-x-6 py-4">
                    {{ discipline.description }}
                </div>
            </div>

            <div class="px-2 md:px-6">
                <h2 class="text-center text-2xl text-slate-700">
                    Les catégories associées
                </h2>
                <ul class="list-inside list-disc gap-y-2">
                    <li
                        v-for="categorie in categories"
                        :key="categorie.id"
                        class="flex items-start text-sm"
                    >
                        client: {{ categorie.nom_categorie_client }} / pro:
                        {{ categorie.nom_categorie_pro }}
                    </li>
                </ul>
            </div>
            <h2 class="text-center text-2xl text-slate-700">
                Gestion des disciplines similaires
            </h2>
            <div class="flex items-start justify-around px-2 md:px-6">
                <div class="w-full md:w-1/3">
                    <h3 class="text-center text-lg text-slate-700">
                        Les disciplines similaires
                    </h3>
                    <ul class="flex list-inside list-disc flex-wrap gap-2">
                        <li
                            v-for="disciplineIn in disciplinesSimilaires"
                            :key="disciplineIn.id"
                            class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ disciplineIn.name }}
                            <button type="button" @click="detach(disciplineIn)">
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-2/3">
                    <h3 class="text-center text-lg text-slate-700">
                        Ajouter une discipline
                    </h3>
                    <ul class="flex list-inside list-disc flex-wrap gap-2">
                        <li
                            v-for="disciplineNotIn in listDisciplines.data"
                            :key="disciplineNotIn.discipline_similaire_id"
                            class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ disciplineNotIn.name }}
                            <button
                                type="button"
                                @click="attach(disciplineNotIn)"
                            >
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500"
                                />
                            </button>
                        </li>
                        <Pagination :links="listDisciplines.links" />
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
