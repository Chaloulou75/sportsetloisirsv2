<script setup>
import { ref, watch, onMounted } from "vue";
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
    allCategories: Object,
    activiteForTarifs: Object,
    structureActivites: Object,
    show: Boolean,
});

const filteredCategories = ref(props.allCategories ?? null);
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

watch(
    () => addTarifForm.discipline_id,
    (newDisciplineId) => {
        filteredCategories.value = props.allCategories.filter(
            (category) => category.discipline_id === newDisciplineId
        );
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
    }
);

watch(
    () => addTarifForm.categorie_id,
    (newCategorieId) => {
        newCategorie.value = props.allCategories.find(
            (categorie) => categorie.id === newCategorieId
        );
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
                    const disciplineData =
                        props.activiteForTarifs[disciplineId];
                    if (disciplineData) {
                        const categories = disciplineData.categories;
                        for (const categoryId in categories) {
                            const catId = String(categoryId);
                            const formCatId = String(addTarifForm.categorie_id);

                            if (catId === formCatId) {
                                addTarifForm.categories[categoryId] = true;
                                const category = categories[categoryId];
                                for (const activity of category.activites) {
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
                    for (const disciplineId in props.activiteForTarifs) {
                        const disciplineData =
                            props.activiteForTarifs[disciplineId];
                        const categoryData =
                            disciplineData.categories[categoryId];
                        if (categoryData) {
                            const activites = categoryData.activites;

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
            for (const disciplineId in props.activiteForTarifs) {
                const disciplineData = props.activiteForTarifs[disciplineId];
                for (const categoryId in disciplineData.categories) {
                    const categoryData = disciplineData.categories[categoryId];
                    for (const activity of categoryData.activites) {
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
            for (const disciplineId in props.activiteForTarifs) {
                if (props.activiteForTarifs.hasOwnProperty(disciplineId)) {
                    const disId = String(disciplineId);
                    const formDiscId = String(addTarifForm.discipline_id);
                    const propsDisId = String(
                        props.discipline ? props.discipline.id : null
                    );

                    if (
                        disId === formDiscId ||
                        (props.discipline && disId === propsDisId)
                    ) {
                        addTarifForm.disciplines[disciplineId] = newValue;
                        const categories =
                            props.activiteForTarifs[disciplineId].categories;

                        for (const categoryId in categories) {
                            if (categories.hasOwnProperty(categoryId)) {
                                const catId = String(categoryId);
                                const formCatId = String(
                                    addTarifForm.categorie_id
                                );

                                if (catId === formCatId) {
                                    addTarifForm.categories[categoryId] =
                                        newValue;
                                    const activites =
                                        categories[categoryId].activites;

                                    activites.forEach((activity) => {
                                        addTarifForm.activites[activity.id] =
                                            newValue;

                                        activity.produits.forEach((product) => {
                                            addTarifForm.produits[product.id] =
                                                newValue;
                                        });
                                    });
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
    addTarifForm.categorie_id = newSelectedCategorieId.value;
});

// Old Form Part!
// const uniteDurees = reactive([
//     { id: 1, name: "Heures" },
//     { id: 2, name: "Jours" },
//     { id: 3, name: "Mois" },
//     { id: 4, name: "Années" },
// ]);

// const selectedUniteeDuree = ref(uniteDurees[0]);
// const selectedTarifType = ref(props.tarifTypes[0]);
// const checkAll = ref(false);

// const formAddTarif = reactive({
//     structure_id: ref(props.structure.id),
//     titre: ref(null),
//     description: ref(null),
//     tarifType: ref(selectedTarifType.value),
//     attributs: ref([]),
//     amount: ref(null),
//     disciplines: ref({}),
//     categories: ref({}),
//     activites: ref({}),
//     produits: ref({}),
//     uniteDuree: ref(selectedUniteeDuree.value),
// });

// watch(
//     () => props.structureActivites,
//     (newStructureActivites) => {
//         if (newStructureActivites) {
//             for (const activite of props.structureActivites) {
//                 for (const produit of activite.produits) {
//                     formAddTarif.produits[produit.id] = true;
//                 }
//             }
//         }
//     },
//     {
//         immediate: true,
//         deep: true,
//     }
// );

// watch(
//     () => formAddTarif.disciplines,
//     (newValue) => {
//         if (newValue) {
//             formAddTarif.categories = {};
//             formAddTarif.activites = {};
//             formAddTarif.produits = {};
//             // Set related checkboxes to true based on the discipline selection
//             for (const disciplineId in newValue) {
//                 const discipline = newValue[disciplineId];
//                 if (discipline) {
//                     const disciplineData =
//                         props.activiteForTarifs[disciplineId];

//                     if (disciplineData) {
//                         const categories = disciplineData.categories;

//                         for (const categoryId in categories) {
//                             formAddTarif.categories[categoryId] = true;
//                             const category = categories[categoryId];

//                             for (const activity of category.activites) {
//                                 formAddTarif.activites[activity.id] = true;

//                                 for (const product of activity.produits) {
//                                     formAddTarif.produits[product.id] = true;
//                                 }
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     },
//     { deep: true }
// );

// watch(
//     () => formAddTarif.categories,
//     (newValue) => {
//         if (newValue) {
//             formAddTarif.activites = {};
//             formAddTarif.produits = {};
//             // Set related checkboxes to true based on the categorie selection
//             for (const categoryId in newValue) {
//                 const category = newValue[categoryId];

//                 if (category) {
//                     for (const disciplineId in props.activiteForTarifs) {
//                         const disciplineData =
//                             props.activiteForTarifs[disciplineId];
//                         const categoryData =
//                             disciplineData.categories[categoryId];

//                         if (categoryData) {
//                             const activites = categoryData.activites;

//                             for (const activity of activites) {
//                                 formAddTarif.activites[activity.id] = true;

//                                 for (const product of activity.produits) {
//                                     formAddTarif.produits[product.id] = true;
//                                 }
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     },
//     { deep: true }
// );

// watch(
//     () => formAddTarif.activites,
//     (newValue) => {
//         if (newValue) {
//             formAddTarif.produits = {};

//             // Set related checkboxes to true based on the activity selection
//             for (const disciplineId in props.activiteForTarifs) {
//                 const disciplineData = props.activiteForTarifs[disciplineId];

//                 for (const categoryId in disciplineData.categories) {
//                     const categoryData = disciplineData.categories[categoryId];

//                     for (const activity of categoryData.activites) {
//                         if (newValue[activity.id]) {
//                             formAddTarif.activites[activity.id] = true;

//                             for (const product of activity.produits) {
//                                 formAddTarif.produits[product.id] = true;
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     },
//     { deep: true }
// );

// watch(
//     () => checkAll,
//     (newValue) => {
//         if (newValue) {
//             for (const disciplineId in props.activiteForTarifs) {
//                 formAddTarif.disciplines[disciplineId] = newValue.value;
//                 for (const categoryId in formAddTarif.categories) {
//                     formAddTarif.categories[categoryId] = newValue.value;
//                     for (const activityId in formAddTarif.activites) {
//                         formAddTarif.activites[activityId] = newValue.value;
//                         for (const productId in formAddTarif.produits) {
//                             formAddTarif.produits[productId] = newValue.value;
//                         }
//                     }
//                 }
//             }
//         }
//     },
//     { deep: true }
// );

// const onSubmitAddTarifForm = () => {
//     router.post(
//         route("structures.tarifs.store", {
//             structure: props.structure,
//         }),
//         {
//             structure_id: formAddTarif.structure_id,
//             titre: formAddTarif.titre,
//             description: formAddTarif.description,
//             tarifType: selectedTarifType.value.id,
//             attributs: formAddTarif.attributs,
//             amount: formAddTarif.amount,
//             disciplines: formAddTarif.disciplines,
//             categories: formAddTarif.categories,
//             activites: formAddTarif.activites,
//             produits: formAddTarif.produits,
//             uniteDuree: selectedUniteeDuree.value,
//         },
//         {
//             preserveScroll: true,
//             remember: false,
//             onSuccess: () => {
//                 // formAddTarif.reset();
//                 formAddTarif.titre = "";
//                 formAddTarif.attributs = [];
//                 formAddTarif.amount = "";
//                 formAddTarif.tarifType = props.tarifTypes[0];
//                 formAddTarif.uniteDuree = uniteDurees[0];
//                 formAddTarif.disciplines = {};
//                 formAddTarif.categories = {};
//                 formAddTarif.activites = {};
//                 formAddTarif.produits = {};
//                 checkAll.value = false;
//                 emit("close");
//             },
//         }
//     );
// };

// part of the new form
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
                                                    addTarifForm.discipline_id
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
                                        v-if="
                                            (discipline ||
                                                addTarifForm.discipline_id) &&
                                            allCategories
                                        "
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
                                                class="block text-sm font-medium text-gray-700"
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
                                            class="block text-sm font-medium text-gray-700"
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
                                    <template
                                        v-if="
                                            addTarifForm.tarif_type &&
                                            addTarifForm.tarif_type
                                                .tarif_attributs.length > 0
                                        "
                                    >
                                        <div
                                            v-for="attribut in addTarifForm
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
                                                        addTarifForm
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
                                                        :for="sousattribut.nom"
                                                        class="block text-sm font-medium text-gray-700"
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
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ sousattribut.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
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
                                            </div>
                                        </div>
                                    </template>

                                    <div
                                        v-if="addTarifForm.tarif_type"
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
                                                v-for="discipline in props.activiteForTarifs"
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
                                                                discipline.disciplineName
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
                                                                        category.name
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
                            <!-- Tarif form classic -->
                            <!-- <form
                                @submit.prevent="onSubmitAddTarifForm()"
                                autocomplete="off"
                            >
                                <div class="w-full mt-2">
                                    <div class="flex flex-col space-y-3">
                                        <div
                                            class="flex flex-col items-center justify-start w-full space-x-0 space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                        >
                                            <div class="w-full md:w-1/2">
                                                <label
                                                    for="titre"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Titre
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        v-model="
                                                            formAddTarif.titre
                                                        "
                                                        type="text"
                                                        name="titre"
                                                        id="titre"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.titre }}
                                                </div>
                                            </div>
                                            <Listbox
                                                class="w-full md:w-1/2"
                                                v-model="selectedTarifType"
                                            >
                                                <div class="relative mt-1">
                                                    <label
                                                        for="tarifType"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Type de tarif
                                                    </label>
                                                    <ListboxButton
                                                        class="relative w-full py-2 pl-3 pr-10 mt-1 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                    >
                                                        <span
                                                            class="block truncate"
                                                            >{{
                                                                selectedTarifType.type
                                                            }}</span
                                                        >
                                                        <span
                                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                                                        >
                                                            <ChevronUpDownIcon
                                                                class="w-5 h-5 text-gray-400"
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
                                                            class="absolute z-40 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
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
                                                                            class="w-5 h-5"
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
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="
                                                        formAddTarif.description
                                                    "
                                                    id="description"
                                                    name="description"
                                                    rows="2"
                                                    class="block w-full h-32 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                            v-if="selectedTarifType"
                                            class="flex flex-col items-center justify-start w-full space-x-0 space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                        >
                                            <div
                                                v-for="attribut in selectedTarifType.tariftypeattributs"
                                                :key="attribut.id"
                                                class="flex items-center w-1/2 space-x-2 md:w-auto"
                                            >
                                                <div>
                                                    <label
                                                        :for="attribut.attribut"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ attribut.attribut }}
                                                    </label>
                                                    <div
                                                        class="flex mt-1 rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                formAddTarif
                                                                    .attributs[
                                                                    attribut.id
                                                                ]
                                                            "
                                                            type="text"
                                                            :name="
                                                                attribut.attribut
                                                            "
                                                            :id="
                                                                attribut.attribut
                                                            "
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
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
                                                        selectedUniteeDuree
                                                    "
                                                    class="w-56"
                                                >
                                                    <div class="relative">
                                                        <label
                                                            for="unite de duree"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Unité de durée
                                                        </label>
                                                        <ListboxButton
                                                            class="relative w-full py-2 pl-3 pr-10 mt-1 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                                        >
                                                            <span
                                                                class="block truncate"
                                                            >
                                                                {{
                                                                    selectedUniteeDuree.name
                                                                }}
                                                            </span>
                                                            <span
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                                                            >
                                                                <ChevronUpDownIcon
                                                                    class="w-5 h-5 text-gray-400"
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
                                                                class="absolute z-40 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
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
                                                                                class="w-5 h-5"
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
                                        <div class="w-full md:w-1/2">
                                            <label
                                                for="amount"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Montant
                                            </label>
                                            <div
                                                class="flex items-center mt-1 rounded-md"
                                            >
                                                <input
                                                    v-model="
                                                        formAddTarif.amount
                                                    "
                                                    type="number"
                                                    name="amount"
                                                    id="amount"
                                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                                <CurrencyEuroIcon
                                                    class="w-6 h-6 ml-2"
                                                />
                                            </div>
                                            <div
                                                v-if="errors.amount"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.amount }}
                                            </div>
                                        </div>
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
                                                    v-model="checkAll"
                                                    class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
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
                                                        :value="discipline.id"
                                                        :name="
                                                            discipline.disciplineName
                                                        "
                                                        v-model="
                                                            formAddTarif
                                                                .disciplines[
                                                                discipline.id
                                                            ]
                                                        "
                                                        class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
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
                                                            :id="category.id"
                                                            :value="category.id"
                                                            :name="
                                                                category.name
                                                            "
                                                            v-model="
                                                                formAddTarif
                                                                    .categories[
                                                                    category.id
                                                                ]
                                                            "
                                                            class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
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
                                                                    formAddTarif
                                                                        .activites[
                                                                        activite
                                                                            .id
                                                                    ]
                                                                "
                                                                class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                                                            />
                                                            <span
                                                                class="ml-2 text-sm text-gray-600"
                                                                >{{
                                                                    activite.titre
                                                                }}</span
                                                            >
                                                        </label>

                                                        <div
                                                            class="flex flex-col items-center ml-8 space-x-0 space-y-3 md:ml-16 md:flex-row md:space-x-8 md:space-y-0"
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
                                                                        formAddTarif
                                                                            .produits[
                                                                            produit
                                                                                .id
                                                                        ]
                                                                    "
                                                                    class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                                                                />
                                                                <span
                                                                    class="ml-2 text-sm text-gray-600"
                                                                    >Produit n°
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
                                    class="flex items-center justify-between w-full mt-4"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        :disabled="formAddTarif.processing"
                                        type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="formAddTarif.processing"
                                        />
                                        Enregistrer
                                    </button>
                                </div>
                            </form> -->
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
