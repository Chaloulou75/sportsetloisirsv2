<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
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

const emit = defineEmits(["close"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    categorie: Object,
    tarifToUpdate: Object,
    allCategories: Object,
    activiteForTarifs: Object,
    show: Boolean,
});

const filteredCategories = ref(props.allCategories ?? null);
const newCategorie = ref(null);

const editCatTarifForm = useForm({
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

//tarif
watch(
    () => props.tarifToUpdate,
    (newtarif) => {
        editCatTarifForm.produits = {};
        if (newtarif) {
            editCatTarifForm.discipline_id = newtarif.categorie.discipline_id;
            editCatTarifForm.categorie_id = newtarif.categorie_id;
            editCatTarifForm.tarif_type = newtarif.cat_tarif_type;
            editCatTarifForm.titre = newtarif.titre;
            editCatTarifForm.description = newtarif.description;
            editCatTarifForm.amount = newtarif.amount;

            if (
                editCatTarifForm.tarif_type &&
                editCatTarifForm.tarif_type.tarif_attributs.length > 0
            ) {
                editCatTarifForm.tarif_type.tarif_attributs.forEach(
                    (tarifAttr) => {
                        newtarif.attributs.forEach((attribut) => {
                            if (tarifAttr.id === attribut.cat_tar_att_id) {
                                if (tarifAttr.valeurs.length > 0) {
                                    tarifAttr.valeurs.forEach((attrValeur) => {
                                        if (
                                            attribut.dis_cat_tar_att_valeur_id !==
                                            null
                                        ) {
                                            if (
                                                attrValeur.id ===
                                                attribut.dis_cat_tar_att_valeur_id
                                            ) {
                                                editCatTarifForm.attributs[
                                                    tarifAttr.id
                                                ] = attrValeur;
                                            }
                                        }
                                    });
                                } else {
                                    editCatTarifForm.attributs[tarifAttr.id] =
                                        attribut.valeur;
                                }
                                if (tarifAttr.sous_attributs.length > 0) {
                                    tarifAttr.sous_attributs.forEach(
                                        (sousAttributOff) => {
                                            attribut.sous_attributs.forEach(
                                                (sousAttrTarif) => {
                                                    if (
                                                        sousAttributOff.id ===
                                                        sousAttrTarif.sousattribut_id
                                                    ) {
                                                        if (
                                                            sousAttrTarif.sous_attribut_valeur !==
                                                            null
                                                        ) {
                                                            editCatTarifForm.sousattributs[
                                                                sousAttributOff.id
                                                            ] =
                                                                sousAttrTarif.sous_attribut_valeur;
                                                        } else {
                                                            editCatTarifForm.sousattributs[
                                                                sousAttributOff.id
                                                            ] =
                                                                sousAttrTarif.valeur;
                                                        }
                                                    }
                                                }
                                            );
                                        }
                                    );
                                }
                            }
                        });
                    }
                );
            }
            if (newtarif.produits) {
                newtarif.produits.forEach((produit) => {
                    editCatTarifForm.produits[produit.id] = true;
                });
            }
        }
    },
    { deep: true, immediate: true }
);

//dis_id
watch(
    () => editCatTarifForm.discipline_id,
    (newDisciplineId) => {
        filteredCategories.value = props.allCategories.filter(
            (category) => category.discipline_id === newDisciplineId
        );
        if (filteredCategories.value.length > 0) {
            editCatTarifForm.categorie_id = props.tarifToUpdate
                ? props.tarifToUpdate.categorie_id
                : filteredCategories.value[0].id;
        } else {
            editCatTarifForm.categorie_id = null;
        }
        editCatTarifForm.checkAll = false;
        editCatTarifForm.disciplines = {};
        editCatTarifForm.categories = {};
        editCatTarifForm.activites = {};
        // editCatTarifForm.produits = {};
    }
);

//cat_id
watch(
    () => editCatTarifForm.categorie_id,
    (newCategorieId) => {
        newCategorie.value = props.allCategories.find(
            (categorie) => categorie.id === newCategorieId
        );
        if (newCategorie.value) {
            editCatTarifForm.tarif_type = props.tarifToUpdate
                ? props.tarifToUpdate.cat_tarif_type
                : newCategorie.value.tarif_types[0];
        } else {
            editCatTarifForm.tarif_type = null;
        }
        editCatTarifForm.checkAll = false;
        editCatTarifForm.disciplines = {};
        editCatTarifForm.categories = {};
        editCatTarifForm.activites = {};
        // editCatTarifForm.produits = {};
    }
);

//disciplines
watch(
    () => editCatTarifForm.disciplines,
    (newDisciplines) => {
        if (newDisciplines) {
            editCatTarifForm.categories = {};
            editCatTarifForm.activites = {};
            // editCatTarifForm.produits = {};
            for (const disciplineId in newDisciplines) {
                const discipline = newDisciplines[disciplineId];
                if (discipline) {
                    const disciplineData =
                        props.activiteForTarifs[disciplineId];
                    if (disciplineData) {
                        const categories = disciplineData.categories;
                        for (const categoryId in categories) {
                            const catId = String(categoryId);
                            const formCatId = String(
                                editCatTarifForm.categorie_id
                            );
                            if (catId === formCatId) {
                                editCatTarifForm.categories[categoryId] = true;
                                const category = categories[categoryId];
                                for (const activity of category.activites) {
                                    editCatTarifForm.activites[
                                        activity.id
                                    ] = true;
                                    for (const product of activity.produits) {
                                        editCatTarifForm.produits[
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

//categories
watch(
    () => editCatTarifForm.categories,
    (newValue) => {
        if (newValue) {
            editCatTarifForm.activites = {};
            // editCatTarifForm.produits = {};
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
                                editCatTarifForm.activites[activity.id] = true;

                                for (const product of activity.produits) {
                                    editCatTarifForm.produits[
                                        product.id
                                    ] = true;
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

//activites
watch(
    () => editCatTarifForm.activites,
    (newValue) => {
        if (newValue) {
            // editCatTarifForm.produits = {};
            for (const disciplineId in props.activiteForTarifs) {
                const disciplineData = props.activiteForTarifs[disciplineId];
                for (const categoryId in disciplineData.categories) {
                    const categoryData = disciplineData.categories[categoryId];
                    for (const activity of categoryData.activites) {
                        if (newValue[activity.id]) {
                            editCatTarifForm.activites[activity.id] = true;
                            for (const product of activity.produits) {
                                editCatTarifForm.produits[product.id] = true;
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
    () => editCatTarifForm.checkAll,
    (newValue) => {
        if (newValue && newValue === true) {
            for (const disciplineId in props.activiteForTarifs) {
                if (props.activiteForTarifs.hasOwnProperty(disciplineId)) {
                    const disId = String(disciplineId);
                    const formDiscId = String(editCatTarifForm.discipline_id);
                    const propsDisId = String(
                        props.discipline ? props.discipline.id : null
                    );

                    if (
                        disId === formDiscId ||
                        (props.discipline && disId === propsDisId)
                    ) {
                        editCatTarifForm.disciplines[disciplineId] = newValue;
                        const categories =
                            props.activiteForTarifs[disciplineId].categories;

                        for (const categoryId in categories) {
                            if (categories.hasOwnProperty(categoryId)) {
                                const catId = String(categoryId);
                                const formCatId = String(
                                    editCatTarifForm.categorie_id
                                );

                                if (catId === formCatId) {
                                    editCatTarifForm.categories[categoryId] =
                                        newValue;
                                    const activites =
                                        categories[categoryId].activites;

                                    activites.forEach((activity) => {
                                        editCatTarifForm.activites[
                                            activity.id
                                        ] = newValue;

                                        activity.produits.forEach((product) => {
                                            editCatTarifForm.produits[
                                                product.id
                                            ] = newValue;
                                        });
                                    });
                                }
                            }
                        }
                    }
                }
            }
        } else {
            editCatTarifForm.disciplines = {};
            editCatTarifForm.categories = {};
            editCatTarifForm.activites = {};
            editCatTarifForm.produits = {};
        }
    },
    { deep: true }
);

const onSubmit = () => {
    editCatTarifForm.put(
        route("structures.cat.tarifs.update", {
            structure: props.structure,
            tarif: props.tarifToUpdate,
        }),
        {
            errorBag: "editCatTarifForm",
            preserveScroll: true,
            onSuccess: () => {
                editCatTarifForm.reset();
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
                    <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
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
                                        Editer un tarif aux produits
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
                                                de</span
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
                                                class="block text-sm font-medium text-gray-700"
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
                                                        editCatTarifForm.discipline_id
                                                    "
                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                >
                                                    <option
                                                        v-for="discipline in props.activiteForTarifs"
                                                        :key="discipline.id"
                                                        :value="discipline.id"
                                                    >
                                                        {{
                                                            discipline.disciplineName
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- categories -->
                                        <div
                                            v-if="allCategories"
                                            class="flex w-full flex-col items-start justify-start space-y-2"
                                        >
                                            <label
                                                for="categorie"
                                                class="block text-sm font-medium text-gray-700"
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
                                                        editCatTarifForm.categorie_id
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
                                                v-if="errors.editCatTarifForm"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    errors.editCatTarifForm
                                                        .categorie
                                                }}
                                            </div>
                                        </div>
                                        <!-- tarif_types -->
                                        <div
                                            class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                        >
                                            <Listbox
                                                v-if="
                                                    editCatTarifForm.categorie_id &&
                                                    newCategorie.tarif_types
                                                        .length > 0
                                                "
                                                class="w-full max-w-sm"
                                                v-model="
                                                    editCatTarifForm.tarif_type
                                                "
                                            >
                                                <div class="relative mt-1">
                                                    <label
                                                        for="tarifType"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Type de tarif
                                                    </label>
                                                    <ListboxButton
                                                        class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                    >
                                                        <span
                                                            class="block truncate"
                                                            >{{
                                                                editCatTarifForm
                                                                    .tarif_type
                                                                    .nom
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
                                                v-if="
                                                    editCatTarifForm.tarif_type
                                                "
                                                class="w-full max-w-sm"
                                            >
                                                <label
                                                    for="titre"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Titre
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="
                                                            editCatTarifForm.titre
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
                                                    v-if="
                                                        errors.editCatTarifForm
                                                    "
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{
                                                        errors.editCatTarifForm
                                                            .titre
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- description -->
                                        <div
                                            v-if="editCatTarifForm.tarif_type"
                                            class="w-full max-w-3xl"
                                        >
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="
                                                        editCatTarifForm.description
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
                                                v-if="errors.editCatTarifForm"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    errors.editCatTarifForm
                                                        .description
                                                }}
                                            </div>
                                        </div>
                                        <!-- attributs && sous attributs -->
                                        <template
                                            v-if="
                                                editCatTarifForm.tarif_type &&
                                                editCatTarifForm.tarif_type
                                                    .tarif_attributs.length > 0
                                            "
                                        >
                                            <div
                                                v-for="attribut in editCatTarifForm
                                                    .tarif_type.tarif_attributs"
                                                :key="attribut.id"
                                                class="flex w-full flex-col items-center space-y-2 md:flex-row md:space-x-2 md:space-y-0"
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
                                                        editCatTarifForm
                                                            .attributs[
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
                                                        editCatTarifForm
                                                            .attributs[
                                                            attribut.id
                                                        ]
                                                    "
                                                    :options="attribut.valeurs"
                                                />
                                                <!-- :is-checkbox-selected="
                                                            isCheckboxSelected
                                                        "
                                                        @update-selected-checkboxes="
                                                            updateSelectedCheckboxes
                                                        " -->

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
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ attribut.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="text"
                                                            v-model="
                                                                editCatTarifForm
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
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ attribut.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
                                                            v-model="
                                                                editCatTarifForm
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
                                                <div
                                                    v-for="sousattribut in attribut.sous_attributs"
                                                    :key="sousattribut.id"
                                                    class="flex w-full flex-col items-center space-y-2 md:flex-row md:space-x-2 md:space-y-0"
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
                                                            editCatTarifForm
                                                                .sousattributs[
                                                                sousattribut.id
                                                            ]
                                                        "
                                                        :options="
                                                            sousattribut.valeurs
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
                                                            :for="
                                                                sousattribut.nom
                                                            "
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{
                                                                sousattribut.nom
                                                            }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <TextInput
                                                                type="text"
                                                                v-model="
                                                                    editCatTarifForm
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
                                                            :for="
                                                                sousattribut.nom
                                                            "
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{
                                                                sousattribut.nom
                                                            }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <TextInput
                                                                type="number"
                                                                v-model="
                                                                    editCatTarifForm
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
                                                </div>
                                            </div>
                                        </template>

                                        <div
                                            v-if="editCatTarifForm.tarif_type"
                                            class="w-full max-w-sm"
                                        >
                                            <label
                                                for="amount"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Montant
                                            </label>
                                            <div
                                                class="mt-1 flex items-center rounded-md"
                                            >
                                                <input
                                                    v-model="
                                                        editCatTarifForm.amount
                                                    "
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
                                                v-if="errors.editCatTarifForm"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    errors.editCatTarifForm
                                                        .amount
                                                }}
                                            </div>
                                        </div>
                                        <!-- liste des produits -->
                                        <template
                                            v-if="
                                                editCatTarifForm.categorie_id &&
                                                editCatTarifForm.tarif_type
                                            "
                                        >
                                            <div
                                                class="flex flex-col space-y-2"
                                            >
                                                <p
                                                    class="text-base font-medium text-gray-700"
                                                >
                                                    Ce tarif est valable pour:
                                                </p>

                                                <label
                                                    class="flex items-center"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        name="Tout"
                                                        v-model="
                                                            editCatTarifForm.checkAll
                                                        "
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
                                                    <template
                                                        v-if="
                                                            discipline.id ===
                                                            (editCatTarifForm.discipline_id ||
                                                                (props.discipline &&
                                                                    props
                                                                        .discipline
                                                                        .id))
                                                        "
                                                    >
                                                        <label
                                                            class="flex items-center"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                :id="
                                                                    discipline.id
                                                                "
                                                                :value="
                                                                    discipline.id
                                                                "
                                                                :name="
                                                                    discipline.disciplineName
                                                                "
                                                                v-model="
                                                                    editCatTarifForm
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
                                                            <template
                                                                v-if="
                                                                    category.id ===
                                                                    editCatTarifForm.categorie_id
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
                                                                            category.name
                                                                        "
                                                                        v-model="
                                                                            editCatTarifForm
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
                                                                                editCatTarifForm
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
                                                                                    editCatTarifForm
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
                                            v-if="editCatTarifForm.tarif_type"
                                            :disabled="
                                                editCatTarifForm.processing
                                            "
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                        >
                                            <LoadingSVG
                                                v-if="
                                                    editCatTarifForm.processing
                                                "
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
