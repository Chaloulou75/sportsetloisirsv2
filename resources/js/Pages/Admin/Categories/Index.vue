<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    ChevronLeftIcon,
    TrashIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    categories: Object,
    user_can: Object,
});

const categorieForm = ref({});
const showCreateCategoryForm = ref(false);
const toggleCreateCategoryForm = () => {
    showCreateCategoryForm.value = !showCreateCategoryForm.value;
};

const initializecategorieForm = () => {
    for (const categorieId in props.categories) {
        const categorie = props.categories[categorieId];
        categorieForm.value[categorie.id] = useForm({
            nom: ref(categorie.nom),
            remember: true,
        });
    }
};

initializecategorieForm();

const createCategorieForm = useForm({
    nom: null,
});

const createCategorie = () => {
    createCategorieForm.post(route("admin.categories.store"), {
        errorBag: "createCategorieForm",
        preserveScroll: true,
        onSuccess: () => {
            initializecategorieForm();
            createCategorieForm.reset();
            toggleCreateCategoryForm();
        },
    });
};

const updateCategorie = (categorie) => {
    router.patch(
        route("admin.categories.update", {
            categorie: categorie,
        }),
        {
            nom: categorieForm.value[categorie.id].nom,
        },
        {
            errorBag: "categorieForm",
            preserveScroll: true,
        }
    );
};

const deleteCategorie = (categorie) => {
    router.delete(
        route("admin.categories.destroy", {
            categorie: categorie,
        }),
        {
            preserveScroll: true,
            categorie: categorie,
        }
    );
};

const attachAllDisciplines = (categorie) => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir lier toutes les disciplines?"
    );
    if (isConfirmed) {
        router.post(
            route("admin.categories.disciplines.store", {
                categorie: categorie,
            }),
            {
                preserveScroll: true,
            }
        );
    }
};

const detachAllDisciplines = (categorie) => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir délier toutes les disciplines?"
    );
    if (isConfirmed) {
        router.delete(
            route("admin.categories.disciplines.destroy", {
                categorie: categorie,
            }),
            {
                preserveScroll: true,
                categorie: categorie,
            }
        );
    }
};

const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head title="Gestion du site" :description="'Administration du site.'" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion des catégories
                </h1>
            </div>
        </template>

        <div class="w-full px-2 py-6 space-y-16 text-slate-700 md:px-6">
            <div
                class="flex flex-col items-start justify-center w-full py-4 space-y-4 md:flex-row md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <h3
                        class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Gérer les catégories existantes:
                    </h3>
                    <ul
                        ref="toAnimateOne"
                        class="max-w-3xl py-4 space-y-2 text-sm list-disc list-inside text-slate-600 marker:text-indigo-600"
                    >
                        <li
                            v-for="categorie in categories"
                            :key="categorie.id"
                            class="text-base text-slate-600"
                        >
                            <form
                                v-if="categorie"
                                class="inline-flex space-x-2"
                                @submit.prevent="updateCategorie(categorie)"
                            >
                                <div class="flex flex-col mt-1 rounded-md">
                                    <input
                                        v-model="
                                            categorieForm[categorie.id].nom
                                        "
                                        type="text"
                                        :name="categorie.nom"
                                        :id="categorie.nom"
                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                        placeholder=""
                                        autocomplete="none"
                                    />
                                    <div
                                        v-if="
                                            categorieForm[categorie.id].errors
                                                .nom
                                        "
                                        class="text-xs text-red-500"
                                    >
                                        {{
                                            categorieForm[categorie.id].errors
                                                .nom[0]
                                        }}
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <button type="submit">
                                        <ArrowPathIcon
                                            class="w-6 h-6 mr-1 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                        />
                                        <span class="sr-only"
                                            >Mettre à jour la catégorie</span
                                        >
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="deleteCategorie(categorie)"
                                >
                                    <TrashIcon class="w-5 h-5 text-red-500" />
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                <div
                    class="flex flex-col items-center justify-center w-full md:w-1/3"
                >
                    <h3
                        class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Créer une catégorie:
                    </h3>
                    <button
                        type="button"
                        v-if="!showCreateCategoryForm"
                        @click="toggleCreateCategoryForm"
                        class="inline-flex items-center justify-center w-auto px-4 py-3 space-y-1 text-sm font-medium text-center text-gray-600 border border-gray-600 rounded shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Créer une nouvelle catégorie
                    </button>
                    <form
                        v-if="showCreateCategoryForm"
                        class="flex flex-col items-start space-y-4"
                        @submit.prevent="createCategorie"
                    >
                        <div class="flex flex-col mt-1 rounded-md">
                            <input
                                v-model="createCategorieForm.nom"
                                type="text"
                                name="categorie_nom"
                                id="categorie_nom"
                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder=""
                                autocomplete="none"
                            />
                            <div
                                v-if="createCategorieForm.errors.nom"
                                class="text-xs text-red-500"
                            >
                                {{ createCategorieForm.errors.nom }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full">
                            <button
                                :disabled="createCategorieForm.processing"
                                :class="{
                                    'opacity-25':
                                        createCategorieForm.processing,
                                }"
                                class="px-2 py-2 text-sm font-medium text-center text-white bg-blue-600 border border-gray-300 rounded shadow-sm"
                                type="submit"
                            >
                                Enregistrer
                            </button>
                            <button
                                class="px-2 py-2 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded shadow-sm"
                                type="button"
                                @click="toggleCreateCategoryForm"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div
                class="flex flex-col items-center justify-center w-full py-4 space-y-4"
            >
                <h3
                    class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                >
                    Liaison des catégories aux disciplines:
                </h3>

                <div
                    class="flex flex-col items-start justify-around h-full gap-8 text-base text-slate-600 md:flex-row md:flex-wrap"
                >
                    <div
                        class="flex flex-col items-stretch justify-between max-w-md px-4 py-3 border border-gray-100 shadow bg-gray-50"
                        v-for="categorie in categories"
                        :key="categorie.id"
                    >
                        <div>
                            <h3>
                                <span
                                    class="text-lg font-semibold text-indigo-600"
                                >
                                    {{ categorie.nom }}</span
                                >
                                est lié aux
                                <span class="font-semibold">disciplines</span>
                                suivantes:
                            </h3>
                        </div>
                        <div class="my-4">
                            <ul class="ml-4 list-disc list-inside">
                                <li
                                    v-for="discipline in categorie.disciplines"
                                    :key="discipline.id"
                                >
                                    {{ discipline.name }}
                                </li>
                            </ul>
                        </div>
                        <div class="mt-auto space-y-3">
                            <button
                                type="button"
                                @click.prevent="attachAllDisciplines(categorie)"
                                class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm group hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                <div>
                                    Lier
                                    <span
                                        class="font-semibold text-red-500 group-hover:text-white"
                                        >{{ categorie.nom }}</span
                                    >
                                    à toutes les disciplines existantes?
                                </div>
                            </button>
                            <button
                                type="button"
                                @click.prevent="detachAllDisciplines(categorie)"
                                class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm group hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                <div>
                                    Délier
                                    <span
                                        class="font-semibold text-red-500 group-hover:text-white"
                                        >{{ categorie.nom }}</span
                                    >
                                    à toutes ses disciplines?
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
