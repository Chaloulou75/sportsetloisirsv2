<script setup>
import { ref, reactive, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    XCircleIcon,
    ChevronUpDownIcon,
    CheckCircleIcon,
    CurrencyEuroIcon,
} from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    categorie: Object,
    tarif: Object,
    allCategories: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
    structureActivites: Object,
    show: Boolean,
});

const uniteDurees = reactive([
    { id: 1, name: "Heures" },
    { id: 2, name: "Jours" },
    { id: 3, name: "Mois" },
    { id: 4, name: "Années" },
]);

const formEditTarif = useForm({
    titre: null,
    description: null,
    tarifType: null,
    attributs: [],
    amount: null,
    disciplines: {},
    categories: {},
    activites: {},
    produits: {},
    uniteDuree: null,
});

const checkAll = ref(false);

watch(
    () => props.tarif,
    (newValue) => {
        if (newValue) {
            formEditTarif.titre = newValue.titre;
            formEditTarif.description = newValue.description;
            formEditTarif.amount = newValue.amount;

            const tarifType = props.tarifTypes.find(
                (type) => type.id === newValue.type_id
            );
            formEditTarif.tarifType = tarifType
                ? tarifType
                : props.tarifTypes[0];

            const uniteDuree = uniteDurees.find(
                (unite) =>
                    unite.name === newValue.structure_tarif_type_infos[0].unite
            );
            formEditTarif.uniteDuree = uniteDuree ? uniteDuree : uniteDurees[0];

            formEditTarif.attributs =
                newValue.structure_tarif_type_infos.reduce((acc, attribut) => {
                    acc[attribut.attribut_id] = attribut.valeur;
                    return acc;
                }, {});

            for (const disciplineId in props.activiteForTarifs) {
                const disciplineData = props.activiteForTarifs[disciplineId];
                for (const categoryId in disciplineData.categories) {
                    const categoryData = disciplineData.categories[categoryId];
                    for (const activity of categoryData.activites) {
                        for (const produit of activity.produits) {
                            for (const tarif of produit.tarifs) {
                                if (tarif.id === newValue.id) {
                                    formEditTarif.produits[produit.id] = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
);

watch(
    () => formEditTarif.disciplines,
    (newValue) => {
        if (newValue) {
            formEditTarif.categories = {};
            formEditTarif.activites = {};
            formEditTarif.produits = {};
            // Set related checkboxes to true based on the discipline selection
            for (const disciplineId in newValue) {
                const discipline = newValue[disciplineId];
                if (discipline) {
                    const disciplineData =
                        props.activiteForTarifs[disciplineId];

                    if (disciplineData) {
                        const categories = disciplineData.categories;

                        for (const categoryId in categories) {
                            formEditTarif.categories[categoryId] = true;
                            const category = categories[categoryId];

                            for (const activity of category.activites) {
                                formEditTarif.activites[activity.id] = true;

                                for (const product of activity.produits) {
                                    formEditTarif.produits[product.id] = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    },
    { deep: true }
);

watch(
    () => formEditTarif.categories,
    (newValue) => {
        if (newValue) {
            formEditTarif.activites = {};
            formEditTarif.produits = {};
            // Set related checkboxes to true based on the categorie selection
            for (const categoryId in newValue) {
                const category = newValue[categoryId];

                if (category) {
                    for (const disciplineId in props.activiteForTarifs) {
                        const disciplineData =
                            props.activiteForTarifs[disciplineId];
                        const categoryData =
                            disciplineData.categories[categoryId];

                        if (categoryData) {
                            const activites = categoryData.activites;

                            for (const activity of activites) {
                                formEditTarif.activites[activity.id] = true;

                                for (const product of activity.produits) {
                                    formEditTarif.produits[product.id] = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    },
    { deep: true }
);

watch(
    () => formEditTarif.activites,
    (newValue) => {
        if (newValue) {
            formEditTarif.produits = {};

            // Set related checkboxes to true based on the activity selection
            for (const disciplineId in props.activiteForTarifs) {
                const disciplineData = props.activiteForTarifs[disciplineId];

                for (const categoryId in disciplineData.categories) {
                    const categoryData = disciplineData.categories[categoryId];

                    for (const activity of categoryData.activites) {
                        if (newValue[activity.id]) {
                            formEditTarif.activites[activity.id] = true;

                            for (const product of activity.produits) {
                                formEditTarif.produits[product.id] = true;
                            }
                        }
                    }
                }
            }
        }
    },
    { deep: true }
);

watch(
    () => checkAll,
    (newValue) => {
        if (newValue) {
            for (const disciplineId in props.activiteForTarifs) {
                formEditTarif.disciplines[disciplineId] = newValue.value;
                for (const categoryId in formEditTarif.categories) {
                    formEditTarif.categories[categoryId] = newValue.value;
                    for (const activityId in formEditTarif.activites) {
                        formEditTarif.activites[activityId] = newValue.value;
                        for (const productId in formEditTarif.produits) {
                            formEditTarif.produits[productId] = newValue.value;
                        }
                    }
                }
            }
        }
    },
    { deep: true }
);

const onSubmitEditTarifForm = () => {
    formEditTarif.put(
        route("structures.tarifs.update", {
            structure: props.structure.slug,
            tarif: props.tarif.id,
        }),
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                emit("close");
            },
        }
    );
};
</script>
<template>
    <div>
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="open = false" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                    />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="min-h-full w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <form
                                    @submit.prevent="onSubmitEditTarifForm()"
                                    autocomplete="off"
                                >
                                    <DialogTitle
                                        as="div"
                                        class="flex w-full items-center justify-between"
                                    >
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-800"
                                        >
                                            Editer ce tarif lié à
                                            <span class="text-blue-700">
                                                {{ structure.name }}</span
                                            >
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon
                                                @click="emit('close')"
                                                class="h-6 w-6 text-gray-600 hover:text-red-600"
                                            />
                                        </button>
                                    </DialogTitle>
                                    <div class="mt-2 w-full">
                                        <div class="flex flex-col space-y-3">
                                            <div
                                                class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                            >
                                                <!-- titre -->
                                                <div class="w-full md:w-1/2">
                                                    <label
                                                        for="titre"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        Titre
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                formEditTarif.titre
                                                            "
                                                            type="text"
                                                            name="titre"
                                                            id="titre"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <div
                                                        v-if="errors.titre"
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{ errors.titre }}
                                                    </div>
                                                </div>
                                                <Listbox
                                                    class="w-full md:w-1/2"
                                                    v-model="
                                                        formEditTarif.tarifType
                                                    "
                                                >
                                                    <div class="relative mt-1">
                                                        <label
                                                            for="tarifType"
                                                            class="block text-sm font-medium normal-case text-gray-700"
                                                        >
                                                            Type de tarif
                                                        </label>
                                                        <ListboxButton
                                                            class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                        >
                                                            <span
                                                                class="block truncate"
                                                            >
                                                                {{
                                                                    formEditTarif
                                                                        .tarifType
                                                                        .type
                                                                }}
                                                            </span>
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
                                                                    v-for="tarifType in props.tarifTypes"
                                                                    :key="
                                                                        tarifType.id
                                                                    "
                                                                    :value="
                                                                        tarifType
                                                                    "
                                                                    as="template"
                                                                >
                                                                    <li
                                                                        :class="[
                                                                            active
                                                                                ? 'bg-amber-100 text-amber-900'
                                                                                : 'text-gray-900',
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
                                                                                tarifType.type
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
                                            <!-- description -->
                                            <div>
                                                <label
                                                    for="description"
                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                >
                                                    Description
                                                </label>
                                                <div class="mt-1">
                                                    <textarea
                                                        v-model="
                                                            formEditTarif.description
                                                        "
                                                        id="description"
                                                        name="description"
                                                        rows="2"
                                                        class="mt-1 block h-32 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        :class="{
                                                            errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                        }"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.description"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.description }}
                                                </div>
                                            </div>
                                            <div
                                                v-if="formEditTarif.tarifType"
                                                class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                            >
                                                <div
                                                    v-for="attribut in formEditTarif
                                                        .tarifType
                                                        .tariftypeattributs"
                                                    :key="attribut.id"
                                                    class="flex w-1/2 items-center space-x-2 md:w-auto"
                                                >
                                                    <div>
                                                        <label
                                                            :for="
                                                                attribut.attribut
                                                            "
                                                            class="block text-sm font-medium normal-case text-gray-700"
                                                        >
                                                            {{
                                                                attribut.attribut
                                                            }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <input
                                                                v-model="
                                                                    formEditTarif
                                                                        .attributs[
                                                                        attribut
                                                                            .id
                                                                    ]
                                                                "
                                                                type="text"
                                                                :name="
                                                                    attribut.attribut
                                                                "
                                                                :id="
                                                                    attribut.attribut
                                                                "
                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                    </div>

                                                    <Listbox
                                                        v-if="
                                                            attribut.attribut ===
                                                            'Duree'
                                                        "
                                                        v-model="
                                                            formEditTarif.uniteDuree
                                                        "
                                                        class="w-56"
                                                    >
                                                        <div class="relative">
                                                            <label
                                                                for="unite de duree"
                                                                class="block text-sm font-medium normal-case text-gray-700"
                                                            >
                                                                Unité de durée
                                                            </label>
                                                            <ListboxButton
                                                                class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                            >
                                                                <span
                                                                    class="block truncate"
                                                                >
                                                                    {{
                                                                        formEditTarif
                                                                            .uniteDuree
                                                                            .name
                                                                    }}
                                                                </span>
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
                                                                        v-for="unite in uniteDurees"
                                                                        :key="
                                                                            unite.id
                                                                        "
                                                                        :value="
                                                                            unite
                                                                        "
                                                                        as="template"
                                                                    >
                                                                        <li
                                                                            :class="[
                                                                                active
                                                                                    ? 'bg-amber-100 text-amber-900'
                                                                                    : 'text-gray-900',
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
                                                                                    unite.name
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
                                            </div>
                                            <!-- Amount -->
                                            <div class="w-full md:w-1/2">
                                                <label
                                                    for="amount"
                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                >
                                                    Montant
                                                </label>
                                                <div
                                                    class="mt-1 flex items-center rounded-md"
                                                >
                                                    <input
                                                        v-model="
                                                            formEditTarif.amount
                                                        "
                                                        type="text"
                                                        name="amount"
                                                        id="amount"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                    <CurrencyEuroIcon
                                                        class="ml-2 h-6 w-6"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.amount"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.amount }}
                                                </div>
                                            </div>
                                            <div
                                                class="flex flex-col space-y-2"
                                            >
                                                <p
                                                    class="text-sm font-medium text-gray-700"
                                                >
                                                    Ce tarif est valable pour:
                                                </p>

                                                <label
                                                    class="flex items-center"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        name="Tout"
                                                        v-model="checkAll"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    />
                                                    <span
                                                        class="ml-2 text-sm text-gray-600"
                                                        >Tout sélectionner</span
                                                    >
                                                </label>

                                                <div
                                                    v-for="discipline in props.activiteForTarifs"
                                                    :key="discipline.id"
                                                    class="ml-2 md:ml-4"
                                                >
                                                    <label
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :id="discipline.id"
                                                            :value="
                                                                discipline.id
                                                            "
                                                            :name="
                                                                discipline.disciplineName
                                                            "
                                                            v-model="
                                                                formEditTarif
                                                                    .disciplines[
                                                                    discipline
                                                                        .id
                                                                ]
                                                            "
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        />

                                                        <span
                                                            class="ml-2 text-sm text-gray-600"
                                                            >{{
                                                                discipline.disciplineName
                                                            }}</span
                                                        >
                                                    </label>
                                                    <div
                                                        v-for="category in discipline.categories"
                                                        :key="category.id"
                                                        class="ml-4 md:ml-8"
                                                    >
                                                        <label
                                                            class="flex items-center"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                :id="
                                                                    category.id
                                                                "
                                                                :value="
                                                                    category.id
                                                                "
                                                                :name="
                                                                    category.name
                                                                "
                                                                v-model="
                                                                    formEditTarif
                                                                        .categories[
                                                                        category
                                                                            .id
                                                                    ]
                                                                "
                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                            />
                                                            <span
                                                                class="ml-2 text-sm text-gray-600"
                                                                >{{
                                                                    category.name
                                                                }}</span
                                                            >
                                                        </label>

                                                        <div
                                                            v-for="activite in category.activites"
                                                            :key="activite.id"
                                                            class="ml-6 md:ml-12"
                                                        >
                                                            <label
                                                                class="flex items-center"
                                                            >
                                                                <input
                                                                    type="checkbox"
                                                                    :id="
                                                                        activite.id
                                                                    "
                                                                    :value="
                                                                        activite.id
                                                                    "
                                                                    :name="
                                                                        activite.titre
                                                                    "
                                                                    v-model="
                                                                        formEditTarif
                                                                            .activites[
                                                                            activite
                                                                                .id
                                                                        ]
                                                                    "
                                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                />
                                                                <span
                                                                    class="ml-2 text-sm text-gray-600"
                                                                    >{{
                                                                        activite.titre
                                                                    }}</span
                                                                >
                                                            </label>

                                                            <div
                                                                class="ml-8 flex flex-col items-center space-x-0 space-y-3 md:ml-16 md:flex-row md:space-x-8 md:space-y-0"
                                                            >
                                                                <label
                                                                    v-for="produit in activite.produits"
                                                                    :key="
                                                                        produit.id
                                                                    "
                                                                    class="flex items-center"
                                                                >
                                                                    <input
                                                                        type="checkbox"
                                                                        :id="
                                                                            produit.id
                                                                        "
                                                                        :value="
                                                                            produit.id
                                                                        "
                                                                        :name="
                                                                            produit.id
                                                                        "
                                                                        v-model="
                                                                            formEditTarif
                                                                                .produits[
                                                                                produit
                                                                                    .id
                                                                            ]
                                                                        "
                                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                    />
                                                                    <span
                                                                        class="ml-2 text-sm text-gray-600"
                                                                    >
                                                                        Produit
                                                                        n°
                                                                        {{
                                                                            produit.id
                                                                        }}</span
                                                                    >
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="mt-4 flex w-full items-center justify-between"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            :disabled="formEditTarif.processing"
                                            :class="{
                                                'opacity-25':
                                                    formEditTarif.processing,
                                            }"
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                        >
                                            <LoadingSVG
                                                v-if="formEditTarif.processing"
                                            />
                                            Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
