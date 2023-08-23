<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, defineAsyncComponent, nextTick } from "vue";
import {
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    TrashIcon,
    ChevronUpDownIcon,
    CheckCircleIcon,
    ArrowUturnLeftIcon,
} from "@heroicons/vue/24/outline";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

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
    groupedData: Object,
    user_can: Object,
    listeCriteres: Object,
});

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
            name: updateInfoBaseForm.name,
            description: updateInfoBaseForm.description,
        },
        {
            preserveScroll: true,
        }
    );
};

const attachFamille = (familleNotIn) => {
    router.post(
        route("familles-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            familleNotIn: familleNotIn.id,
        },
        {
            preserveScroll: true,
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
            familleIn: familleIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};

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

const detachCategorie = (categorieIn) => {
    router.put(
        route("categories-disciplines.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            categorieIn: categorieIn.categorie_id,
        },
        {
            preserveScroll: true,
        }
    );
};

const attachCategorie = (categorieNotIn) => {
    router.post(
        route("categories-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            categorieNotIn: categorieNotIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const categorieForms = ref([]);

onMounted(() => {
    updateCategorieForms();
});

watch(
    () => props.categories,
    () => {
        updateCategorieForms();
    }
);

const updateCategorieForms = () => {
    categorieForms.value = props.categories.map((categorie) => {
        return useForm({
            nom_categorie_client: ref(categorie.nom_categorie_client ?? ""),
            nom_categorie_pro: ref(categorie.nom_categorie_pro ?? ""),
            remember: true,
        });
    });
};

const updateCategorie = (index) => {
    router.patch(
        route("categories-disciplines.update", {
            discipline: props.discipline,
        }),
        {
            nom_categorie_client: categorieForms[index].nom_categorie_client,
            nom_categorie_pro: categorieForms[index].nom_categorie_pro,
            id: props.categories[index].id,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                categorieForms.reset();
            },
        }
    );
};

const deleteCritere = (disciplineCategorieCritere) => {
    router.delete(
        route("categories-disciplines-criteres.destroy", {
            lienDisciplineCategorieCritere: disciplineCategorieCritere,
        }),
        {
            lienDisciplineCategorieCritere: disciplineCategorieCritere,
        },
        {
            preserveScroll: true,
        }
    );
};

const valeurForm = ref({});
const initializeValeurForm = () => {
    for (const categoryId in props.groupedData) {
        const category = props.groupedData[categoryId];

        for (const critereId in category.criteres) {
            const critere = category.criteres[critereId];

            for (const valeurId in critere.valeurs) {
                const valeur = critere.valeurs[valeurId];

                valeurForm.value[valeur.id] = useForm({
                    id: ref(valeur.id),
                    valeur: ref(valeur.valeur),
                    remember: true,
                });
            }
        }
    }
};

initializeValeurForm();

const updateValeur = (valeur) => {
    router.patch(
        route("categories-disciplines-criteres-valeurs.update", {
            lienDisCatCritValeur: valeur.id,
        }),
        {
            valeur: valeurForm.value[valeur.id].valeur,
            id: valeurForm.value[valeur.id].id,
        },
        {
            preserveScroll: true,
        }
    );
};

const valeurFormsVisibility = ref({});

const toggleAddValeurForm = (critere) => {
    valeurFormsVisibility.value[critere.disciplineCategorieCritere] =
        !valeurFormsVisibility.value[critere.disciplineCategorieCritere];
};

const showAddValeurForm = (critere) => {
    return (
        valeurFormsVisibility.value[critere.disciplineCategorieCritere] || false
    );
};

const addValeurForm = useForm({
    valeur: ref(null),
    remember: true,
});

const addValeur = (critere) => {
    router.post(
        route("categories-disciplines-criteres-valeurs.store", {
            critere: critere.disciplineCategorieCritere,
        }),
        {
            valeur: addValeurForm.valeur,
            disciplineCategorieCritereId: critere.disciplineCategorieCritere,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeValeurForm();
                addValeurForm.reset();
                toggleAddValeurForm(critere);
            },
        }
    );
};

const removeValeur = (valeur) => {
    router.delete(
        route("categories-disciplines-criteres-valeurs.destroy", {
            lienDisCatCritValeur: valeur,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeValeurForm();
            },
        }
    );
};

const critereFormsVisibility = ref({});

const toggleAddCritereForm = (categorie) => {
    critereFormsVisibility.value[categorie.categorie.id] =
        !critereFormsVisibility.value[categorie.categorie.id];
};

const showAddCritereForm = (categorie) => {
    return critereFormsVisibility.value[categorie.categorie.id] || false;
};

const type_champs = [{ type: "select" }, { type: "checkbox" }];

const addCritereForm = useForm({
    critere: ref(props.listeCriteres[0]),
    type_champ: ref(type_champs[0]),
    remember: true,
});

const addCritere = (categorie) => {
    router.post(
        route("categories-disciplines-criteres.store"),
        {
            critere: addCritereForm.critere,
            categorie: categorie.categorie,
            type_champ: addCritereForm.type_champ,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                addCritereForm.reset();
                toggleAddCritereForm(categorie);
            },
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
                    class="text-center text-xl font-semibold leading-tight tracking-widest text-gray-800"
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
        <div class="px-2 py-6 md:px-6">
            <Link
                :href="route('admin.index')"
                class="hidden w-1/5 items-center justify-center rounded border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 shadow-sm hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2 md:flex"
            >
                <ArrowUturnLeftIcon class="mr-3 h-5 w-5 self-end" />
                Retour administration
            </Link>
        </div>
        <div class="px-2 md:px-6">
            <h1
                class="text-center text-2xl font-bold uppercase tracking-wide text-indigo-600 md:text-4xl"
            >
                {{ discipline.name }}
            </h1>
        </div>

        <div class="space-y-16 px-2 py-6 md:px-6">
            <!-- édition infos de base -->
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Informations de base
            </h2>
            <div>
                <form @submit.prevent="submitUpdateInfoBase" class="space-y-4">
                    <UpdateInfoBase
                        :errors="errors"
                        v-model:name="updateInfoBaseForm.name"
                        v-model:description="updateInfoBaseForm.description"
                    />
                    <button
                        :disabled="updateInfoBaseForm.processing"
                        type="submit"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                    >
                        Editer la discipline
                    </button>
                </form>
            </div>

            <!-- les familles -->
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Les familles associées à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 md:flex-row md:items-start md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les familles associées à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la famille)</span
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

            <!-- les disciplines similaires -->
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des disciplines similaires à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
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
                        <Pagination :links="listDisciplines.links" />
                    </ul>
                </div>
            </div>

            <!-- les catégories associées -->
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des catégories associées à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 md:flex-row md:items-start md:justify-around md:space-y-0"
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
                            <template v-if="categorieForms[index]">
                                <form
                                    class="inline-flex flex-col space-y-2 md:flex-row md:space-x-4 md:space-y-0"
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
                                                name="nom_categorie_client"
                                                id="nom_categorie_client"
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
                                                name="nom_categorie_pro"
                                                id="nom_categorie_pro"
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
                                    <div
                                        class="mx-auto flex w-full items-center justify-center md:w-auto"
                                    >
                                        <button
                                            type="submit"
                                            class="flex items-center justify-center rounded-md border border-gray-200 px-4 py-2 md:border-none"
                                        >
                                            <span class="mr-2 text-xs md:hidden"
                                                >Mettre à jour</span
                                            >
                                            <ArrowPathIcon
                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                            />
                                            <span class="sr-only"
                                                >Mettre à jour la
                                                catégorie</span
                                            >
                                        </button>
                                    </div>
                                </form>
                            </template>
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

            <!-- les criteres -->
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Les critères associés à
                <span class="text-indigo-600">{{ discipline.name }}</span>
            </h2>
            <template v-if="groupedData" class="w-full">
                <ul
                    class="flex flex-wrap justify-center gap-10 md:justify-start"
                >
                    <li
                        v-for="categorie in groupedData"
                        :key="categorie.id"
                        class="gap-4"
                    >
                        <p
                            class="text-base text-slate-600 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Catégorie:
                            <span class="font-semibold">{{
                                categorie.categorie.nom_categorie_client
                            }}</span>
                        </p>

                        <ul
                            class="list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                        >
                            <li
                                v-for="critere in categorie.criteres"
                                :key="critere.disciplineCategorieCritere"
                                class="flex flex-col text-base text-slate-600"
                            >
                                <div
                                    class="inline-flex w-full items-center justify-between"
                                >
                                    <span class="flex-grow"
                                        >Critère:
                                        <span class="font-semibold">{{
                                            critere.critere.nom
                                        }}</span></span
                                    >

                                    <button
                                        type="button"
                                        class="inline-flex items-center"
                                        @click="
                                            deleteCritere(
                                                critere.disciplineCategorieCritere
                                            )
                                        "
                                    >
                                        <TrashIcon
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                </div>
                                <ul
                                    class="ml-4 list-inside list-disc marker:text-indigo-600"
                                >
                                    <li
                                        v-for="valeur in critere.valeurs"
                                        :key="valeur.id"
                                        class="text-sm text-slate-600"
                                    >
                                        <template v-if="valeurForm[valeur.id]">
                                            <form
                                                v-if="valeur"
                                                class="inline-flex space-x-4"
                                                @submit.prevent="
                                                    updateValeur(valeur)
                                                "
                                            >
                                                <div>
                                                    <label
                                                        :for="valeur[valeur.id]"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        <span
                                                            class="text-xs italic"
                                                        ></span>
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                valeurForm[
                                                                    valeur.id
                                                                ].valeur
                                                            "
                                                            type="text"
                                                            :name="
                                                                valeur[
                                                                    valeur.id
                                                                ]
                                                            "
                                                            :id="
                                                                valeur[
                                                                    valeur.id
                                                                ]
                                                            "
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <div
                                                        v-if="
                                                            valeurForm[
                                                                valeur.id
                                                            ].errors.valeur
                                                        "
                                                        class="text-xs text-red-500"
                                                    >
                                                        {{
                                                            valeurForm[
                                                                valeur.id
                                                            ].errors.valeur[0]
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
                                                            valeur</span
                                                        >
                                                    </button>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center"
                                                    @click="
                                                        removeValeur(valeur)
                                                    "
                                                >
                                                    <TrashIcon
                                                        class="h-5 w-5 text-red-500 hover:text-red-700"
                                                    />
                                                </button>
                                            </form>
                                        </template>
                                    </li>
                                </ul>
                                <ul
                                    v-if="showAddValeurForm(critere)"
                                    class="ml-4 list-inside list-disc marker:text-indigo-600"
                                >
                                    <li class="text-sm text-slate-600">
                                        <form
                                            class="inline-flex flex-grow items-center justify-between"
                                            @submit.prevent="addValeur(critere)"
                                        >
                                            <div>
                                                <label for="newValeur"></label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="
                                                            addValeurForm.valeur
                                                        "
                                                        type="text"
                                                        name="newValeur"
                                                        id="newValeur"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                            </div>
                                            <button
                                                type="submit"
                                                class="ml-4 inline-flex items-center"
                                            >
                                                <PlusCircleIcon
                                                    class="h-6 w-6 text-indigo-500 hover:text-indigo-700"
                                                />
                                            </button>
                                            <button
                                                @click="
                                                    toggleAddValeurForm(critere)
                                                "
                                                type="button"
                                                class="ml-4 inline-flex items-center"
                                            >
                                                <XCircleIcon
                                                    class="h-6 w-6 text-red-500 hover:text-red-700"
                                                />
                                            </button>

                                            <div
                                                v-if="
                                                    addValeurForm.errors.valeur
                                                "
                                                class="text-xs text-red-500"
                                            >
                                                {{
                                                    addValeurForm.errors
                                                        .valeur[0]
                                                }}
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                                <div
                                    class="flex w-full items-center justify-center py-2"
                                >
                                    <button
                                        class="inline-flex items-center justify-center space-y-1 rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                        v-if="!showAddValeurForm(critere)"
                                        type="button"
                                        @click="toggleAddValeurForm(critere)"
                                    >
                                        Ajouter une valeur
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="flex w-full items-center justify-start">
                            <button
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                v-if="!showAddCritereForm(categorie)"
                                type="button"
                                @click="toggleAddCritereForm(categorie)"
                            >
                                Ajouter un critère
                            </button>
                            <form
                                v-if="showAddCritereForm(categorie)"
                                class="inline-flex flex-grow items-center justify-between"
                                @submit.prevent="addCritere(categorie)"
                            >
                                <div
                                    class="flex w-full flex-grow flex-col space-y-3"
                                >
                                    <Listbox
                                        class="w-full flex-grow"
                                        v-model="addCritereForm.critere"
                                    >
                                        <div class="relative mt-1">
                                            <ListboxButton
                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                            >
                                                <span class="block truncate">{{
                                                    addCritereForm.critere.nom
                                                }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                                >
                                                    <ChevronUpDownIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </ListboxButton>

                                            <transition
                                                leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100"
                                                leave-to-class="opacity-0"
                                            >
                                                <ListboxOptions
                                                    class="absolute z-40 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                >
                                                    <ListboxOption
                                                        v-slot="{
                                                            active,
                                                            selected,
                                                        }"
                                                        v-for="critere in props.listeCriteres"
                                                        :key="critere.id"
                                                        :value="critere"
                                                        as="template"
                                                    >
                                                        <li
                                                            :class="[
                                                                active
                                                                    ? 'bg-amber-100 text-amber-900'
                                                                    : 'text-gray-700',
                                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                                            ]"
                                                        >
                                                            <span
                                                                :class="[
                                                                    selected
                                                                        ? 'font-medium'
                                                                        : 'font-normal',
                                                                    'block truncate',
                                                                ]"
                                                                >{{
                                                                    critere.nom
                                                                }}</span
                                                            >
                                                            <span
                                                                v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                            >
                                                                <CheckCircleIcon
                                                                    class="h-5 w-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>

                                    <Listbox
                                        v-if="addCritereForm.critere"
                                        class="w-full flex-grow"
                                        v-model="addCritereForm.type_champ"
                                    >
                                        <div class="relative mt-1">
                                            <ListboxButton
                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                            >
                                                <span class="block truncate">{{
                                                    addCritereForm.type_champ
                                                        .type
                                                }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                                >
                                                    <ChevronUpDownIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </ListboxButton>

                                            <transition
                                                leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100"
                                                leave-to-class="opacity-0"
                                            >
                                                <ListboxOptions
                                                    class="absolute z-40 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                >
                                                    <ListboxOption
                                                        v-slot="{
                                                            active,
                                                            selected,
                                                        }"
                                                        v-for="(
                                                            type_champ, index
                                                        ) in type_champs"
                                                        :key="index"
                                                        :value="type_champ"
                                                        as="template"
                                                    >
                                                        <li
                                                            :class="[
                                                                active
                                                                    ? 'bg-amber-100 text-amber-900'
                                                                    : 'text-gray-700',
                                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                                            ]"
                                                        >
                                                            <span
                                                                :class="[
                                                                    selected
                                                                        ? 'font-medium'
                                                                        : 'font-normal',
                                                                    'block truncate',
                                                                ]"
                                                                >{{
                                                                    type_champ.type
                                                                }}</span
                                                            >
                                                            <span
                                                                v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                            >
                                                                <CheckCircleIcon
                                                                    class="h-5 w-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>
                                <button
                                    type="submit"
                                    class="ml-4 inline-flex items-center"
                                >
                                    <PlusCircleIcon
                                        class="h-6 w-6 text-indigo-500 hover:text-indigo-700"
                                    />
                                </button>
                                <button
                                    @click="toggleAddCritereForm(categorie)"
                                    type="button"
                                    class="ml-4 inline-flex items-center"
                                >
                                    <XCircleIcon
                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </template>
            <template v-else>
                <ul
                    class="flex flex-wrap justify-center gap-10 md:justify-start"
                >
                    <li
                        v-for="categorie in categories"
                        :key="categorie.id"
                        class="gap-4"
                    >
                        <p
                            class="text-base text-slate-600 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Catégorie:
                            <span
                                v-if="categorie.nom_categorie_client"
                                class="font-semibold"
                                >{{ categorie.nom_categorie_client }}</span
                            ><span v-else class="font-semibold">{{
                                categorie.categorie.nom
                            }}</span>
                        </p>
                        <div
                            class="my-4 flex w-full items-center justify-start"
                        >
                            <button
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                v-if="!showAddCritereForm(categorie)"
                                type="button"
                                @click="toggleAddCritereForm(categorie)"
                            >
                                Ajouter un critère
                            </button>
                            <form
                                v-if="showAddCritereForm(categorie)"
                                class="inline-flex flex-grow items-center justify-between"
                                @submit.prevent="addCritere(categorie)"
                            >
                                <div
                                    class="flex w-full flex-grow flex-col space-y-3"
                                >
                                    <Listbox
                                        class="w-full flex-grow"
                                        v-model="addCritereForm.critere"
                                    >
                                        <div class="relative mt-1">
                                            <ListboxButton
                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                            >
                                                <span class="block truncate">{{
                                                    addCritereForm.critere.nom
                                                }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                                >
                                                    <ChevronUpDownIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </ListboxButton>

                                            <transition
                                                leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100"
                                                leave-to-class="opacity-0"
                                            >
                                                <ListboxOptions
                                                    class="absolute z-40 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                >
                                                    <ListboxOption
                                                        v-slot="{
                                                            active,
                                                            selected,
                                                        }"
                                                        v-for="critere in props.listeCriteres"
                                                        :key="critere.id"
                                                        :value="critere"
                                                        as="template"
                                                    >
                                                        <li
                                                            :class="[
                                                                active
                                                                    ? 'bg-amber-100 text-amber-900'
                                                                    : 'text-gray-700',
                                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                                            ]"
                                                        >
                                                            <span
                                                                :class="[
                                                                    selected
                                                                        ? 'font-medium'
                                                                        : 'font-normal',
                                                                    'block truncate',
                                                                ]"
                                                                >{{
                                                                    critere.nom
                                                                }}</span
                                                            >
                                                            <span
                                                                v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                            >
                                                                <CheckCircleIcon
                                                                    class="h-5 w-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>

                                    <Listbox
                                        v-if="addCritereForm.critere"
                                        class="w-full flex-grow"
                                        v-model="addCritereForm.type_champ"
                                    >
                                        <div class="relative mt-1">
                                            <ListboxButton
                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                            >
                                                <span class="block truncate">{{
                                                    addCritereForm.type_champ
                                                        .type
                                                }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                                >
                                                    <ChevronUpDownIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </ListboxButton>

                                            <transition
                                                leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100"
                                                leave-to-class="opacity-0"
                                            >
                                                <ListboxOptions
                                                    class="absolute z-40 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                >
                                                    <ListboxOption
                                                        v-slot="{
                                                            active,
                                                            selected,
                                                        }"
                                                        v-for="(
                                                            type_champ, index
                                                        ) in type_champs"
                                                        :key="index"
                                                        :value="type_champ"
                                                        as="template"
                                                    >
                                                        <li
                                                            :class="[
                                                                active
                                                                    ? 'bg-amber-100 text-amber-900'
                                                                    : 'text-gray-700',
                                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                                            ]"
                                                        >
                                                            <span
                                                                :class="[
                                                                    selected
                                                                        ? 'font-medium'
                                                                        : 'font-normal',
                                                                    'block truncate',
                                                                ]"
                                                                >{{
                                                                    type_champ.type
                                                                }}</span
                                                            >
                                                            <span
                                                                v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                            >
                                                                <CheckCircleIcon
                                                                    class="h-5 w-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>
                                <button
                                    type="submit"
                                    class="ml-4 inline-flex items-center"
                                >
                                    <PlusCircleIcon
                                        class="h-6 w-6 text-indigo-500 hover:text-indigo-700"
                                    />
                                </button>
                                <button
                                    @click="toggleAddCritereForm(categorie)"
                                    type="button"
                                    class="ml-4 inline-flex items-center"
                                >
                                    <XCircleIcon
                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </template>
        </div>
    </AppLayout>
</template>
