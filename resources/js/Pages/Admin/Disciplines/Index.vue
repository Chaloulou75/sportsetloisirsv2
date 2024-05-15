<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    ChevronLeftIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const CreateInfoBase = defineAsyncComponent(() =>
    import("@/Components/Disciplines/CreateInfoBase.vue")
);

const props = defineProps({
    listDisciplines: Object,
    errors: Object,
    user_can: Object,
});

const discipline = ref(null);

const displayCreateDisciplineForm = ref(false);

const showCreateDisciplineForm = () => {
    return (displayCreateDisciplineForm.value =
        !displayCreateDisciplineForm.value);
};

const createInfoBaseForm = useForm({
    name: null,
    description: null,
    theme: "light",
    remember: true,
});

const submitCreateInfoBase = () => {
    createInfoBaseForm.post(route("disciplines.create"), {
        preserveScroll: true,
        onSuccess: () => {
            createInfoBaseForm.reset();
            showCreateDisciplineForm();
        },
    });
};

const replicateCategoriesForm = useForm({
    discipline_origin: null,
    discipline_target: null,
});

const replicateDisciplineCategories = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer toutes les catégories?"
    );
    if (isConfirmed) {
        replicateCategoriesForm.post(
            route("admin.disciplines.duplicate_categories"),
            {
                errorBag: "replicateCategoriesForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateCategoriesForm.reset();
                },
            }
        );
    }
};

const replicateAllForm = useForm({
    discipline_origin: null,
    discipline_target: null,
});
const replicateAll = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer toutes les catégories, critères et tarifs associés?"
    );
    if (isConfirmed) {
        replicateAllForm.post(
            route("admin.disciplines.duplicate_all_parameters"),
            {
                errorBag: "replicateAllForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateAllForm.reset();
                },
            }
        );
    }
};

const replicateCatAndCritForm = useForm({
    discipline_origin: null,
    discipline_target: null,
});
const replicateCatAndCriteres = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer toutes les catégories et critères associés?"
    );
    if (isConfirmed) {
        replicateCatAndCritForm.post(
            route("admin.disciplines.duplicate_categories_and_criteres"),
            {
                errorBag: "replicateCatAndCritForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateCatAndCritForm.reset();
                },
            }
        );
    }
};

const replicateCatTarifForm = useForm({
    discipline_origin: null,
    discipline_target: null,
});
const replicateCatTarifs = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer toutes les catégories et tarifs liés?"
    );
    if (isConfirmed) {
        replicateCatTarifForm.post(
            route("admin.disciplines.duplicate_categories_and_tarifs"),
            {
                errorBag: "replicateCatTarifForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateCatTarifForm.reset();
                },
            }
        );
    }
};

const categoriesOfOriginForCatDis = ref([]);
const replicateCatDisForm = useForm({
    discipline_origin: null,
    categorie_origin: null,
    discipline_target: null,
});
//categoriesOfOrigin
watch(
    () => replicateCatDisForm.discipline_origin,
    async (newDisciplineSlug) => {
        if (newDisciplineSlug) {
            axios
                .get("/api/listdisciplinesbyslug/" + newDisciplineSlug)
                .then((response) => {
                    categoriesOfOriginForCatDis.value = response.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        }
    }
);
const replicateCatDis = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer tous les critères liés à cette catégorie?"
    );
    if (isConfirmed) {
        replicateCatDisForm.post(
            route("admin.disciplines.duplicate_criteres_of_categorie"),
            {
                errorBag: "replicateCatDisForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateCatDisForm.reset();
                },
            }
        );
    }
};

const categoriesOfOrigin = ref([]);
const replicateCatDisTarForm = useForm({
    discipline_origin: null,
    categorie_origin: null,
    discipline_target: null,
});

watch(
    () => replicateCatDisTarForm.discipline_origin,
    async (newDisciplineSlug) => {
        if (newDisciplineSlug) {
            axios
                .get("/api/listdisciplinesbyslug/" + newDisciplineSlug)
                .then((response) => {
                    categoriesOfOrigin.value = response.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        }
    }
);
const replicateCatDisTar = () => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir dupliquer tous les tarifs liés à cette catégorie?"
    );
    if (isConfirmed) {
        replicateCatDisTarForm.post(
            route("admin.disciplines.duplicate_tarifs_of_categorie"),
            {
                errorBag: "replicateCatDisTarForm",
                preserveScroll: true,
                preserveState: false,
                onSuccess: () => {
                    replicateCatDisTarForm.reset();
                },
            }
        );
    }
};
</script>
<template>
    <Head
        title="Gestion du contenu"
        description="Administration du site. Gestion du contenu"
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
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion du contenu (disciplines, catégories, critères,
                    tarifs, formulaires de réservation)
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div
                class="flex w-full flex-col items-start justify-between gap-x-4 space-y-8"
            >
                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold md:text-lg">
                        Rechercher une discipline:
                    </h2>
                    <AutocompleteDisciplineNav
                        class="!w-full !max-w-sm"
                        :disciplines="listDisciplines"
                        v-model="discipline"
                    />
                    <template v-if="discipline" class="w-full">
                        <Link
                            :href="
                                route(
                                    'admin.disciplines.informations.edit',
                                    discipline
                                )
                            "
                            class="group inline-flex w-full max-w-sm items-center justify-center rounded border border-gray-300 bg-white px-4 py-3 text-base font-medium text-gray-600 shadow-sm hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2"
                        >
                            <span
                                class="mr-4 inline-flex group-hover:text-white"
                                >Gérer {{ discipline }}</span
                            >
                            <MagnifyingGlassIcon
                                class="h-5 w-5 text-indigo-500 group-hover:text-white"
                            />
                        </Link>
                    </template>

                    <template v-if="!discipline">
                        <div class="w-full items-center justify-center">
                            <button
                                v-if="!displayCreateDisciplineForm"
                                @click="showCreateDisciplineForm"
                                type="button"
                                class="inline-flex w-full max-w-sm justify-center rounded border border-transparent bg-indigo-600 px-4 py-2.5 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Créer une nouvelle discipline
                            </button>
                            <form
                                v-if="displayCreateDisciplineForm"
                                @submit.prevent="submitCreateInfoBase"
                                class="w-full max-w-md space-y-4"
                            >
                                <h2 class="text-lg font-semibold">
                                    Créer une discipline:
                                </h2>
                                <CreateInfoBase
                                    :errors="errors"
                                    v-model:name="createInfoBaseForm.name"
                                    v-model:description="
                                        createInfoBaseForm.description
                                    "
                                    v-model:theme="createInfoBaseForm.theme"
                                />

                                <div class="flex max-w-md space-x-2">
                                    <button
                                        @click="showCreateDisciplineForm"
                                        type="button"
                                        class="inline-flex w-full justify-center rounded border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        :disabled="
                                            createInfoBaseForm.processing
                                        "
                                        :class="{
                                            'opacity-25':
                                                createInfoBaseForm.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex w-full justify-center rounded border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="createInfoBaseForm.processing"
                                        />
                                        Créer la discipline
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>

                <h2
                    class="my-6 w-full text-center text-lg font-semibold uppercase underline-offset-2 md:text-xl"
                >
                    Duplication de paramètres
                </h2>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer
                        <span class="font-medium italic">seulement</span> les
                        <span class="uppercase text-indigo-500"
                            >catégories</span
                        >
                        d'une discipline à une autre:
                        <span
                            class="text-sm uppercase text-indigo-500"
                            v-if="replicateCategoriesForm.discipline_origin"
                        >
                            {{ replicateCategoriesForm.discipline_origin }}
                        </span>
                        <span
                            class="text-sm"
                            v-if="replicateCategoriesForm.discipline_origin"
                        >
                            à
                        </span>
                        <span
                            class="text-sm uppercase text-indigo-500"
                            v-if="replicateCategoriesForm.discipline_target"
                        >
                            {{ replicateCategoriesForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateDisciplineCategories"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCategoriesForm.discipline_origin
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCategoriesForm"
                            >
                                {{
                                    errors.replicateCategoriesForm
                                        .discipline_origin
                                }}
                            </p>
                        </div>

                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCategoriesForm.discipline_target
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCategoriesForm"
                            >
                                {{
                                    errors.replicateCategoriesForm
                                        .discipline_target
                                }}
                            </p>
                        </div>

                        <button
                            :disabled="replicateCategoriesForm.processing"
                            :class="{
                                'opacity-25':
                                    replicateCategoriesForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG
                                v-if="replicateCategoriesForm.processing"
                            />
                            Dupliquer
                        </button>
                    </form>
                </div>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer <span class="italic">tous</span> les
                        paramètres d'une discipline (<span
                            class="uppercase text-indigo-500"
                            >catégories</span
                        >,
                        <span class="uppercase text-indigo-500">critères</span>,
                        <span class="uppercase text-indigo-500">tarifs</span>
                        etc... ) à une autre:
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateAllForm.discipline_origin"
                        >
                            {{ replicateAllForm.discipline_origin }}
                        </span>
                        <span
                            class="text-base"
                            v-if="replicateAllForm.discipline_origin"
                        >
                            à
                        </span>
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateAllForm.discipline_target"
                        >
                            {{ replicateAllForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateAll"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="replicateAllForm.discipline_origin"
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateAllForm"
                            >
                                {{ errors.replicateAllForm.discipline_origin }}
                            </p>
                        </div>

                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="replicateAllForm.discipline_target"
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateAllForm"
                            >
                                {{ errors.replicateAllForm.discipline_target }}
                            </p>
                        </div>
                        <button
                            :disabled="replicateAllForm.processing"
                            :class="{
                                'opacity-25': replicateAllForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG v-if="replicateAllForm.processing" />
                            Dupliquer
                        </button>
                    </form>
                </div>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer <span class="italic">toutes</span> les
                        <span class="uppercase text-indigo-500"
                            >catégories</span
                        >
                        et
                        <span class="uppercase text-indigo-500">critères</span>
                        d'une discipline à une autre:
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateCatAndCritForm.discipline_origin"
                        >
                            {{ replicateCatAndCritForm.discipline_origin }}
                        </span>
                        <span
                            class="text-base"
                            v-if="replicateCatAndCritForm.discipline_origin"
                        >
                            à
                        </span>
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateCatAndCritForm.discipline_target"
                        >
                            {{ replicateCatAndCritForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateCatAndCriteres"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatAndCritForm.discipline_origin
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatAndCritForm"
                            >
                                {{
                                    errors.replicateCatAndCritForm
                                        .discipline_origin
                                }}
                            </p>
                        </div>

                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatAndCritForm.discipline_target
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatAndCritForm"
                            >
                                {{
                                    errors.replicateCatAndCritForm
                                        .discipline_target
                                }}
                            </p>
                        </div>
                        <button
                            :disabled="replicateCatAndCritForm.processing"
                            :class="{
                                'opacity-25':
                                    replicateCatAndCritForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG
                                v-if="replicateCatAndCritForm.processing"
                            />
                            Dupliquer
                        </button>
                    </form>
                </div>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer les
                        <span class="uppercase text-indigo-500">critères</span>
                        d'une
                        <span class="uppercase text-indigo-500">catégorie</span>
                        de discipline à une autre:
                        <span
                            class="uppercase text-indigo-500"
                            v-if="replicateCatDisForm.discipline_origin"
                        >
                            {{ replicateCatDisForm.discipline_origin }}
                        </span>
                        <span v-if="replicateCatDisForm.discipline_origin">
                            à
                        </span>
                        <span
                            class="uppercase text-indigo-500"
                            v-if="replicateCatDisForm.discipline_target"
                        >
                            {{ replicateCatDisForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateCatDis"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="replicateCatDisForm.discipline_origin"
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisForm"
                            >
                                {{
                                    errors.replicateCatDisForm.discipline_origin
                                }}
                            </p>
                        </div>
                        <div
                            v-if="replicateCatDisForm.discipline_origin"
                            class="flex flex-col items-start"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Catégorie originale:</label
                            >
                            <select
                                v-model="replicateCatDisForm.categorie_origin"
                                class="form-select w-full flex-1 rounded-md border border-gray-300 px-2 py-3 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm md:w-auto"
                                v-if="replicateCatDisForm.discipline_origin"
                            >
                                <option value="" disabled selected>
                                    Selectionner une categorie
                                </option>
                                <option
                                    v-for="category in categoriesOfOriginForCatDis"
                                    :value="category.id"
                                >
                                    {{ category.nom }} /
                                    {{ category.pivot.nom_categorie_client }}
                                </option>
                            </select>
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisForm"
                            >
                                {{
                                    errors.replicateCatDisForm.categorie_origin
                                }}
                            </p>
                        </div>

                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="replicateCatDisForm.discipline_target"
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisForm"
                            >
                                {{
                                    errors.replicateCatDisForm.discipline_target
                                }}
                            </p>
                        </div>
                        <button
                            :disabled="replicateCatDisForm.processing"
                            :class="{
                                'opacity-25': replicateCatDisForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG v-if="replicateCatDisForm.processing" />
                            Dupliquer
                        </button>
                    </form>
                </div>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer les
                        <span class="uppercase text-indigo-500">tarifs</span>,
                        ses
                        <span class="uppercase text-indigo-500">attributs</span
                        >, et
                        <span class="uppercase text-indigo-500"
                            >champs de formulaire de réservation</span
                        >
                        etc... d'une discipline à une autre:
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateCatTarifForm.discipline_origin"
                        >
                            {{ replicateCatTarifForm.discipline_origin }}
                        </span>
                        <span
                            class="text-base"
                            v-if="replicateCatTarifForm.discipline_origin"
                        >
                            à
                        </span>
                        <span
                            class="text-base uppercase text-indigo-500"
                            v-if="replicateCatTarifForm.discipline_target"
                        >
                            {{ replicateCatTarifForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateCatTarifs"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatTarifForm.discipline_origin
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatTarifForm"
                            >
                                {{
                                    errors.replicateCatTarifForm
                                        .discipline_origin
                                }}
                            </p>
                        </div>
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatTarifForm.discipline_target
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatTarifForm"
                            >
                                {{
                                    errors.replicateCatTarifForm
                                        .discipline_target
                                }}
                            </p>
                        </div>
                        <button
                            :disabled="replicateCatTarifForm.processing"
                            :class="{
                                'opacity-25': replicateCatTarifForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG
                                v-if="replicateCatTarifForm.processing"
                            />
                            Dupliquer
                        </button>
                    </form>
                </div>

                <div class="w-full space-y-4">
                    <h2 class="text-base font-semibold">
                        Dupliquer les
                        <span class="uppercase text-indigo-500">tarifs</span>
                        d'une
                        <span class="uppercase text-indigo-500">catégorie</span>
                        de discipline à une autre:
                        <span
                            class="uppercase text-indigo-500"
                            v-if="replicateCatDisTarForm.discipline_origin"
                        >
                            {{ replicateCatDisTarForm.discipline_origin }}
                        </span>
                        <span v-if="replicateCatDisTarForm.discipline_origin">
                            à
                        </span>
                        <span
                            class="uppercase text-indigo-500"
                            v-if="replicateCatDisTarForm.discipline_target"
                        >
                            {{ replicateCatDisTarForm.discipline_target }}
                        </span>
                    </h2>
                    <form
                        @submit.prevent="replicateCatDisTar"
                        class="flex w-full flex-col items-center justify-between space-y-5 md:flex-row md:items-end md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline originale:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatDisTarForm.discipline_origin
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisTarForm"
                            >
                                {{
                                    errors.replicateCatDisTarForm
                                        .discipline_origin
                                }}
                            </p>
                        </div>
                        <div
                            v-if="replicateCatDisTarForm.discipline_origin"
                            class="flex flex-col items-start"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Catégorie originale:</label
                            >
                            <select
                                v-model="
                                    replicateCatDisTarForm.categorie_origin
                                "
                                class="form-select w-full flex-1 rounded-md border border-gray-300 px-2 py-3 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                v-if="replicateCatDisTarForm.discipline_origin"
                            >
                                <option value="" disabled selected>
                                    Selectionner une categorie
                                </option>
                                <option
                                    v-for="category in categoriesOfOrigin"
                                    :value="category.id"
                                >
                                    {{ category.nom }} /
                                    {{ category.pivot.nom_categorie_client }}
                                </option>
                            </select>
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisTarForm"
                            >
                                {{
                                    errors.replicateCatDisTarForm
                                        .categorie_origin
                                }}
                            </p>
                        </div>

                        <div class="w-full flex-1">
                            <p class="block text-sm font-medium text-gray-700">
                                Discipline cible:
                            </p>
                            <AutocompleteDisciplineNav
                                class="!w-full"
                                :disciplines="listDisciplines"
                                v-model="
                                    replicateCatDisTarForm.discipline_target
                                "
                            />
                            <p
                                class="mt-1 text-xs text-red-500"
                                v-if="errors.replicateCatDisTarForm"
                            >
                                {{
                                    errors.replicateCatDisTarForm
                                        .discipline_target
                                }}
                            </p>
                        </div>
                        <button
                            :disabled="replicateCatDisTarForm.processing"
                            :class="{
                                'opacity-25': replicateCatDisTarForm.processing,
                            }"
                            type="submit"
                            class="inline-flex w-full max-w-sm justify-center rounded-md border border-transparent bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 md:w-auto"
                        >
                            <LoadingSVG
                                v-if="replicateCatDisTarForm.processing"
                            />
                            Dupliquer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
