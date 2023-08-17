<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import {
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const UpdateInfoBase = defineAsyncComponent(() =>
    import("@/Components/Disciplines/UpdateInfoBase.vue")
);

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    otherCategories: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    familles: Object,
    disciplineCategorieCriteres: Object,
    can: Object,
});

const attachDiscipline = (disciplineNotIn) => {
    router.post(
        route("discipline-similaire.store", {
            discipline: props.discipline,
        }),
        {
            preserveScroll: true,
            disciplineNotIn: disciplineNotIn.id,
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
            preserveScroll: "true",
            disciplineIn: disciplineIn.discipline_similaire_id,
        }
    );
};

const attachFamille = (familleNotIn) => {
    router.post(
        route("familles-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            preserveScroll: true,
            familleNotIn: familleNotIn.id,
        }
    );
};

const detachFamille = (familleIn) => {
    router.put(
        route("familles-disciplines.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            preserveScroll: "true",
            familleIn: familleIn.id,
        }
    );
};

const attachCategorie = (categorieNotIn) => {
    router.post(
        route("categories-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            preserveScroll: true,
            categorieNotIn: categorieNotIn.id,
        }
    );
};

const detachCategorie = (categorieIn) => {
    router.put(
        route("categories-disciplines.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            preserveScroll: "true",
            categorieIn: categorieIn.categorie_id,
        }
    );
};

const updateInfoBaseForm = useForm({
    name: ref(props.discipline.name),
    description: ref(props.discipline.description),
    remember: true,
});

const submitUpdateInfoBase = () => {
    router.put(
        route("disciplines.update", {
            discipline: props.discipline,
        }),
        {
            preserveScroll: true,
            name: updateInfoBaseForm.name,
            description: updateInfoBaseForm.description,
        }
    );
};

const categorieForms = props.categories.map((categorie) => {
    return useForm({
        nom_categorie_client: ref(categorie.nom_categorie_client),
        nom_categorie_pro: ref(categorie.nom_categorie_pro),
        remember: true,
    });
});

const updateCategorie = (index) => {
    router.patch(
        route("categories-disciplines.update", {
            discipline: props.discipline,
        }),
        {
            preserveScroll: true,
            // id: categorieForms[index].id,
            nom_categorie_client: categorieForms[index].nom_categorie_client,
            nom_categorie_pro: categorieForms[index].nom_categorie_pro,
            id: props.categories[index].id,
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
                <h2
                    class="text-xl font-semibold leading-tight tracking-widest text-gray-800"
                >
                    Administration de la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h2>
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
                {{ discipline.name }} sur sports-et-loisirs.fr. Disciplines
                similaires et catégories associées.
            </p>
        </template>

        <div class="space-y-8 py-6">
            <div class="px-2 md:px-6">
                <h1
                    class="text-center text-2xl font-bold uppercase tracking-wide text-indigo-600 md:text-4xl"
                >
                    {{ discipline.name }}
                </h1>
            </div>
            <!-- édition infos de base -->
            <div class="px-2 md:px-6">
                <form @submit.prevent="submitUpdateInfoBase" class="space-y-2">
                    <UpdateInfoBase
                        :errors="errors"
                        v-model:name="updateInfoBaseForm.name"
                        v-model:description="updateInfoBaseForm.description"
                    />
                    <button
                        :disabled="updateInfoBaseForm.processing"
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Editer la discipline
                    </button>
                </form>
            </div>

            <!-- les catégories associées -->
            <h2
                class="text-center text-2xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4"
            >
                Gestion des catégories associées à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 px-2 md:flex-row md:items-start md:justify-around md:space-y-0 md:px-6"
            >
                <div class="w-full md:w-2/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les catégories associées à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la catégorie)</span
                        >
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="(categorieIn, index) in categories"
                            :key="categorieIn.id"
                            class="inline-flex space-x-4"
                        >
                            <button
                                type="button"
                                @click="detachCategorie(categorieIn)"
                                class="group inline-flex w-48 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                {{ categorieIn.categorie.nom }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>

                            <form
                                class="inline-flex space-x-4"
                                @submit.prevent="updateCategorie(index)"
                            >
                                <div>
                                    <label
                                        for="nom_categorie_client"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Nom client
                                        <span class="text-xs italic"></span>
                                    </label>
                                    <div class="mt-1 flex rounded-md">
                                        <input
                                            v-model="
                                                categorieForms[index]
                                                    .nom_categorie_client
                                            "
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                    </div>
                                    <div
                                        v-if="
                                            categorieForms[index].errors
                                                .nom_categorie_client
                                        "
                                        class="text-xs text-red-500"
                                    >
                                        {{
                                            categorieForms[index].errors
                                                .nom_categorie_client[0]
                                        }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="nom_categorie_pro"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Nom Professionnel
                                        <span class="text-xs italic"></span>
                                    </label>
                                    <div class="mt-1 flex rounded-md">
                                        <input
                                            v-model="
                                                categorieForms[index]
                                                    .nom_categorie_pro
                                            "
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                    </div>
                                    <div
                                        v-if="
                                            categorieForms[index].errors
                                                .nom_categorie_pro
                                        "
                                        class="text-xs text-red-500"
                                    >
                                        {{
                                            categorieForms[index].errors
                                                .nom_categorie_pro[0]
                                        }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <button type="submit">
                                        <ArrowPathIcon
                                            class="mr-1 h-6 w-6 text-gray-600 transition-all duration-200 hover:-rotate-90 hover:text-gray-800"
                                        />
                                    </button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Ajouter des catégories à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="categorieNotIn in otherCategories"
                            :key="categorieNotIn.id"
                            class="group inline-flex space-x-4 self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachCategorie(categorieNotIn)"
                                class="inline-flex w-48 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ categorieNotIn.nom }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- les disciplines similaires -->
            <h2
                class="text-center text-2xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4"
            >
                Gestion des disciplines similaires à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 px-2 md:flex-row md:items-start md:justify-around md:space-y-0 md:px-6"
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
                        <Pagination :links="listDisciplines.links" />
                    </ul>
                </div>
            </div>

            <!-- les familles -->
            <h2
                class="text-center text-2xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4"
            >
                Les familles associées à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div class="flex items-start justify-around px-2 md:px-6">
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les familles associées à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la discipline)</span
                        >
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="familleIn in discipline.familles"
                            :key="familleIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="detachFamille(familleIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ familleIn.name }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Ajouter des familles de disciplines
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="familleNotIn in familles"
                            :key="familleNotIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachFamille(familleNotIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ familleNotIn.name }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- les criteres -->
            <h2
                class="text-center text-2xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4"
            >
                Les critères associés à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div class="flex items-start justify-around px-2 md:px-6">
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les critères associés à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur le critère)</span
                        >
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="critere in disciplineCategorieCriteres"
                            :key="critere.id"
                            class="group inline-flex self-stretch"
                        >
                            <p class="text-sm text-slate-600">
                                Categorie:
                                {{ critere.categorie.nom_categorie_client }}
                            </p>

                            <button
                                type="button"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ critere.nom }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>
                            <ul>
                                <li
                                    v-for="valeur in critere.valeurs"
                                    :key="valeur.id"
                                    class="liste-disc list-inside text-sm text-slate-600"
                                >
                                    {{ valeur.valeur }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Ajouter des familles de disciplines
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="familleNotIn in familles"
                            :key="familleNotIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachFamille(familleNotIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ familleNotIn.name }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
