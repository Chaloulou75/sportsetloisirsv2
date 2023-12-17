<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import NavAdminDisCatParametres from "@/Components/Admin/NavAdminDisCatParametres.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    TrashIcon,
    ChevronUpDownIcon,
    ChevronLeftIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/outline";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    categorie: Object,
    listeTarifsTypes: Object,
    user_can: Object,
});

const updateNomTarifTypeFormVisibility = ref([]);
const tarifTypeNameForm = ref({});

const initializeTarifTypeForm = () => {
    const category = props.categorie;
    for (const tarifTypeId in category.tarif_types) {
        const tarifType = category.tarif_types[tarifTypeId];
        tarifTypeNameForm.value[tarifType.id] = useForm({
            nom: ref(tarifType.nom),
        });
    }
};

initializeTarifTypeForm();

const tarifForms = ref({});

const toggleAddTarifTypeForm = (categorie) => {
    tarifForms.value[categorie.id] = !tarifForms.value[categorie.id];
};

const showAddTarifTypeForm = (categorie) => {
    return tarifForms.value[categorie.id] || false;
};

const addTarifTypeForm = useForm({
    type: props.listeTarifsTypes[0],
    nom: null,
    remember: true,
});

watch(
    () => addTarifTypeForm.type,
    (newValue) => {
        addTarifTypeForm.nom = newValue.type;
    }
);

const addTarifType = (categorie) => {
    addTarifTypeForm.post(
        route("admin.disciplines.categories.tarifs.store", {
            discipline: props.discipline,
            categorie: props.categorie,
        }),
        {
            errorBag: "addTarifTypeForm",
            preserveScroll: true,
            onSuccess: () => {
                addTarifTypeForm.reset();
                toggleAddTarifTypeForm(categorie);
            },
        }
    );
};

const toggleUpdateNomTarifTypeForm = (tarifTypeId) => {
    const index = updateNomTarifTypeFormVisibility.value.indexOf(tarifTypeId);
    if (index === -1) {
        updateNomTarifTypeFormVisibility.value.push(tarifTypeId);
    } else {
        updateNomTarifTypeFormVisibility.value.splice(index, 1);
    }
};

const isUpdateFormVisible = (tarifTypeId) => {
    return updateNomTarifTypeFormVisibility.value.includes(tarifTypeId);
};

const updateNameTarifType = (tarifType) => {
    router.patch(
        route("admin.disciplines.categories.tarifs.update", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
        }),
        {
            nom: tarifTypeNameForm.value[tarifType.id].nom,
        },
        {
            errorBag: "tarifTypeNameForm",
            preserveScroll: true,
            onSuccess: () => {
                toggleUpdateNomTarifTypeForm(tarifType.id);
            },
        }
    );
};

const deleteTarifType = (tarifType) => {
    router.delete(
        route("admin.disciplines.categories.tarifs.destroy", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
        }),
        {
            preserveScroll: true,
        }
    );
};

const addAttributFormVisibility = ref([]);
const toggleAddAttributForm = (tarifType) => {
    addAttributFormVisibility.value[tarifType.id] =
        !addAttributFormVisibility.value[tarifType.id];
};
const showAddAttributForm = (tarifType) => {
    return addAttributFormVisibility.value[tarifType.id] || false;
};

const type_champs = [
    { type: "select" },
    { type: "checkbox" },
    { type: "text" },
    { type: "number" },
];

const addAttributForm = useForm({
    nom: null,
    type_champ: type_champs[0],
    remember: true,
});

const updateAttributForm = ref({});
const initializeAttributForm = () => {
    for (const categorieId in props.categories) {
        const categorie = props.categories[categorieId];

        for (const tarifTypeId in categorie.tarif_types) {
            const tarifType = categorie.tarif_types[tarifTypeId];
            for (const attributId in tarifType.tarif_attributs) {
                const attribut = tarifType.tarif_attributs[attributId];
                updateAttributForm.value[attribut.id] = useForm({
                    nom: ref(attribut.nom),
                    remember: true,
                });
            }
        }
    }
};

initializeAttributForm();

const addTarifAttribut = (tarifType) => {
    addAttributForm.post(
        route("admin.disciplines.categories.tarifs.attributs.store", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
        }),
        {
            errorBag: "addAttributForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeAttributForm();
                addAttributForm.reset();
                toggleAddAttributForm(tarifType);
            },
        }
    );
};

const deleteTarifTypeAttribut = (tarifType, attribut) => {
    router.delete(
        route("admin.disciplines.categories.tarifs.attributs.destroy", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
            attribut: attribut,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeAttributForm();
            },
        }
    );
};

const updateAttribut = (tarifType, attribut) => {
    router.patch(
        route("admin.disciplines.categories.tarifs.attributs.update", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
            attribut: attribut,
        }),
        {
            nom: updateAttributForm.value[attribut.id].nom,
        },
        {
            errorBag: "updateAttributForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeAttributForm();
            },
        }
    );
};

const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
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
                    :href="
                        route('admin.disciplines.categories.edit', discipline)
                    "
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Tarifs associées à la catégorie
                    <span class="text-indigo-600">{{
                        categorie.nom_categorie_client
                    }}</span>
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />
        <NavAdminDisciplineCategorie
            :discipline="discipline"
            :categories="categories"
        />
        <NavAdminDisCatParametres
            :discipline="discipline"
            :categorie="categorie"
        />

        <div class="space-y-16 px-2 py-6 md:px-6">
            <div
                class="rounded-md border border-indigo-300 bg-gray-50 px-1 py-6 shadow-lg md:px-3"
            >
                <p
                    class="px-2 text-center text-lg text-slate-600 underline decoration-yellow-400 decoration-4 underline-offset-4"
                >
                    <span class="font-semibold">Types de tarifs </span>pour la
                    catégorie:
                    <span
                        v-if="categorie.nom_categorie_client"
                        class="font-semibold"
                        >{{ categorie.nom_categorie_client }}</span
                    ><span v-else class="font-semibold">{{
                        categorie.categorie.nom
                    }}</span>
                </p>
                <template v-if="categorie.tarif_types.length > 0">
                    <ul
                        ref="toAnimateOne"
                        class="list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                    >
                        <li
                            v-for="tarifType in categorie.tarif_types"
                            :key="tarifType.id"
                            class="flex flex-col space-y-4 border-b-8 border-white py-4 text-base text-slate-600"
                        >
                            <div
                                class="w-full flex-col items-start justify-between"
                            >
                                <div
                                    class="flex w-full flex-col items-start justify-between space-y-3 pb-2 md:flex-row md:space-x-6 md:space-y-0"
                                >
                                    <div
                                        class="underline decoration-blue-500 decoration-2 underline-offset-2"
                                    >
                                        <span class="text-xl font-semibold">
                                            {{ tarifType.nom }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-6">
                                        <button
                                            v-if="
                                                !isUpdateFormVisible(
                                                    tarifType.id
                                                )
                                            "
                                            @click="
                                                toggleUpdateNomTarifTypeForm(
                                                    tarifType.id
                                                )
                                            "
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                            type="button"
                                        >
                                            <div>
                                                Modifier le nom du type de tarif
                                                <span class="font-semibold">{{
                                                    tarifType.nom
                                                }}</span>
                                            </div>
                                        </button>
                                        <form
                                            @submit.prevent="
                                                updateNameTarifType(tarifType)
                                            "
                                            v-if="
                                                isUpdateFormVisible(
                                                    tarifType.id
                                                )
                                            "
                                            class="mt-1 flex flex-col rounded-md"
                                        >
                                            <label
                                                for="nom tarif"
                                                class="block text-sm font-medium text-gray-700"
                                                >Modifier le nom du type de
                                                tarif
                                                <span class="font-semibold">{{
                                                    tarifType.nom
                                                }}</span>
                                                ?</label
                                            >
                                            <div
                                                class="flex"
                                                v-if="
                                                    tarifTypeNameForm &&
                                                    tarifTypeNameForm[
                                                        tarifType.id
                                                    ]
                                                "
                                            >
                                                <input
                                                    v-model="
                                                        tarifTypeNameForm[
                                                            tarifType.id
                                                        ].nom
                                                    "
                                                    type="text"
                                                    name="nom_type_tarif"
                                                    id="nom_type_tarif"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />

                                                <button
                                                    :disabled="
                                                        tarifTypeNameForm[
                                                            tarifType.id
                                                        ].processing
                                                    "
                                                    type="submit"
                                                    class="ml-4 inline-flex items-center"
                                                >
                                                    <ArrowPathIcon
                                                        class="h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                    />
                                                </button>
                                                <button
                                                    @click="
                                                        toggleUpdateNomTarifTypeForm(
                                                            tarifType.id
                                                        )
                                                    "
                                                    type="button"
                                                    class="ml-4 inline-flex items-center"
                                                >
                                                    <XCircleIcon
                                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                                    />
                                                </button>
                                            </div>
                                            <div
                                                v-if="errors.tarifTypeNameForm"
                                                class="text-xs text-red-500"
                                            >
                                                {{
                                                    errors.tarifTypeNameForm.nom
                                                }}
                                            </div>
                                        </form>
                                        <button
                                            type="button"
                                            class="inline-flex items-center"
                                            @click="deleteTarifType(tarifType)"
                                        >
                                            <TrashIcon
                                                class="h-6 w-6 text-red-500 hover:text-red-700"
                                            />
                                        </button>
                                    </div>
                                </div>
                                <!-- liste attributs -->
                                <ul
                                    v-if="tarifType.tarif_attributs.length > 0"
                                    class="ml-6 list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                                >
                                    <li
                                        v-for="attribut in tarifType.tarif_attributs"
                                        :key="attribut.id"
                                        class="space-y-3 text-sm text-slate-600"
                                    >
                                        <form
                                            v-if="attribut"
                                            class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                            @submit.prevent="
                                                updateAttribut(
                                                    tarifType,
                                                    attribut
                                                )
                                            "
                                        >
                                            <div class="flex flex-col">
                                                <input
                                                    v-if="
                                                        updateAttributForm[
                                                            attribut.id
                                                        ]
                                                    "
                                                    v-model="
                                                        updateAttributForm[
                                                            attribut.id
                                                        ].nom
                                                    "
                                                    type="text"
                                                    :name="attribut.nom"
                                                    :id="attribut.nom"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                                <div
                                                    v-if="
                                                        errors.updateAttributForm
                                                    "
                                                    class="mt-1 text-xs text-red-500"
                                                >
                                                    {{
                                                        errors
                                                            .updateAttributForm
                                                            .nom
                                                    }}
                                                </div>
                                            </div>
                                            <div class="text-base">
                                                Type de champ:
                                                <span class="font-semibold">{{
                                                    attribut.type_champ_form
                                                }}</span>
                                            </div>
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <button type="submit">
                                                    <ArrowPathIcon
                                                        class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                    />
                                                    <span class="sr-only"
                                                        >Mettre à jour l'
                                                        attribut</span
                                                    >
                                                </button>
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center"
                                                    @click="
                                                        deleteTarifTypeAttribut(
                                                            tarifType,
                                                            attribut
                                                        )
                                                    "
                                                >
                                                    <TrashIcon
                                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                                    />
                                                </button>
                                                <button
                                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                    type="button"
                                                >
                                                    <div>
                                                        Ajouter un sous attribut
                                                        à
                                                        <span
                                                            class="font-semibold"
                                                            >{{
                                                                attribut.nom
                                                            }}</span
                                                        >
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <!-- Ajout d'attribut -->
                            <div class="my-4">
                                <button
                                    v-if="!showAddAttributForm(tarifType)"
                                    @click="toggleAddAttributForm(tarifType)"
                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                    type="button"
                                >
                                    <div>
                                        Ajouter un attribut à
                                        <span class="font-semibold">{{
                                            tarifType.nom
                                        }}</span>
                                    </div>
                                </button>
                                <form
                                    v-if="showAddAttributForm(tarifType)"
                                    class="ml-6 inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                                    @submit.prevent="
                                        addTarifAttribut(tarifType)
                                    "
                                >
                                    <div class="flex flex-col items-start">
                                        <label for="newAttribut"
                                            >Ajouter un attribut à
                                            <span class="font-semibold">{{
                                                tarifType.nom
                                            }}</span
                                            >:</label
                                        >
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="addAttributForm.nom"
                                                type="text"
                                                name="newAttribut"
                                                id="newAttribut"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="errors.addAttributForm"
                                            class="text-xs text-red-500"
                                        >
                                            {{ errors.addAttributForm.nom }}
                                        </div>
                                        <Listbox
                                            class="w-full flex-grow"
                                            v-model="addAttributForm.type_champ"
                                        >
                                            <div class="relative mt-1">
                                                <ListboxButton
                                                    class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                >
                                                    <span
                                                        class="block truncate"
                                                        >{{
                                                            addAttributForm
                                                                .type_champ.type
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
                                        class="ml-4 inline-flex items-end"
                                    >
                                        <PlusCircleIcon
                                            class="h-6 w-6 text-indigo-500 hover:text-indigo-700"
                                        />
                                    </button>
                                    <button
                                        @click="
                                            toggleAddAttributForm(tarifType)
                                        "
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
                <!-- Ajout Tarif type -->
                <div class="flex w-full items-center justify-start">
                    <button
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        v-if="!showAddTarifTypeForm(categorie)"
                        type="button"
                        @click="toggleAddTarifTypeForm(categorie)"
                    >
                        <div>
                            Ajouter un type de tarif à
                            <span class="font-semibold">{{
                                categorie.nom_categorie_client
                            }}</span>
                        </div>
                    </button>
                    <form
                        v-if="showAddTarifTypeForm(categorie)"
                        class="inline-flex max-w-md flex-grow items-center justify-between"
                        @submit.prevent="addTarifType(categorie)"
                    >
                        <div class="flex w-full flex-grow flex-col space-y-3">
                            <Listbox
                                class="w-full flex-grow"
                                v-model="addTarifTypeForm.type"
                            >
                                <div class="relative mt-1">
                                    <ListboxButton
                                        class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                    >
                                        <span class="block truncate">{{
                                            addTarifTypeForm.type.type
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
                                                v-slot="{ active, selected }"
                                                v-for="tarifsType in listeTarifsTypes"
                                                :key="tarifsType.id"
                                                :value="tarifsType"
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
                                                            tarifsType.type
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

                            <div
                                v-if="addTarifTypeForm.type"
                                class="mt-1 flex flex-col rounded-md"
                            >
                                <label
                                    for="tarif_type_nom"
                                    class="block text-sm font-medium text-gray-700"
                                    >Modifier le nom du type de tarif?</label
                                >
                                <input
                                    v-model="addTarifTypeForm.nom"
                                    type="text"
                                    name="tarif_type_nom"
                                    id="tarif_type_nom"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <div
                                    v-if="errors.addTarifTypeForm"
                                    class="text-xs text-red-500"
                                >
                                    {{ errors.addTarifTypeForm.nom }}
                                </div>
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
                            @click="toggleAddTarifTypeForm(categorie)"
                            type="button"
                            class="ml-4 inline-flex items-center"
                        >
                            <XCircleIcon
                                class="h-6 w-6 text-red-500 hover:text-red-700"
                            />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
