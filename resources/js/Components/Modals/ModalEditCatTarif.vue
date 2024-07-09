<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import RadioForm from "@/Components/Forms/RadioForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { XCircleIcon, CurrencyEuroIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const emit = defineEmits(["close", "showDisplay"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    categorie: Object,
    tarifToUpdate: Object,
    show: Boolean,
});

const selectedDiscipline = ref(null);
const filteredCategories = ref([]);
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

//dis_id
watch(
    () => editCatTarifForm.discipline_id,
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
            editCatTarifForm.categorie_id = props.tarifToUpdate
                ? props.tarifToUpdate.categorie_id
                : filteredCategories.value[0].id;
        } else {
            editCatTarifForm.categorie_id = null;
        }
    }
);

//cat_id
watch(
    () => editCatTarifForm.categorie_id,
    (newCategorieId) => {
        if (selectedDiscipline.value) {
            newCategorie.value = selectedDiscipline.value.str_categories.find(
                (categorie) => categorie.id === newCategorieId
            );
        }
        if (newCategorie.value) {
            editCatTarifForm.tarif_type = props.tarifToUpdate
                ? newCategorie.value.tarif_types.find(
                      (type) =>
                          type.id === props.tarifToUpdate.dis_cat_tar_typ_id
                  )
                : newCategorie.value.tarif_types[0] || null;
        } else {
            editCatTarifForm.tarif_type = null;
        }
    }
);

watch(
    () => editCatTarifForm.disciplines,
    (newValue) => {
        if (newValue) {
            editCatTarifForm.categories = {};
            editCatTarifForm.activites = {};
            editCatTarifForm.produits = {};

            for (const disciplineId in newValue) {
                const discipline = newValue[disciplineId];
                if (discipline) {
                    const disciplineData = props.structure.disciplines.find(
                        (item) => item.id === parseInt(disciplineId)
                    );
                    if (disciplineData) {
                        const categories = disciplineData.str_categories;
                        for (const category of categories) {
                            if (category.id === editCatTarifForm.categorie_id) {
                                editCatTarifForm.categories[category.id] = true;
                                for (const activity of category.str_activites) {
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

watch(
    () => editCatTarifForm.categories,
    (newValue) => {
        if (newValue) {
            editCatTarifForm.activites = {};
            editCatTarifForm.produits = {};
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
                            editCatTarifForm.categories[categorie.id] = true;

                            const activites = categorie.str_activites;
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

watch(
    () => editCatTarifForm.activites,
    (newValue) => {
        if (newValue) {
            editCatTarifForm.produits = {};
            for (const disciplineId in props.structure.disciplines) {
                const discipline = props.structure.disciplines[disciplineId];
                for (const categoryId in discipline.str_categories) {
                    const categorie = discipline.str_categories[categoryId];
                    for (const activity of categorie.str_activites) {
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
            for (const discipline of props.structure.disciplines) {
                if (discipline) {
                    editCatTarifForm.disciplines[discipline.id] = true;
                    const categories = discipline.str_categories;
                    for (const category of categories) {
                        if (category.id === editCatTarifForm.categorie_id) {
                            editCatTarifForm.categories[category.id] = true;
                            for (const activity of category.str_activites) {
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
        } else {
            editCatTarifForm.disciplines = {};
            editCatTarifForm.categories = {};
            editCatTarifForm.activites = {};
            editCatTarifForm.produits = {};
        }
    },
    { deep: true }
);

//tarif_to_update
watch(
    () => props.tarifToUpdate,
    (newtarif) => {
        if (newtarif) {
            editCatTarifForm.discipline_id = newtarif.categorie.discipline_id;
            editCatTarifForm.categorie_id = newtarif.categorie_id;

            selectedDiscipline.value = props.structure.disciplines.find(
                (discipline) =>
                    discipline.id === newtarif.categorie.discipline_id
            );
            if (selectedDiscipline.value) {
                newCategorie.value =
                    selectedDiscipline.value.str_categories.find(
                        (categorie) => categorie.id === newtarif.categorie_id
                    );
                if (newCategorie.value) {
                    const foundTarifType = newCategorie.value.tarif_types.find(
                        (type) => type.id === newtarif.dis_cat_tar_typ_id
                    );
                    editCatTarifForm.tarif_type = foundTarifType || null;
                } else {
                    editCatTarifForm.tarif_type = null;
                }
            }

            editCatTarifForm.titre = newtarif.titre;
            editCatTarifForm.description = newtarif.description;
            editCatTarifForm.amount = Number(newtarif.amount);

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
                                                if (
                                                    attribut.tarif_attribut
                                                        .type_champ_form ===
                                                    "checkbox"
                                                ) {
                                                    if (
                                                        !editCatTarifForm
                                                            .attributs[
                                                            tarifAttr.id
                                                        ]
                                                    ) {
                                                        editCatTarifForm.attributs[
                                                            tarifAttr.id
                                                        ] = [];
                                                    }
                                                    editCatTarifForm.attributs[
                                                        tarifAttr.id
                                                    ].push(attrValeur);
                                                } else {
                                                    editCatTarifForm.attributs[
                                                        tarifAttr.id
                                                    ] = attrValeur;
                                                }
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
                                                            if (
                                                                sousAttrTarif
                                                                    .sous_attribut
                                                                    .type_champ_form ===
                                                                "checkbox"
                                                            ) {
                                                                if (
                                                                    !editCatTarifForm
                                                                        .sousattributs[
                                                                        sousAttributOff
                                                                            .id
                                                                    ]
                                                                ) {
                                                                    editCatTarifForm.sousattributs[
                                                                        sousAttributOff.id
                                                                    ] = [];
                                                                }
                                                                editCatTarifForm.sousattributs[
                                                                    sousAttributOff
                                                                        .id
                                                                ].push(
                                                                    sousAttrTarif.sous_attribut_valeur
                                                                );
                                                            } else {
                                                                editCatTarifForm.sousattributs[
                                                                    sousAttributOff.id
                                                                ] =
                                                                    sousAttrTarif.sous_attribut_valeur;
                                                            }
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
                editCatTarifForm.produits = {};
                newtarif.produits.forEach((produit) => {
                    editCatTarifForm.produits[produit.id] = true;
                });
            }
        }
    },
    { deep: true, immediate: true }
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
                emit("showDisplay", "Mes tarifs");
            },
        }
    );
};
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
                                        <span class="text-gray-800"> de</span>
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
                            <!--  attributs-->
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
                                            <Dropdown
                                                v-model="
                                                    editCatTarifForm.discipline_id
                                                "
                                                :options="structure.disciplines"
                                                optionLabel="name"
                                                optionValue="id"
                                                :placeholder="`Selectionner une discipline`"
                                                class="w-full text-sm md:w-[14rem]"
                                                :ptOptions="{
                                                    mergeProps: true,
                                                }"
                                                :pt="{ item: 'text-sm' }"
                                                showClear
                                            />
                                        </div>
                                    </div>
                                    <!-- categories -->
                                    <div
                                        v-if="editCatTarifForm.discipline_id"
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
                                            <Dropdown
                                                v-model="
                                                    editCatTarifForm.categorie_id
                                                "
                                                :options="filteredCategories"
                                                optionLabel="nom_categorie_pro"
                                                optionValue="id"
                                                :placeholder="`Selectionner une catégorie`"
                                                class="w-full text-sm md:w-[14rem]"
                                                :ptOptions="{
                                                    mergeProps: true,
                                                }"
                                                :pt="{ item: 'text-sm' }"
                                                showClear
                                            />
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
                                        v-if="
                                            editCatTarifForm.categorie_id &&
                                            newCategorie?.tarif_types?.length >
                                                0
                                        "
                                        class="flex w-full flex-col items-start justify-start space-y-2"
                                    >
                                        <label
                                            for="tarifType"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Type de tarif:
                                        </label>
                                        <div
                                            class="mt-1 flex w-full rounded-md md:w-1/2"
                                        >
                                            <Dropdown
                                                v-model="
                                                    editCatTarifForm.tarif_type
                                                "
                                                :options="
                                                    newCategorie.tarif_types
                                                "
                                                optionLabel="nom"
                                                :placeholder="`Selectionner un type de tarif`"
                                                class="w-full text-sm md:w-[14rem]"
                                                :ptOptions="{
                                                    mergeProps: true,
                                                }"
                                                :pt="{ item: 'text-sm' }"
                                                showClear
                                            />
                                        </div>
                                        <div
                                            v-if="errors.editCatTarifForm"
                                            class="mt-2 text-xs text-red-500"
                                        >
                                            {{
                                                errors.editCatTarifForm
                                                    .tarif_type
                                            }}
                                        </div>
                                    </div>

                                    <!-- titre -->
                                    <div
                                        v-if="editCatTarifForm.tarif_type"
                                        class="w-full max-w-sm"
                                    >
                                        <label
                                            for="titre"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Titre
                                        </label>
                                        <div class="mt-1 flex rounded-md">
                                            <TextInput
                                                v-model="editCatTarifForm.titre"
                                                type="text"
                                                name="titre"
                                                id="titre"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder="Titre"
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="errors.editCatTarifForm"
                                            class="mt-2 text-xs text-red-500"
                                        >
                                            {{ errors.editCatTarifForm.titre }}
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div
                                        v-if="editCatTarifForm.tarif_type"
                                        class="w-full max-w-3xl"
                                    >
                                        <label
                                            for="description"
                                            class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Description
                                        </label>
                                        <Textarea
                                            id="description"
                                            v-model="
                                                editCatTarifForm.description
                                            "
                                            autoResize
                                            rows="4"
                                            cols="45"
                                        />
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
                                    <div
                                        v-if="
                                            editCatTarifForm.tarif_type &&
                                            editCatTarifForm.tarif_type
                                                .tarif_attributs.length > 0
                                        "
                                        class="mx-auto grid w-full grid-cols-1 gap-4 md:grid-cols-3"
                                    >
                                        <div
                                            v-for="attribut in editCatTarifForm
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
                                                    editCatTarifForm.attributs[
                                                        attribut.id
                                                    ]
                                                "
                                                :options="attribut.valeurs"
                                            />

                                            <!-- radio  -->
                                            <RadioForm
                                                class="w-full max-w-sm"
                                                v-if="
                                                    attribut.type_champ_form ===
                                                    'radio'
                                                "
                                                :name="attribut.nom"
                                                v-model="
                                                    editCatTarifForm.attributs[
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
                                                :name="attribut.nom"
                                                v-model="
                                                    editCatTarifForm.attributs[
                                                        attribut.id
                                                    ]
                                                "
                                                :options="attribut.valeurs"
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
                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                >
                                                    {{ attribut.nom }}
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <InputNumber
                                                        v-model="
                                                            editCatTarifForm
                                                                .attributs[
                                                                attribut.id
                                                            ]
                                                        "
                                                        inputId="integeronly"
                                                        :name="attribut.nom"
                                                        :id="attribut.nom"
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
                                                        editCatTarifForm
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
                                                    class="mt-1 w-full min-w-max"
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
                                                        <InputNumber
                                                            v-model="
                                                                editCatTarifForm
                                                                    .sousattributs[
                                                                    sousattribut
                                                                        .id
                                                                ]
                                                            "
                                                            inputId="integeronly"
                                                            :name="
                                                                sousattribut.nom
                                                            "
                                                            :id="
                                                                sousattribut.nom
                                                            "
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div
                                        v-if="editCatTarifForm.tarif_type"
                                        class="w-full max-w-sm"
                                    >
                                        <label
                                            for="amount"
                                            class="mb-1 block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Montant
                                        </label>
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <InputNumber
                                                v-model="
                                                    editCatTarifForm.amount
                                                "
                                                inputId="minmaxfraction"
                                                :minFractionDigits="2"
                                                name="amount"
                                                id="amount"
                                                placeholder="10"
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
                                            {{ errors.editCatTarifForm.amount }}
                                        </div>
                                    </div>

                                    <!-- liste des produits -->
                                    <template
                                        v-if="
                                            editCatTarifForm.categorie_id &&
                                            editCatTarifForm.tarif_type
                                        "
                                    >
                                        <div class="flex flex-col space-y-2">
                                            <p
                                                class="text-base font-medium text-gray-700"
                                            >
                                                Ce tarif est valable pour:
                                            </p>

                                            <Checkbox
                                                :name="'Tout sélectionner'"
                                                v-model:checked="
                                                    editCatTarifForm.checkAll
                                                "
                                            />

                                            <div
                                                v-for="discipline in structure.disciplines"
                                                :key="discipline.id"
                                                class="ml-2 md:ml-4"
                                            >
                                                <template
                                                    v-if="
                                                        discipline.id ===
                                                        (editCatTarifForm.discipline_id ||
                                                            (props.discipline &&
                                                                props.discipline
                                                                    .id))
                                                    "
                                                >
                                                    <Checkbox
                                                        v-model:checked="
                                                            editCatTarifForm
                                                                .disciplines[
                                                                discipline.id
                                                            ]
                                                        "
                                                        :inputId="
                                                            Number(
                                                                discipline.id
                                                            )
                                                        "
                                                        :name="discipline.name"
                                                        :value="discipline.id"
                                                    />

                                                    <div
                                                        v-for="category in discipline.str_categories"
                                                        :key="category.id"
                                                        class="ml-4 md:ml-8"
                                                    >
                                                        <template
                                                            v-if="
                                                                category.id ===
                                                                editCatTarifForm.categorie_id
                                                            "
                                                        >
                                                            <Checkbox
                                                                v-model:checked="
                                                                    editCatTarifForm
                                                                        .categories[
                                                                        category
                                                                            .id
                                                                    ]
                                                                "
                                                                :inputId="
                                                                    Number(
                                                                        category.id
                                                                    )
                                                                "
                                                                :name="
                                                                    category.nom_categorie_pro
                                                                "
                                                                :value="
                                                                    category.id
                                                                "
                                                            />

                                                            <div
                                                                v-for="activite in category.str_activites"
                                                                :key="
                                                                    activite.id
                                                                "
                                                                class="ml-6 md:ml-12"
                                                            >
                                                                <Checkbox
                                                                    v-model:checked="
                                                                        editCatTarifForm
                                                                            .activites[
                                                                            activite
                                                                                .id
                                                                        ]
                                                                    "
                                                                    :inputId="
                                                                        Number(
                                                                            activite.id
                                                                        )
                                                                    "
                                                                    :name="
                                                                        activite.titre
                                                                    "
                                                                    :value="
                                                                        activite.id
                                                                    "
                                                                />

                                                                <div
                                                                    class="ml-8 flex flex-col items-center gap-4 md:ml-16 md:flex-row md:flex-wrap"
                                                                >
                                                                    <div
                                                                        v-for="produit in activite.produits"
                                                                        :key="
                                                                            produit.id
                                                                        "
                                                                        class="flex items-center"
                                                                    >
                                                                        <Checkbox
                                                                            v-model:checked="
                                                                                editCatTarifForm
                                                                                    .produits[
                                                                                    produit
                                                                                        .id
                                                                                ]
                                                                            "
                                                                            :inputId="
                                                                                Number(
                                                                                    produit.id
                                                                                )
                                                                            "
                                                                            :name="`Produit n° ${produit.id}`"
                                                                            :value="
                                                                                produit.id
                                                                            "
                                                                        />
                                                                    </div>
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
                                        :disabled="editCatTarifForm.processing"
                                        :class="{
                                            'opacity-25':
                                                editCatTarifForm.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="editCatTarifForm.processing"
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
