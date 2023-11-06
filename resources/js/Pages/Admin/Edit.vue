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
            nom_categorie_client:
                categorieForms.value[index].nom_categorie_client,
            nom_categorie_pro: categorieForms.value[index].nom_categorie_pro,
            id: props.categories[index].id,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                categorieForm.reset();
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

const deleteSousCritere = (souscritere) => {
    router.delete(
        route("dcc-sous-criteres.destroy", {
            souscritere: souscritere,
        }),
        {
            preserveScroll: true,
        }
    );
};

const valeurForm = ref({});
const sousvaleurForm = ref({});
const initializeValeurForm = () => {
    for (const categoryId in props.categories) {
        const category = props.categories[categoryId];

        for (const critereId in category.criteres) {
            const critere = category.criteres[critereId];

            for (const valeurId in critere.valeurs) {
                const valeur = critere.valeurs[valeurId];

                valeurForm.value[valeur.id] = useForm({
                    id: ref(valeur.id),
                    valeur: ref(valeur.valeur),
                    remember: true,
                });

                for (const souscritereId in valeur.sous_criteres) {
                    const souscritere = valeur.sous_criteres[souscritereId];

                    for (const sousvaleurId in souscritere.sous_criteres_valeurs) {
                        const sousvaleur =
                            souscritere.sous_criteres_valeurs[sousvaleurId];

                        sousvaleurForm.value[sousvaleur.id] = useForm({
                            id: ref(sousvaleur.id),
                            valeur: ref(sousvaleur.valeur),
                            remember: true,
                        });
                    }
                }
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
    valeurFormsVisibility.value[critere] =
        !valeurFormsVisibility.value[critere];
};

const showAddValeurForm = (critere) => {
    return valeurFormsVisibility.value[critere] || false;
};

const addValeurForm = useForm({
    valeur: ref(null),
    remember: true,
});

const addValeur = (critere) => {
    router.post(
        route("categories-disciplines-criteres-valeurs.store", {
            critere: critere,
        }),
        {
            valeur: addValeurForm.valeur,
            disciplineCategorieCritereId: critere.id,
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

//Sous critere Valeurs
const updateSousValeur = (sousvaleur) => {
    router.patch(
        route("dcc-sous-criteres-valeurs.update", {
            sousvaleur: sousvaleur,
        }),
        {
            valeur: sousvaleurForm.value[sousvaleur.id].valeur,
            id: sousvaleurForm.value[sousvaleur.id].id,
        },
        {
            preserveScroll: true,
        }
    );
};

const sousValeurFormsVisibility = ref({});

const toggleAddSousValeurForm = (souscritere) => {
    sousValeurFormsVisibility.value[souscritere] =
        !sousValeurFormsVisibility.value[souscritere];
};

const showAddSousValeurForm = (souscritere) => {
    return sousValeurFormsVisibility.value[souscritere] || false;
};

const addSousValeurForm = useForm({
    sousvaleur: ref(null),
    remember: true,
});

const addSousValeur = (souscritere) => {
    router.post(
        route("dcc-sous-criteres-valeurs.store", {
            souscritere: souscritere,
        }),
        {
            valeur: addSousValeurForm.valeur,
            dccValSsCritId: souscritere.id,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeValeurForm();
                addSousValeurForm.reset();
                toggleAddSousValeurForm(souscritere);
            },
        }
    );
};

const removeSousValeur = (sousvaleur) => {
    router.delete(
        route("dcc-sous-criteres-valeurs.destroy", {
            sousvaleur: sousvaleur,
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
    critereFormsVisibility.value[categorie.id] =
        !critereFormsVisibility.value[categorie.id];
};

const showAddCritereForm = (categorie) => {
    return critereFormsVisibility.value[categorie.id] || false;
};

const type_champs = [
    { type: "select" },
    { type: "checkbox" },
    { type: "text" },
    { type: "number" },
    { type: "adresse" },
    { type: "date" },
    { type: "dates" },
    { type: "time" },
    { type: "times" },
    { type: "mois" },
    { type: "rayon" },
    { type: "instructeur" },
];

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

const sousCritereFormsVisibility = ref({});

const toggleAddSousCritereForm = (valeur) => {
    addSousCritereForm.defaults();
    sousCritereFormsVisibility.value[valeur.id] =
        !sousCritereFormsVisibility.value[valeur.id];
};

const showAddSousCritereForm = (valeur) => {
    return sousCritereFormsVisibility.value[valeur.id] || false;
};

const addSousCritereForm = useForm({
    nom: ref(null),
    type_champ: ref(type_champs[0]),
    remember: true,
});

const addSousCritere = (valeur) => {
    router.post(
        route("dcc-sous-criteres.store", {
            valeur: valeur,
        }),
        {
            nom: addSousCritereForm.nom,
            type_champ: addSousCritereForm.type_champ.type,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                addSousCritereForm.reset();
                toggleAddSousCritereForm(valeur);
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
                                Gestion du site
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
                Les <span class="text-indigo-600">critères</span> associés aux
                catégories.
            </h2>
            <template v-if="categories.length > 0" class="w-full">
                <ul
                    class="grid grid-cols-1 place-items-start justify-items-stretch gap-4 md:grid-cols-3 md:gap-8"
                >
                    <li
                        v-for="categorie in categories"
                        :key="categorie.id"
                        class="rounded-md border border-indigo-600 bg-gray-50 px-1 py-6 shadow-sm md:px-3"
                    >
                        <p
                            class="text-center text-lg text-slate-600 underline decoration-yellow-400 decoration-4 underline-offset-4"
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

                        <ul
                            class="list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                        >
                            <li
                                v-for="critere in categorie.criteres"
                                :key="critere.id"
                                class="flex flex-col text-base text-slate-600"
                            >
                                <div
                                    class="inline-flex w-full items-center justify-between"
                                >
                                    <div
                                        class="flex-grow pb-2 underline decoration-blue-500 decoration-2 underline-offset-2"
                                    >
                                        Critère:
                                        <span class="font-semibold">
                                            {{ critere.nom }}
                                            <span class="text-xs font-medium"
                                                >(Type de champ:
                                                {{
                                                    critere.type_champ_form
                                                }})</span
                                            >
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        class="inline-flex items-center"
                                        @click="deleteCritere(critere)"
                                    >
                                        <TrashIcon
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                </div>
                                <ul
                                    class="list-inside list-disc marker:text-indigo-600"
                                >
                                    <li
                                        v-for="valeur in critere.valeurs"
                                        :key="valeur.id"
                                        class="space-y-1 text-sm text-slate-600"
                                    >
                                        <template v-if="valeurForm[valeur.id]">
                                            <form
                                                v-if="valeur"
                                                class="inline-flex space-x-2"
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
                                                <button
                                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                    v-if="
                                                        !showAddSousCritereForm(
                                                            valeur
                                                        ) &&
                                                        valeur.sous_criteres
                                                            .length === 0
                                                    "
                                                    type="button"
                                                    @click="
                                                        toggleAddSousCritereForm(
                                                            valeur
                                                        )
                                                    "
                                                >
                                                    <div class="">
                                                        Ajouter un sous critère
                                                        à
                                                        <span
                                                            class="font-semibold"
                                                            >{{
                                                                valeur.valeur
                                                            }}</span
                                                        >
                                                    </div>
                                                </button>
                                            </form>
                                            <div v-if="valeur.sous_criteres">
                                                <div
                                                    v-for="souscritere in valeur.sous_criteres"
                                                    :key="souscritere.id"
                                                    class="ml-4 list-inside list-disc marker:text-indigo-600"
                                                >
                                                    <div class="py-2">
                                                        <div
                                                            class="flex flex-grow items-center justify-around"
                                                        >
                                                            <div
                                                                class="underline decoration-pink-300 decoration-2 underline-offset-2"
                                                            >
                                                                Sous critère:
                                                                <span
                                                                    class="font-semibold"
                                                                    >{{
                                                                        souscritere.nom
                                                                    }}
                                                                    <span
                                                                        class="text-sm font-medium"
                                                                        >(Champ:
                                                                        {{
                                                                            souscritere.type_champ_form
                                                                        }})</span
                                                                    ></span
                                                                >
                                                            </div>

                                                            <button
                                                                type="button"
                                                                class="inline-flex items-center"
                                                                @click="
                                                                    deleteSousCritere(
                                                                        souscritere
                                                                    )
                                                                "
                                                            >
                                                                <TrashIcon
                                                                    class="h-5 w-5 text-red-500 hover:text-red-700"
                                                                />
                                                            </button>
                                                        </div>
                                                        <ul
                                                            v-if="
                                                                souscritere.sous_criteres_valeurs
                                                            "
                                                            class="ml-4 list-inside list-disc marker:text-indigo-600"
                                                        >
                                                            <li
                                                                v-for="valeur in souscritere.sous_criteres_valeurs"
                                                                :key="valeur.id"
                                                                class="text-sm text-slate-600"
                                                            >
                                                                <form
                                                                    v-if="
                                                                        valeur
                                                                    "
                                                                    class="inline-flex space-x-4"
                                                                    @submit.prevent="
                                                                        updateSousValeur(
                                                                            valeur
                                                                        )
                                                                    "
                                                                >
                                                                    <div>
                                                                        <label
                                                                            :for="
                                                                                valeur[
                                                                                    valeur
                                                                                        .id
                                                                                ]
                                                                            "
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
                                                                                    sousvaleurForm[
                                                                                        valeur
                                                                                            .id
                                                                                    ]
                                                                                        .valeur
                                                                                "
                                                                                type="text"
                                                                                :name="
                                                                                    valeur[
                                                                                        valeur
                                                                                            .id
                                                                                    ]
                                                                                "
                                                                                :id="
                                                                                    valeur[
                                                                                        valeur
                                                                                            .id
                                                                                    ]
                                                                                "
                                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                                placeholder=""
                                                                                autocomplete="none"
                                                                            />
                                                                        </div>
                                                                        <div
                                                                            v-if="
                                                                                sousvaleurForm[
                                                                                    valeur
                                                                                        .id
                                                                                ]
                                                                                    .errors
                                                                                    .valeur
                                                                            "
                                                                            class="text-xs text-red-500"
                                                                        >
                                                                            {{
                                                                                sousvaleurForm[
                                                                                    valeur
                                                                                        .id
                                                                                ]
                                                                                    .errors
                                                                                    .valeur[0]
                                                                            }}
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="flex items-center"
                                                                    >
                                                                        <button
                                                                            type="submit"
                                                                        >
                                                                            <ArrowPathIcon
                                                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                                            />
                                                                            <span
                                                                                class="sr-only"
                                                                                >Mettre
                                                                                à
                                                                                jour
                                                                                la
                                                                                valeur</span
                                                                            >
                                                                        </button>
                                                                    </div>
                                                                    <button
                                                                        type="button"
                                                                        class="inline-flex items-center"
                                                                        @click="
                                                                            removeSousValeur(
                                                                                valeur
                                                                            )
                                                                        "
                                                                    >
                                                                        <TrashIcon
                                                                            class="h-5 w-5 text-red-500 hover:text-red-700"
                                                                        />
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                        <ul
                                                            v-if="
                                                                showAddSousValeurForm(
                                                                    souscritere
                                                                )
                                                            "
                                                            class="ml-4 list-inside list-disc marker:text-indigo-600"
                                                        >
                                                            <li
                                                                class="text-sm text-slate-600"
                                                            >
                                                                <form
                                                                    class="inline-flex flex-grow items-center justify-between"
                                                                    @submit.prevent="
                                                                        addSousValeur(
                                                                            souscritere
                                                                        )
                                                                    "
                                                                >
                                                                    <div>
                                                                        <label
                                                                            for="newValeur"
                                                                        ></label>
                                                                        <div
                                                                            class="mt-1 flex rounded-md"
                                                                        >
                                                                            <input
                                                                                v-model="
                                                                                    addSousValeurForm.valeur
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
                                                                            toggleAddSousValeurForm(
                                                                                souscritere
                                                                            )
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
                                                                            addSousValeurForm
                                                                                .errors
                                                                                .valeur
                                                                        "
                                                                        class="text-xs text-red-500"
                                                                    >
                                                                        {{
                                                                            addSousValeurForm
                                                                                .errors
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
                                                                class="inline-flex items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                                v-if="
                                                                    !showAddSousValeurForm(
                                                                        souscritere
                                                                    ) &&
                                                                    (souscritere.type_champ_form ===
                                                                        'select' ||
                                                                        souscritere.type_champ_form ===
                                                                            'checkbox')
                                                                "
                                                                type="button"
                                                                @click="
                                                                    toggleAddSousValeurForm(
                                                                        souscritere
                                                                    )
                                                                "
                                                            >
                                                                <div>
                                                                    Ajouter une
                                                                    valeur à
                                                                    <span
                                                                        class="font-semibold"
                                                                    >
                                                                        {{
                                                                            souscritere.nom
                                                                        }}</span
                                                                    >
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form
                                                v-if="
                                                    showAddSousCritereForm(
                                                        valeur
                                                    ) &&
                                                    valeur.sous_criteres
                                                        .length === 0
                                                "
                                                class="inline-flex w-full flex-grow items-end justify-between py-2"
                                                @submit.prevent="
                                                    addSousCritere(valeur)
                                                "
                                            >
                                                <div
                                                    class="flex w-full flex-grow flex-col"
                                                >
                                                    <label for="newSousCritere"
                                                        >Ajouter un sous
                                                        critère:</label
                                                    >
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                addSousCritereForm.nom
                                                            "
                                                            type="text"
                                                            name="newSousCritere"
                                                            id="newSousCritere"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>

                                                    <Listbox
                                                        v-if="
                                                            addSousCritereForm.nom
                                                        "
                                                        class="w-full flex-grow"
                                                        v-model="
                                                            addSousCritereForm.type_champ
                                                        "
                                                    >
                                                        <div
                                                            class="relative mt-1"
                                                        >
                                                            <ListboxButton
                                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                            >
                                                                <span
                                                                    class="block truncate"
                                                                    >{{
                                                                        addSousCritereForm
                                                                            .type_champ
                                                                            .type
                                                                    }}</span
                                                                >
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
                                                                            type_champ,
                                                                            index
                                                                        ) in type_champs"
                                                                        :key="
                                                                            index
                                                                        "
                                                                        :value="
                                                                            type_champ
                                                                        "
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
                                                                                v-if="
                                                                                    selected
                                                                                "
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
                                                    @click="
                                                        toggleAddSousCritereForm(
                                                            valeur
                                                        )
                                                    "
                                                    type="button"
                                                    class="ml-4 inline-flex items-center"
                                                >
                                                    <XCircleIcon
                                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                                    />
                                                </button>
                                            </form>
                                        </template>
                                    </li>
                                </ul>
                                <ul
                                    v-if="showAddValeurForm(critere)"
                                    class="list-inside list-disc marker:text-indigo-600"
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
                                        class="inline-flex items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                        v-if="
                                            !showAddValeurForm(critere) &&
                                            (critere.type_champ_form ===
                                                'select' ||
                                                critere.type_champ_form ===
                                                    'checkbox')
                                        "
                                        type="button"
                                        @click="toggleAddValeurForm(critere)"
                                    >
                                        <div>
                                            Ajouter une valeur à
                                            <span class="font-semibold">{{
                                                critere.nom
                                            }}</span>
                                        </div>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="flex w-full items-center justify-start">
                            <button
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                v-if="!showAddCritereForm(categorie)"
                                type="button"
                                @click="toggleAddCritereForm(categorie)"
                            >
                                <div>
                                    Ajouter un critère à
                                    <span class="font-semibold">{{
                                        categorie.nom_categorie_client
                                    }}</span>
                                </div>
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
                                                        v-for="critere in listeCriteres"
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
