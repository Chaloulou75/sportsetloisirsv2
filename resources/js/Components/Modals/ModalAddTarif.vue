<script setup>
import { ref, watch, computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
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
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const emit = defineEmits(["close", "showDisplay"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    categorie: Object,
    produit: Object,
    show: Boolean,
});

const selectedDiscipline = ref(props.discipline ? props.discipline.id : null);
const filteredCategories = ref([]);
const newSelectedCategorieId = ref(props.categorie ? props.categorie.id : null);
const newCategorie = ref(null);

const addTarifForm = useForm({
    discipline_id: null,
    categorie_id: null,
    tarif_type: null,
    titre: null,
    description: null,
    attributs: {},
    sousattributs: {},
    amount: null,
    checkAll: false,
    disciplines: {},
    categories: {},
    activites: {},
    produits: {},
});

const updateSelectedCheckboxes = (attributId, optionValue, checked) => {
    const selectedAttribut = addTarifForm.attributs[attributId];

    if (checked) {
        if (!Array.isArray(selectedAttribut)) {
            // Use ref() to ensure reactivity when modifying arrays
            addTarifForm.attributs[attributId] = ref([optionValue]);
        } else {
            selectedAttribut.push(optionValue);
        }
    } else {
        if (Array.isArray(selectedAttribut)) {
            const index = selectedAttribut.indexOf(optionValue);
            if (index !== -1) {
                selectedAttribut.splice(index, 1);
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (attributId, optionValue) => {
        return (
            addTarifForm.attributs[attributId] &&
            addTarifForm.attributs[attributId].includes(optionValue)
        );
    };
});

const updateSousAttrSelectedCheckboxes = (
    sousattributId,
    optionValue,
    checked
) => {
    const selectedSousAttribut = addTarifForm.sousattributs[sousattributId];

    if (checked) {
        if (!Array.isArray(selectedSousAttribut)) {
            // Use ref() to ensure reactivity when modifying arrays
            addTarifForm.sousattributs[sousattributId] = ref([optionValue]);
        } else {
            selectedSousAttribut.push(optionValue);
        }
    } else {
        if (Array.isArray(selectedSousAttribut)) {
            const index = selectedSousAttribut.indexOf(optionValue);
            if (index !== -1) {
                selectedSousAttribut.splice(index, 1);
            }
        }
    }
};

const isCheckboxSousAttrSelected = computed(() => {
    return (sousattributId, optionValue) => {
        return (
            addTarifForm.sousattributs[sousattributId] &&
            addTarifForm.sousattributs[sousattributId].includes(optionValue)
        );
    };
});

watch(
    () => addTarifForm.discipline_id,
    (newDisciplineId) => {
        selectedDiscipline.value = props.structure.disciplines.find(
            (discipline) => discipline.id === newDisciplineId
        );
        if (selectedDiscipline.value) {
            filteredCategories.value = selectedDiscipline.value.str_categories;
        } else {
            filteredCategories.value = [];
        }
        if (filteredCategories.value.length > 0) {
            addTarifForm.categorie_id = filteredCategories.value[0].id;
        } else {
            addTarifForm.categorie_id = null;
        }
        addTarifForm.checkAll = false;
        addTarifForm.disciplines = {};
        addTarifForm.categories = {};
        addTarifForm.activites = {};
        addTarifForm.produits = {};
    },
    { deep: true }
);

watch(
    () => addTarifForm.categorie_id,
    (newCategorieId) => {
        if (selectedDiscipline.value) {
            newCategorie.value = selectedDiscipline.value.str_categories.find(
                (categorie) => categorie.id === newCategorieId
            );
        }
        if (newCategorie.value) {
            addTarifForm.tarif_type = newCategorie.value.tarif_types[0];
        } else {
            addTarifForm.tarif_type = null;
        }

        addTarifForm.attributs = {};
        addTarifForm.sousattributs = {};
        addTarifForm.checkAll = false;
        addTarifForm.disciplines = {};
        addTarifForm.categories = {};
        addTarifForm.activites = {};
        addTarifForm.produits = {};
    }
);

watch(
    () => addTarifForm.disciplines,
    (newValue) => {
        if (newValue) {
            addTarifForm.categories = {};
            addTarifForm.activites = {};
            addTarifForm.produits = {};

            for (const disciplineId in newValue) {
                const discipline = newValue[disciplineId];
                if (discipline) {
                    const disciplineData = props.structure.disciplines.find(
                        (item) => item.id === parseInt(disciplineId)
                    );
                    if (disciplineData) {
                        const categories = disciplineData.str_categories;
                        for (const category of categories) {
                            if (category.id === addTarifForm.categorie_id) {
                                addTarifForm.categories[category.id] = true;
                                for (const activity of category.str_activites) {
                                    addTarifForm.activites[activity.id] = true;
                                    for (const product of activity.produits) {
                                        addTarifForm.produits[
                                            product.id
                                        ] = true;
                                    }
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
    () => addTarifForm.categories,
    (newValue) => {
        if (newValue) {
            addTarifForm.activites = {};
            addTarifForm.produits = {};
            for (const categoryId in newValue) {
                const category = newValue[categoryId];
                if (category) {
                    for (const disciplineId in props.structure.disciplines) {
                        const discipline =
                            props.structure.disciplines[disciplineId];
                        const categorie = discipline.str_categories.find(
                            (cat) => cat.id === parseInt(categoryId)
                        );
                        if (categorie) {
                            addTarifForm.categories[categorie.id] = true;

                            const activites = categorie.str_activites;
                            for (const activity of activites) {
                                addTarifForm.activites[activity.id] = true;

                                for (const product of activity.produits) {
                                    addTarifForm.produits[product.id] = true;
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
    () => addTarifForm.activites,
    (newValue) => {
        if (newValue) {
            addTarifForm.produits = {};
            for (const disciplineId in props.structure.disciplines) {
                const discipline = props.structure.disciplines[disciplineId];
                for (const categoryId in discipline.str_categories) {
                    const categorie = discipline.str_categories[categoryId];
                    for (const activity of categorie.str_activites) {
                        if (newValue[activity.id]) {
                            addTarifForm.activites[activity.id] = true;
                            for (const product of activity.produits) {
                                addTarifForm.produits[product.id] = true;
                            }
                        }
                    }
                }
            }
        }
    },
    { deep: true }
);

// checkAll
watch(
    () => addTarifForm.checkAll,
    (newValue) => {
        if (newValue && newValue === true) {
            for (const discipline of props.structure.disciplines) {
                if (discipline) {
                    addTarifForm.disciplines[discipline.id] = true;
                    const categories = discipline.str_categories;
                    for (const category of categories) {
                        if (category.id === addTarifForm.categorie_id) {
                            addTarifForm.categories[category.id] = true;
                            for (const activity of category.str_activites) {
                                addTarifForm.activites[activity.id] = true;
                                for (const product of activity.produits) {
                                    addTarifForm.produits[product.id] = true;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            addTarifForm.disciplines = {};
            addTarifForm.categories = {};
            addTarifForm.activites = {};
            addTarifForm.produits = {};
        }
    },
    { deep: true }
);

watch(
    () => props.produit,
    (newProduit) => {
        if (newProduit) {
            addTarifForm.discipline_id = newProduit.discipline_id;
            addTarifForm.categorie_id = newProduit.categorie_id;
            addTarifForm.produits[newProduit.id] = true;
        }
    }
);

const onSubmit = () => {
    addTarifForm.post(
        route("structures.cat.tarifs.store", {
            structure: props.structure,
        }),
        {
            errorBag: "addTarifForm",
            preserveScroll: true,
            onSuccess: () => {
                addTarifForm.reset();
                emit("close");
                emit("showDisplay");
            },
        }
    );
};

onMounted(() => {
    if (props.discipline) {
        addTarifForm.discipline_id = props.discipline?.id ?? null;
    }
    if (newSelectedCategorieId.value) {
        addTarifForm.categorie_id = newSelectedCategorieId.value;
    }
});
</script>
<template>
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
                            class="min-h-full w-full max-w-6xl transform space-y-10 overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <DialogTitle
                                as="div"
                                class="flex w-full items-center justify-between"
                            >
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-800"
                                >
                                    Ajouter un tarif aux produits
                                    <span
                                        v-if="newCategorie"
                                        class="text-indigo-700"
                                    >
                                        <span class="text-gray-800"
                                            >de la catégorie</span
                                        >
                                        {{
                                            newCategorie.nom_categorie_client
                                        }}</span
                                    ><span
                                        v-else-if="categorie"
                                        class="text-indigo-700"
                                    >
                                        <span class="text-gray-800"
                                            >de la catégorie</span
                                        >
                                        {{
                                            categorie.nom_categorie_client
                                        }} </span
                                    ><span
                                        v-if="discipline"
                                        class="text-indigo-700"
                                    >
                                        <span class="text-gray-800">
                                            pour la discipline</span
                                        >
                                        {{ discipline.name }}</span
                                    >
                                </h3>
                                <button type="button">
                                    <XCircleIcon
                                        @click="emit('close')"
                                        class="h-6 w-6 text-gray-600 hover:text-red-600"
                                    />
                                </button>
                            </DialogTitle>
                            <!-- test tarif with attributs-->
                            <form
                                @submit.prevent="onSubmit()"
                                autocomplete="off"
                            >
                                <div class="flex flex-col space-y-3">
                                    <!-- disciplines -->
                                    <div
                                        v-if="!discipline"
                                        class="flex w-full flex-col items-start justify-start space-y-2"
                                    >
                                        <label
                                            for="discipline"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Discipline
                                        </label>
                                        <div
                                            class="mt-1 flex w-full rounded-md md:w-1/2"
                                        >
                                            <select
                                                name="discipline"
                                                id="discipline"
                                                v-model="
                                                    addTarifForm.discipline_id
                                                "
                                                class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                            >
                                                <option
                                                    v-for="discipline in structure.disciplines"
                                                    :key="discipline.id"
                                                    :value="discipline.id"
                                                >
                                                    {{ discipline.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- categories -->
                                    <div
                                        v-if="
                                            (discipline ||
                                                addTarifForm.discipline_id) &&
                                            structure
                                        "
                                        class="flex w-full flex-col items-start justify-start space-y-2"
                                    >
                                        <label
                                            for="categorie"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Categorie
                                        </label>
                                        <div
                                            class="mt-1 flex w-full rounded-md md:w-1/2"
                                        >
                                            <select
                                                name="categorie"
                                                id="categorie"
                                                v-model="
                                                    addTarifForm.categorie_id
                                                "
                                                class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                            >
                                                <option
                                                    v-for="categorie in filteredCategories"
                                                    :key="categorie.id"
                                                    :value="categorie.id"
                                                >
                                                    {{
                                                        categorie.nom_categorie_pro
                                                    }}
                                                </option>
                                            </select>
                                        </div>
                                        <div
                                            v-if="errors.addTarifForm"
                                            class="mt-2 text-xs text-red-500"
                                        >
                                            {{ errors.addTarifForm.categorie }}
                                        </div>
                                    </div>
                                    <!-- tarif_types -->
                                    <div
                                        class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                    >
                                        <Listbox
                                            v-if="
                                                addTarifForm.categorie_id &&
                                                newCategorie.tarif_types
                                                    .length > 0
                                            "
                                            class="w-full max-w-sm"
                                            v-model="addTarifForm.tarif_type"
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
                                                        >{{
                                                            addTarifForm
                                                                .tarif_type.nom
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
                                                            v-for="tarifType in newCategorie.tarif_types"
                                                            :key="tarifType.id"
                                                            :value="tarifType"
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
                                                                        tarifType.nom
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
                                        <!-- titre -->
                                        <div
                                            v-if="addTarifForm.tarif_type"
                                            class="w-full max-w-sm"
                                        >
                                            <label
                                                for="titre"
                                                class="block text-sm font-medium normal-case text-gray-700"
                                            >
                                                Titre
                                            </label>
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    v-model="addTarifForm.titre"
                                                    type="text"
                                                    name="titre"
                                                    id="titre"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="errors.addTarifForm"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.addTarifForm.titre }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div
                                        v-if="addTarifForm.tarif_type"
                                        class="w-full max-w-3xl"
                                    >
                                        <label
                                            for="description"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Description
                                        </label>
                                        <div class="mt-1">
                                            <textarea
                                                v-model="
                                                    addTarifForm.description
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
                                            v-if="errors.addTarifForm"
                                            class="mt-2 text-xs text-red-500"
                                        >
                                            {{
                                                errors.addTarifForm.description
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            addTarifForm.tarif_type &&
                                            addTarifForm.tarif_type
                                                .tarif_attributs.length > 0
                                        "
                                        class="mx-auto grid w-full grid-cols-1 gap-4 md:grid-cols-3"
                                    >
                                        <div
                                            v-for="attribut in addTarifForm
                                                .tarif_type.tarif_attributs"
                                            :key="attribut.id"
                                            class="col-span-1"
                                        >
                                            <!-- select  -->
                                            <SelectForm
                                                :classes="'block'"
                                                class="w-full max-w-sm"
                                                v-if="
                                                    attribut.type_champ_form ===
                                                    'select'
                                                "
                                                :name="attribut.nom"
                                                v-model="
                                                    addTarifForm.attributs[
                                                        attribut.id
                                                    ]
                                                "
                                                :options="attribut.valeurs"
                                            />

                                            <!-- checkbox -->
                                            <CheckboxForm
                                                class="max-w-sm"
                                                v-if="
                                                    attribut.type_champ_form ===
                                                    'checkbox'
                                                "
                                                :critere="attribut"
                                                :name="attribut.nom"
                                                v-model="
                                                    addTarifForm.attributs[
                                                        attribut.id
                                                    ]
                                                "
                                                :options="attribut.valeurs"
                                                :is-checkbox-selected="
                                                    isCheckboxSelected
                                                "
                                                @update-selected-checkboxes="
                                                    updateSelectedCheckboxes
                                                "
                                            />

                                            <!-- input text -->
                                            <div
                                                class="w-full max-w-sm"
                                                v-if="
                                                    attribut.type_champ_form ===
                                                    'text'
                                                "
                                            >
                                                <label
                                                    :for="attribut.nom"
                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                >
                                                    {{ attribut.nom }}
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <TextInput
                                                        type="text"
                                                        v-model="
                                                            addTarifForm
                                                                .attributs[
                                                                attribut.id
                                                            ]
                                                        "
                                                        :name="attribut.nom"
                                                        :id="attribut.nom"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                            </div>

                                            <!-- number text -->
                                            <div
                                                class="w-full max-w-sm"
                                                v-if="
                                                    attribut.type_champ_form ===
                                                    'number'
                                                "
                                            >
                                                <label
                                                    :for="attribut.nom"
                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                >
                                                    {{ attribut.nom }}
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <TextInput
                                                        type="number"
                                                        min="0"
                                                        v-model="
                                                            addTarifForm
                                                                .attributs[
                                                                attribut.id
                                                            ]
                                                        "
                                                        :name="attribut.nom"
                                                        :id="attribut.nom"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                            </div>
                                            <!-- sous attributs -->
                                            <template
                                                v-for="sousattribut in attribut.sous_attributs"
                                                :key="sousattribut.id"
                                            >
                                                <SelectForm
                                                    :classes="'block '"
                                                    class="w-full max-w-sm"
                                                    v-if="
                                                        sousattribut.type_champ_form ===
                                                        'select'
                                                    "
                                                    :name="sousattribut.nom"
                                                    v-model="
                                                        addTarifForm
                                                            .sousattributs[
                                                            sousattribut.id
                                                        ]
                                                    "
                                                    :options="
                                                        sousattribut.valeurs
                                                    "
                                                />

                                                <!-- checkbox -->
                                                <CheckboxForm
                                                    class="max-w-sm"
                                                    v-if="
                                                        sousattribut.type_champ_form ===
                                                        'checkbox'
                                                    "
                                                    :critere="sousattribut"
                                                    :name="sousattribut.nom"
                                                    v-model="
                                                        addTarifForm
                                                            .sousattributs[
                                                            sousattribut.id
                                                        ]
                                                    "
                                                    :options="
                                                        sousattribut.valeurs
                                                    "
                                                    :is-checkbox-selected="
                                                        isCheckboxSousAttrSelected
                                                    "
                                                    @update-selected-checkboxes="
                                                        updateSousAttrSelectedCheckboxes
                                                    "
                                                />
                                                <!-- input text -->
                                                <div
                                                    class="w-full max-w-sm"
                                                    v-if="
                                                        sousattribut.type_champ_form ===
                                                        'text'
                                                    "
                                                >
                                                    <label
                                                        :for="sousattribut.nom"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        {{ sousattribut.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="text"
                                                            v-model="
                                                                addTarifForm
                                                                    .sousattributs[
                                                                    sousattribut
                                                                        .id
                                                                ]
                                                            "
                                                            :name="
                                                                sousattribut.nom
                                                            "
                                                            :id="
                                                                sousattribut.nom
                                                            "
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>

                                                <!-- number text -->
                                                <div
                                                    class="w-full min-w-max"
                                                    v-if="
                                                        sousattribut.type_champ_form ===
                                                        'number'
                                                    "
                                                >
                                                    <label
                                                        :for="sousattribut.nom"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        {{ sousattribut.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
                                                            min="0"
                                                            v-model="
                                                                addTarifForm
                                                                    .sousattributs[
                                                                    sousattribut
                                                                        .id
                                                                ]
                                                            "
                                                            :name="
                                                                sousattribut.nom
                                                            "
                                                            :id="
                                                                sousattribut.nom
                                                            "
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div
                                        v-if="addTarifForm.tarif_type"
                                        class="w-full max-w-sm"
                                    >
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
                                                v-model="addTarifForm.amount"
                                                type="number"
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
                                            v-if="errors.addTarifForm"
                                            class="mt-2 text-xs text-red-500"
                                        >
                                            {{ errors.addTarifForm.amount }}
                                        </div>
                                    </div>
                                    <!-- liste des produits -->
                                    <template
                                        v-if="
                                            addTarifForm.categorie_id &&
                                            addTarifForm.tarif_type
                                        "
                                    >
                                        <div class="flex flex-col space-y-2">
                                            <p
                                                class="text-base font-medium text-gray-700"
                                            >
                                                Ce tarif est valable pour:
                                            </p>

                                            <label class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    name="Tout"
                                                    v-model="
                                                        addTarifForm.checkAll
                                                    "
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                />
                                                <span
                                                    class="ml-2 text-sm text-gray-600"
                                                    >Tout sélectionner</span
                                                >
                                            </label>

                                            <div
                                                v-for="discipline in structure.disciplines"
                                                :key="discipline.id"
                                                class="ml-2 md:ml-4"
                                            >
                                                <template
                                                    v-if="
                                                        discipline.id ===
                                                        (addTarifForm.discipline_id ||
                                                            (props.discipline &&
                                                                props.discipline
                                                                    .id))
                                                    "
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
                                                                discipline.name
                                                            "
                                                            v-model="
                                                                addTarifForm
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
                                                                discipline.name
                                                            }}</span
                                                        >
                                                    </label>
                                                    <div
                                                        v-for="category in discipline.str_categories"
                                                        :key="category.id"
                                                        class="ml-4 md:ml-8"
                                                    >
                                                        <template
                                                            v-if="
                                                                category.id ===
                                                                addTarifForm.categorie_id
                                                            "
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
                                                                        category.nom_categorie_pro
                                                                    "
                                                                    v-model="
                                                                        addTarifForm
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
                                                                        category.nom_categorie_pro
                                                                    }}</span
                                                                >
                                                            </label>

                                                            <div
                                                                v-for="activite in category.str_activites"
                                                                :key="
                                                                    activite.id
                                                                "
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
                                                                            addTarifForm
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
                                                                    class="ml-8 flex flex-col items-center space-x-0 space-y-3 md:ml-16 md:flex-row md:flex-wrap md:space-x-8 md:space-y-0"
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
                                                                                addTarifForm
                                                                                    .produits[
                                                                                    produit
                                                                                        .id
                                                                                ]
                                                                            "
                                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                                        />
                                                                        <span
                                                                            class="ml-2 text-sm text-gray-600"
                                                                            >Produit
                                                                            n°
                                                                            {{
                                                                                produit.id
                                                                            }}</span
                                                                        >
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div
                                    class="mt-4 flex w-full items-center justify-between"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click.prevent="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        v-if="addTarifForm.tarif_type"
                                        :disabled="addTarifForm.processing"
                                        :class="{
                                            'opacity-25':
                                                addTarifForm.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="addTarifForm.processing"
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
</template>
