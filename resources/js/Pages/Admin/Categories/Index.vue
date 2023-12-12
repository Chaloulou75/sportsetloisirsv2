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

const toAnimateOne = ref();
onMounted(() => {
    autoAnimate(toAnimateOne.value);
});
</script>
<template>
    <Head title="Gestion du site" :description="'Administration du site.'" />
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
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion des catégories
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les catégories existantes:
                        </h3>
                        <ul
                            ref="toAnimateOne"
                            class="max-w-3xl list-inside list-disc space-y-2 py-4 text-sm text-slate-600 marker:text-indigo-600"
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
                                    <div class="mt-1 flex flex-col rounded-md">
                                        <input
                                            v-model="
                                                categorieForm[categorie.id].nom
                                            "
                                            type="text"
                                            :name="categorie.nom"
                                            :id="categorie.nom"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                        <div
                                            v-if="
                                                categorieForm[categorie.id]
                                                    .errors.nom
                                            "
                                            class="text-xs text-red-500"
                                        >
                                            {{
                                                categorieForm[categorie.id]
                                                    .errors.nom[0]
                                            }}
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <button type="submit">
                                            <ArrowPathIcon
                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                            />
                                            <span class="sr-only"
                                                >Mettre à jour la
                                                catégorie</span
                                            >
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        @click="deleteCategorie(categorie)"
                                    >
                                        <TrashIcon
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer une catégorie:
                        </h3>
                        <button
                            type="button"
                            v-if="!showCreateCategoryForm"
                            @click="toggleCreateCategoryForm"
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            Créer une nouvelle catégorie
                        </button>
                        <form
                            v-if="showCreateCategoryForm"
                            class="flex flex-col items-start space-y-4"
                            @submit.prevent="createCategorie"
                        >
                            <div class="mt-1 flex flex-col rounded-md">
                                <input
                                    v-model="createCategorieForm.nom"
                                    type="text"
                                    name="categorie_nom"
                                    id="categorie_nom"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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

                            <div
                                class="flex w-full items-center justify-between"
                            >
                                <button
                                    :disabled="createCategorieForm.processing"
                                    class="rounded border border-gray-300 bg-blue-600 px-2 py-2 text-center text-sm font-medium text-white shadow-sm"
                                    type="submit"
                                >
                                    Enregistrer
                                </button>
                                <button
                                    class="rounded border border-gray-300 bg-white px-2 py-2 text-center text-sm font-medium text-gray-600 shadow-sm"
                                    type="button"
                                    @click="toggleCreateCategoryForm"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
