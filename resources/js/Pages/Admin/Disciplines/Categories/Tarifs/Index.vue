<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import NavAdminDisCatParametres from "@/Components/Admin/NavAdminDisCatParametres.vue";
import NavAdminDisCatTarifsForm from "@/Components/Admin/NavAdminDisCatTarifsForm.vue";
import Dropdown from "primevue/dropdown";
import autoAnimate from "@formkit/auto-animate";
import {
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    TrashIcon,
    ChevronLeftIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    categorie: Object,
    listeTarifsTypes: Object,
    user_can: Object,
    type_champs: Object,
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
        if (newValue) {
            addTarifTypeForm.nom = newValue.type;
        }
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

const addValeurFormVisibility = ref([]);
const toggleAddValeurForm = (attribut) => {
    addValeurFormVisibility.value[attribut.id] =
        !addValeurFormVisibility.value[attribut.id];
};
const showAddValeurForm = (attribut) => {
    return addValeurFormVisibility.value[attribut.id] || false;
};

const addSousAttributFormVisibility = ref([]);
const toggleAddSousAttributForm = (valeur) => {
    addSousAttributFormVisibility.value[valeur.id] =
        !addSousAttributFormVisibility.value[valeur.id];
};
const showAddSousAttributForm = (valeur) => {
    return addSousAttributFormVisibility.value[valeur.id] || false;
};

const addSousAttributValeurFormVisibility = ref([]);
const toggleAddSousAttributValeurForm = (sousAttribut) => {
    addSousAttributValeurFormVisibility.value[sousAttribut.id] =
        !addSousAttributValeurFormVisibility.value[sousAttribut.id];
};
const showAddSousAttributValeurForm = (sousAttribut) => {
    return addSousAttributValeurFormVisibility.value[sousAttribut.id] || false;
};

const addAttributForm = useForm({
    nom: null,
    type_champ: props.type_champs[0],
    remember: true,
});

const addValeurForm = useForm({
    valeur: null,
    remember: true,
});

const addSousAttributForm = useForm({
    nom: null,
    type_champ: props.type_champs[0],
    remember: true,
});

const addSousAttributValeurForm = useForm({
    valeur: null,
    remember: true,
});

const updateAttributForm = ref({});
const updateValeurForm = ref({});
const updateSousAttributForm = ref({});
const updateSousAttributValeurForm = ref({});
const initializeAttributForm = (categorie) => {
    for (const tarifTypeId in categorie.tarif_types) {
        const tarifType = categorie.tarif_types[tarifTypeId];
        for (const attributId in tarifType.tarif_attributs) {
            const attribut = tarifType.tarif_attributs[attributId];
            updateAttributForm.value[attribut.id] = useForm({
                nom: attribut.nom,
                remember: true,
            });

            for (const valeurId in attribut.valeurs) {
                const valeur = attribut.valeurs[valeurId];
                updateValeurForm.value[valeur.id] = useForm({
                    valeur: valeur.valeur,
                    remember: true,
                });
                for (const sousAttributId in valeur.sous_attributs) {
                    const sousAttribut = valeur.sous_attributs[sousAttributId];
                    updateSousAttributForm.value[sousAttribut.id] = useForm({
                        nom: sousAttribut.nom,
                        remember: true,
                    });
                    for (const ssAttrValeurId in sousAttribut.valeurs) {
                        const ssAttrValeur =
                            sousAttribut.valeurs[ssAttrValeurId];
                        updateSousAttributValeurForm.value[ssAttrValeur.id] =
                            useForm({
                                valeur: ssAttrValeur.valeur,
                                remember: true,
                            });
                    }
                }
            }
        }
    }
};

watch(
    () => props.categorie,
    (categorie) => {
        initializeAttributForm(categorie);
    },
    { immediate: true, deep: true }
);

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
            onSuccess: () => {},
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
            onSuccess: () => {},
        }
    );
};

const addTarifAttributValeur = (tarifType, attribut) => {
    addValeurForm.post(
        route("admin.disciplines.categories.tarifs.attributs.valeurs.store", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
            attribut: attribut,
        }),
        {
            errorBag: "addValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                addValeurForm.reset();
                toggleAddValeurForm(attribut);
            },
        }
    );
};

const updateValeur = (tarifType, attribut, valeur) => {
    router.patch(
        route("admin.disciplines.categories.tarifs.attributs.valeurs.update", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
            attribut: attribut,
            valeur: valeur,
        }),
        {
            valeur: updateValeurForm.value[valeur.id].valeur,
        },
        {
            errorBag: "updateValeurForm",
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const deleteTarifTypeAttrValeur = (tarifType, attribut, valeur) => {
    router.delete(
        route("admin.disciplines.categories.tarifs.attributs.valeurs.destroy", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
            attribut: attribut,
            valeur: valeur,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const addTarifAttributSousAttribut = (tarifType, attribut, valeur) => {
    addSousAttributForm.post(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.store",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
            }
        ),
        {
            errorBag: "addSousAttributForm",
            preserveScroll: true,
            onSuccess: () => {
                addSousAttributForm.reset();
                toggleAddSousAttributForm(valeur);
            },
        }
    );
};

const updateSousAttribut = (tarifType, attribut, valeur, sousAttr) => {
    router.patch(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.update",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
                sousAttribut: sousAttr,
            }
        ),
        {
            nom: updateSousAttributForm.value[sousAttr.id].nom,
        },
        {
            errorBag: "updateSousAttributForm",
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const deleteTarifTypeAttrSousAttribut = (
    tarifType,
    attribut,
    valeur,
    sousAttr
) => {
    router.delete(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.destroy",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
                sousAttribut: sousAttr,
            }
        ),
        {
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const addTarifAttributSousAttributValeur = (
    tarifType,
    attribut,
    valeur,
    sousAttr
) => {
    addSousAttributValeurForm.post(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.valeurs.store",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
                sousAttribut: sousAttr,
            }
        ),
        {
            errorBag: "addSousAttributValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                addSousAttributValeurForm.reset();
                toggleAddSousAttributValeurForm(sousAttr);
            },
        }
    );
};

const updateSousAttributValeur = (
    tarifType,
    attribut,
    valeur,
    sousAttribut,
    ssAttrValeur
) => {
    router.patch(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.valeurs.update",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
                sousAttribut: sousAttribut,
                ssAttrValeur: ssAttrValeur,
            }
        ),
        {
            valeur: updateSousAttributValeurForm.value[ssAttrValeur.id].valeur,
        },
        {
            errorBag: "updateSousAttributValeurForm",
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const deleteTarifTypeAttrSousAttributValeur = (
    tarifType,
    attribut,
    valeur,
    sousAttribut,
    ssAttrValeur
) => {
    router.delete(
        route(
            "admin.disciplines.categories.tarifs.attributs.valeurs.sous_attributs.valeurs.destroy",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: tarifType,
                attribut: attribut,
                valeur: valeur,
                sousAttribut: sousAttribut,
                ssAttrValeur: ssAttrValeur,
            }
        ),
        {
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const toAnimateOne = ref();
onMounted(() => {
    initializeTarifTypeForm();
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head :title="`Administration de la discipline ${discipline.name}`">
        <meta
            head-key="description"
            name="description"
            :content="`Administration de la discipline ${discipline.name}`"
        />
    </Head>
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
                    de
                    <span class="text-indigo-600">{{ discipline.name }}</span>
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
        <NavAdminDisCatTarifsForm
            v-if="categorie.tarif_types.length > 0"
            :discipline="discipline"
            :categorie="categorie"
            :tarif-types="categorie.tarif_types"
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
                                                class="block text-sm font-medium normal-case text-gray-700"
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
                                                updateAttribut(attribut)
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
                                            <div>
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
                                                        >Mettre à jour le
                                                        champ</span
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
                                            </div>
                                        </form>
                                        <!-- liste des valeurs pour attribut-->
                                        <ul
                                            v-if="attribut.valeurs.length > 0"
                                            class="ml-6 list-inside list-disc gap-3 py-2 text-sm text-slate-600 marker:text-indigo-600"
                                        >
                                            <p
                                                class="text-sm underline underline-offset-2"
                                            >
                                                Liste des valeurs pour
                                                <span class="font-semibold">{{
                                                    attribut.nom
                                                }}</span
                                                >:
                                            </p>
                                            <li
                                                v-for="valeur in attribut.valeurs"
                                                :key="valeur.id"
                                                class="space-y-1 text-sm text-slate-600"
                                            >
                                                <form
                                                    v-if="valeur"
                                                    class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                                    @submit.prevent="
                                                        updateValeur(
                                                            tarifType,
                                                            attribut,
                                                            valeur
                                                        )
                                                    "
                                                >
                                                    <div class="flex flex-col">
                                                        <input
                                                            v-if="
                                                                updateValeurForm[
                                                                    valeur.id
                                                                ]
                                                            "
                                                            v-model="
                                                                updateValeurForm[
                                                                    valeur.id
                                                                ].valeur
                                                            "
                                                            type="text"
                                                            :name="
                                                                valeur.valeur
                                                            "
                                                            :id="valeur.valeur"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                        <div
                                                            v-if="
                                                                errors.updateValeurForm
                                                            "
                                                            class="mt-1 text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors
                                                                    .updateValeurForm
                                                                    .valeur
                                                            }}
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex items-center space-x-3"
                                                    >
                                                        <button type="submit">
                                                            <ArrowPathIcon
                                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                            />
                                                            <span
                                                                class="sr-only"
                                                                >Mettre à jour
                                                                la valeur du
                                                                champ</span
                                                            >
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="inline-flex items-center"
                                                            @click.prevent="
                                                                deleteTarifTypeAttrValeur(
                                                                    tarifType,
                                                                    attribut,
                                                                    valeur
                                                                )
                                                            "
                                                        >
                                                            <TrashIcon
                                                                class="h-6 w-6 text-red-500 hover:text-red-700"
                                                            />
                                                        </button>
                                                        <button
                                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-3 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                            type="button"
                                                            @click="
                                                                toggleAddSousAttributForm(
                                                                    valeur
                                                                )
                                                            "
                                                        >
                                                            <div class="">
                                                                Ajouter un sous
                                                                attribut à la
                                                                valeur
                                                                <span
                                                                    class="font-semibold"
                                                                    >{{
                                                                        valeur.valeur
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                                <!-- liste sous attributs -->
                                                <ul
                                                    v-if="
                                                        valeur.sous_attributs
                                                            .length > 0
                                                    "
                                                    class="ml-6 list-inside list-disc space-y-2 py-2 text-sm text-slate-600 marker:text-indigo-600"
                                                >
                                                    <p
                                                        class="underline underline-offset-2"
                                                    >
                                                        Sous attributs pour
                                                        <span
                                                            class="font-semibold"
                                                            >{{
                                                                valeur.valeur
                                                            }}</span
                                                        >:
                                                    </p>
                                                    <li
                                                        v-for="sousAttr in valeur.sous_attributs"
                                                        :key="sousAttr.id"
                                                        class="gap-2 text-sm text-slate-600"
                                                    >
                                                        <form
                                                            v-if="sousAttr"
                                                            class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                                            @submit.prevent="
                                                                updateSousAttribut(
                                                                    tarifType,
                                                                    attribut,
                                                                    valeur,
                                                                    sousAttr
                                                                )
                                                            "
                                                        >
                                                            <div
                                                                class="flex flex-col"
                                                            >
                                                                <input
                                                                    v-if="
                                                                        updateSousAttributForm[
                                                                            sousAttr
                                                                                .id
                                                                        ]
                                                                    "
                                                                    v-model="
                                                                        updateSousAttributForm[
                                                                            sousAttr
                                                                                .id
                                                                        ].nom
                                                                    "
                                                                    type="text"
                                                                    :name="`input-${sousAttr.id}`"
                                                                    :id="`input-${sousAttr.id}`"
                                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                    placeholder=""
                                                                    autocomplete="none"
                                                                />
                                                                <div
                                                                    v-if="
                                                                        errors.updateSousAttributForm &&
                                                                        errors
                                                                            .updateSousAttributForm[
                                                                            sousAttr
                                                                                .id
                                                                        ]
                                                                    "
                                                                    class="mt-1 text-xs text-red-500"
                                                                >
                                                                    {{
                                                                        errors
                                                                            .updateSousAttributForm[
                                                                            sousAttr
                                                                                .id
                                                                        ].nom
                                                                    }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                Type de champ:
                                                                <span
                                                                    class="font-semibold"
                                                                >
                                                                    {{
                                                                        sousAttr.type_champ_form
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="flex items-center space-x-3"
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
                                                                        à jour
                                                                        le sous
                                                                        champ</span
                                                                    >
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex items-center"
                                                                    @click.prevent="
                                                                        deleteTarifTypeAttrSousAttribut(
                                                                            tarifType,
                                                                            attribut,
                                                                            valeur,
                                                                            sousAttr
                                                                        )
                                                                    "
                                                                >
                                                                    <TrashIcon
                                                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                                                    />
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <!-- liste valeurs de sous attributs -->
                                                        <ul
                                                            v-if="
                                                                sousAttr.valeurs
                                                                    .length > 0
                                                            "
                                                            class="ml-6 list-inside list-disc space-y-1 py-2 text-sm text-slate-600 marker:text-indigo-600"
                                                        >
                                                            <p
                                                                class="underline underline-offset-2"
                                                            >
                                                                Liste des
                                                                valeurs pour
                                                                <span
                                                                    class="font-semibold"
                                                                    >{{
                                                                        sousAttr.nom
                                                                    }}</span
                                                                >:
                                                            </p>
                                                            <li
                                                                v-for="sousAttrValeur in sousAttr.valeurs"
                                                                :key="
                                                                    sousAttrValeur.id
                                                                "
                                                                class="space-y-1 text-sm text-slate-600"
                                                            >
                                                                <form
                                                                    v-if="
                                                                        sousAttrValeur
                                                                    "
                                                                    class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                                                    @submit.prevent="
                                                                        updateSousAttributValeur(
                                                                            tarifType,
                                                                            attribut,
                                                                            valeur,
                                                                            sousAttr,
                                                                            sousAttrValeur
                                                                        )
                                                                    "
                                                                >
                                                                    <div
                                                                        class="flex flex-col"
                                                                    >
                                                                        <input
                                                                            v-if="
                                                                                updateSousAttributValeurForm[
                                                                                    sousAttrValeur
                                                                                        .id
                                                                                ]
                                                                            "
                                                                            v-model="
                                                                                updateSousAttributValeurForm[
                                                                                    sousAttrValeur
                                                                                        .id
                                                                                ]
                                                                                    .valeur
                                                                            "
                                                                            type="text"
                                                                            :name="
                                                                                sousAttrValeur.valeur
                                                                            "
                                                                            :id="
                                                                                sousAttrValeur.valeur
                                                                            "
                                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                            placeholder=""
                                                                            autocomplete="none"
                                                                        />
                                                                        <div
                                                                            v-if="
                                                                                errors.updateSousAttributValeurForm
                                                                            "
                                                                            class="mt-1 text-xs text-red-500"
                                                                        >
                                                                            {{
                                                                                errors
                                                                                    .updateSousAttributValeurForm
                                                                                    .valeur
                                                                            }}
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center space-x-3"
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
                                                                                valeur
                                                                                du
                                                                                sous
                                                                                attribut</span
                                                                            >
                                                                        </button>
                                                                        <button
                                                                            type="button"
                                                                            class="inline-flex items-center"
                                                                            @click="
                                                                                deleteTarifTypeAttrSousAttributValeur(
                                                                                    tarifType,
                                                                                    attribut,
                                                                                    valeur,
                                                                                    sousAttr,
                                                                                    sousAttrValeur
                                                                                )
                                                                            "
                                                                        >
                                                                            <TrashIcon
                                                                                class="h-6 w-6 text-red-500 hover:text-red-700"
                                                                            />
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                        <!-- Bouton Ajout valeurs de sous attribut -->
                                                        <div
                                                            v-if="
                                                                !showAddSousAttributValeurForm(
                                                                    sousAttr
                                                                ) &&
                                                                sousAttr.type_champ &&
                                                                sousAttr
                                                                    .type_champ
                                                                    .sous_criterable
                                                            "
                                                            class="py-2"
                                                        >
                                                            <button
                                                                @click.prevent="
                                                                    toggleAddSousAttributValeurForm(
                                                                        sousAttr
                                                                    )
                                                                "
                                                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                                type="button"
                                                            >
                                                                <div>
                                                                    Ajouter une
                                                                    valeur au
                                                                    sous
                                                                    attribut
                                                                    <span
                                                                        class="font-semibold"
                                                                        >{{
                                                                            sousAttr.nom
                                                                        }}</span
                                                                    >
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <!-- Form Ajout valeur de Sous Attribut -->
                                                        <div
                                                            v-if="
                                                                showAddSousAttributValeurForm(
                                                                    sousAttr
                                                                )
                                                            "
                                                        >
                                                            <form
                                                                class="inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                                                                @submit.prevent="
                                                                    addTarifAttributSousAttributValeur(
                                                                        tarifType,
                                                                        attribut,
                                                                        valeur,
                                                                        sousAttr
                                                                    )
                                                                "
                                                            >
                                                                <div
                                                                    class="flex flex-col items-start"
                                                                >
                                                                    <label
                                                                        for="newSousAttributValeur"
                                                                        >Ajouter
                                                                        une
                                                                        valeur à
                                                                        <span
                                                                            class="font-semibold"
                                                                            >{{
                                                                                sousAttr.nom
                                                                            }}</span
                                                                        >:</label
                                                                    >
                                                                    <div
                                                                        class="mt-1 flex rounded-md"
                                                                    >
                                                                        <input
                                                                            v-model="
                                                                                addSousAttributValeurForm.valeur
                                                                            "
                                                                            type="text"
                                                                            name="newSousAttributValeur"
                                                                            id="newSousAttributValeur"
                                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                            placeholder=""
                                                                            autocomplete="none"
                                                                        />
                                                                    </div>
                                                                    <div
                                                                        v-if="
                                                                            errors.addSousAttributValeurForm
                                                                        "
                                                                        class="text-xs text-red-500"
                                                                    >
                                                                        {{
                                                                            errors
                                                                                .addSousAttributValeurForm
                                                                                .valeur
                                                                        }}
                                                                    </div>
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
                                                                    @click.prevent="
                                                                        toggleAddSousAttributValeurForm(
                                                                            sousAttr
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
                                                        </div>
                                                    </li>
                                                </ul>
                                                <!-- Ajout sous attributs à valeur d'attribut-->
                                                <div
                                                    v-if="
                                                        showAddSousAttributForm(
                                                            valeur
                                                        )
                                                    "
                                                >
                                                    <form
                                                        class="my-2 ml-6 inline-flex flex-grow items-end justify-between bg-gray-100 p-1 text-center text-xs font-medium text-gray-600"
                                                        @submit.prevent="
                                                            addTarifAttributSousAttribut(
                                                                tarifType,
                                                                attribut,
                                                                valeur
                                                            )
                                                        "
                                                    >
                                                        <div
                                                            class="flex flex-col items-start space-y-2"
                                                        >
                                                            <label
                                                                for="
                                                                    ssAttrNom
                                                                "
                                                            >
                                                                Ajouter un sous
                                                                attribut à la
                                                                valeur
                                                                <span
                                                                    class="font-semibold"
                                                                    >{{
                                                                        valeur.valeur
                                                                    }}</span
                                                                >:
                                                            </label>
                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <input
                                                                    v-model="
                                                                        addSousAttributForm.nom
                                                                    "
                                                                    type="text"
                                                                    name="
                                                                        ssAttrNom
                                                                    "
                                                                    id="
                                                                        ssAttrNom
                                                                    "
                                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                    placeholder=""
                                                                    autocomplete="none"
                                                                />
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    errors.addSousAttributForm
                                                                "
                                                                class="text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors
                                                                        .addSousAttributForm
                                                                        .nom
                                                                }}
                                                            </div>
                                                            <div>
                                                                <label
                                                                    for="ssAttrChamp"
                                                                    class="block text-left text-xs font-medium normal-case text-gray-700"
                                                                >
                                                                    Type de
                                                                    champ:
                                                                </label>
                                                                <div
                                                                    class="mt-1 text-left"
                                                                >
                                                                    <Dropdown
                                                                        v-model="
                                                                            addSousAttributForm.type_champ
                                                                        "
                                                                        :options="
                                                                            type_champs
                                                                        "
                                                                        optionLabel="type"
                                                                        id="ssAttrChamp"
                                                                        placeholder="Type de champ"
                                                                        class="w-full text-sm md:w-[14rem]"
                                                                        :ptOptions="{
                                                                            mergeProps: true,
                                                                        }"
                                                                        :pt="{
                                                                            item: 'text-sm',
                                                                        }"
                                                                        showClear
                                                                    />
                                                                </div>
                                                            </div>
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
                                                            @click.prevent="
                                                                toggleAddSousAttributForm(
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
                                                </div>
                                            </li>
                                        </ul>

                                        <!-- Ajout valeur attribut -->
                                        <div>
                                            <button
                                                v-if="
                                                    !showAddValeurForm(
                                                        attribut
                                                    ) &&
                                                    attribut.type_champ &&
                                                    attribut.type_champ
                                                        .sous_criterable
                                                "
                                                @click="
                                                    toggleAddValeurForm(
                                                        attribut
                                                    )
                                                "
                                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                type="button"
                                            >
                                                <div>
                                                    Ajouter une valeur à
                                                    <span
                                                        class="font-semibold"
                                                        >{{
                                                            attribut.nom
                                                        }}</span
                                                    >
                                                </div>
                                            </button>
                                            <div
                                                v-if="
                                                    showAddValeurForm(attribut)
                                                "
                                            >
                                                <form
                                                    class="inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                                                    @submit.prevent="
                                                        addTarifAttributValeur(
                                                            tarifType,
                                                            attribut
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex flex-col items-start"
                                                    >
                                                        <label
                                                            for="newAttributValeur"
                                                            >Ajouter une valeur
                                                            à
                                                            <span
                                                                class="font-semibold"
                                                                >{{
                                                                    attribut.nom
                                                                }}</span
                                                            >:</label
                                                        >
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <input
                                                                v-model="
                                                                    addValeurForm.valeur
                                                                "
                                                                type="text"
                                                                name="newAttributValeur"
                                                                id="newAttributValeur"
                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                        <div
                                                            v-if="
                                                                errors.addValeurForm
                                                            "
                                                            class="text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors
                                                                    .addValeurForm
                                                                    .valeur
                                                            }}
                                                        </div>
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
                                                            toggleAddValeurForm(
                                                                attribut
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
                                            </div>
                                        </div>
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
                                    class="ml-6 inline-flex flex-grow items-end justify-between text-xs font-medium text-gray-600"
                                    @submit.prevent="
                                        addTarifAttribut(tarifType)
                                    "
                                >
                                    <div
                                        class="flex flex-col items-start space-y-2"
                                    >
                                        <div>
                                            <label for="newAttribut"
                                                >Ajouter un attribut à
                                                <span class="font-semibold">{{
                                                    tarifType.nom
                                                }}</span
                                                >:</label
                                            >
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    v-model="
                                                        addAttributForm.nom
                                                    "
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
                                        </div>

                                        <div>
                                            <label
                                                for="ssAttrChamp"
                                                class="block text-left text-xs font-medium normal-case text-gray-700"
                                            >
                                                Type de champ:
                                            </label>
                                            <div class="mt-1">
                                                <Dropdown
                                                    v-model="
                                                        addAttributForm.type_champ
                                                    "
                                                    :options="type_champs"
                                                    optionLabel="type"
                                                    id="ssAttrChamp"
                                                    placeholder="Type de champ"
                                                    class="w-full text-sm md:w-[14rem]"
                                                    :ptOptions="{
                                                        mergeProps: true,
                                                    }"
                                                    :pt="{
                                                        item: 'text-sm',
                                                    }"
                                                    showClear
                                                />
                                            </div>
                                        </div>
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
                            <div>
                                <label
                                    for="champ"
                                    class="block text-sm font-medium normal-case text-gray-700"
                                >
                                    Type de tarif:
                                </label>
                                <div class="mt-1">
                                    <Dropdown
                                        v-model="addTarifTypeForm.type"
                                        :options="listeTarifsTypes"
                                        optionLabel="type"
                                        id="champ"
                                        placeholder="Type de tarif"
                                        class="w-full text-sm md:w-[14rem]"
                                        :ptOptions="{
                                            mergeProps: true,
                                        }"
                                        :pt="{
                                            item: 'text-sm',
                                        }"
                                        showClear
                                    />
                                </div>
                            </div>

                            <div
                                v-if="addTarifTypeForm.type"
                                class="mt-1 flex max-w-sm flex-col rounded-md"
                            >
                                <label
                                    for="tarif_type_nom"
                                    class="block text-sm font-medium normal-case text-gray-700"
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
