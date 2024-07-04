<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import NavAdminDisCatParametres from "@/Components/Admin/NavAdminDisCatParametres.vue";
import autoAnimate from "@formkit/auto-animate";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import RangeMultiple from "@/Components/Forms/RangeMultiple.vue";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
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
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    categorie: Object,
    listeCriteres: Object,
    user_can: Object,
    type_champs: Object,
});

const visibleUpdateNomCritereForms = ref([]);
const critereNameForm = ref({});
const critereVisibilityForm = ref({});
const critereUniteForm = ref({});
const souscritereUniteForm = ref({});
const valeurForm = ref({});
const sousvaleurForm = ref({});
const decodeIfJson = (value) => {
    if (typeof value === "string") {
        try {
            const parsed = JSON.parse(value);
            // Check if the parsed value is an array
            if (Array.isArray(parsed)) {
                return parsed;
            }
        } catch (e) {}
    }
    return value;
};

const initializeValeurForm = () => {
    for (const categoryId in props.categories) {
        const category = props.categories[categoryId];

        for (const critereId in category.criteres) {
            const critere = category.criteres[critereId];
            critereVisibilityForm.value[critere.id] = useForm({
                visible_front: ref(!!critere.visible_front),
                visible_back: ref(!!critere.visible_back),
                visible_block: ref(!!critere.visible_block),
                ordre: ref(critere.ordre),
                indexable: ref(!!critere.indexable),
                remember: true,
            });
            critereNameForm.value[critere.id] = useForm({
                nom: ref(critere.nom),
            });
            critereUniteForm.value[critere.id] = useForm({
                unite: ref(critere.unite),
                min: ref(Number(critere.min)),
                max: ref(Number(critere.max)),
            });

            for (const valeurId in critere.valeurs) {
                const valeur = critere.valeurs[valeurId];

                valeurForm.value[valeur.id] = useForm({
                    id: ref(valeur.id),
                    valeur: ref(decodeIfJson(valeur.valeur)),
                    ordre: ref(valeur.ordre),
                    inclus_all: ref(!!valeur.inclus_all),
                    remember: true,
                });

                for (const souscritereId in valeur.sous_criteres) {
                    const souscritere = valeur.sous_criteres[souscritereId];

                    souscritereUniteForm.value[souscritere.id] = useForm({
                        unite: ref(souscritere.unite),
                        min: ref(Number(souscritere.min)),
                        max: ref(Number(souscritere.max)),
                    });

                    for (const sousvaleurId in souscritere.sous_criteres_valeurs) {
                        const sousvaleur =
                            souscritere.sous_criteres_valeurs[sousvaleurId];

                        sousvaleurForm.value[sousvaleur.id] = useForm({
                            id: ref(sousvaleur.id),
                            valeur: ref(decodeIfJson(sousvaleur.valeur)),
                            ordre: ref(sousvaleur.ordre),
                            remember: true,
                        });
                    }
                }
            }
        }
    }
};

initializeValeurForm();

const toggleUpdateNomCritereForm = (critereId) => {
    const index = visibleUpdateNomCritereForms.value.indexOf(critereId);
    if (index === -1) {
        visibleUpdateNomCritereForms.value.push(critereId);
    } else {
        visibleUpdateNomCritereForms.value.splice(index, 1);
    }
};

const isUpdateFormVisible = (critereId) => {
    return visibleUpdateNomCritereForms.value.includes(critereId);
};

const updateNameCritere = (critere) => {
    router.put(
        route("admin-dcc-updatename", {
            critere: critere,
        }),
        {
            nom: critereNameForm.value[critere.id].nom,
        },
        {
            errorBag: "critereNameForm",
            preserveScroll: true,
            onSuccess: () => {
                toggleUpdateNomCritereForm(critere.id);
            },
        }
    );
};

const updateCritereVisibility = (critere) => {
    router.patch(
        route("categories-disciplines-criteres.update", {
            critere: critere,
        }),
        {
            visible_front:
                !!critereVisibilityForm.value[critere.id].visible_front,
            visible_back:
                !!critereVisibilityForm.value[critere.id].visible_back,
            visible_block:
                !!critereVisibilityForm.value[critere.id].visible_block,
            ordre: critereVisibilityForm.value[critere.id].ordre,
            indexable: !!critereVisibilityForm.value[critere.id].indexable,
        },
        {
            preserveScroll: true,
        }
    );
};

const updateUniteCritere = (critere) => {
    router.patch(
        route("categories-disciplines-criteres-nom.unite", {
            critere: critere,
        }),
        {
            unite: critereUniteForm.value[critere.id].unite,
            min: critereUniteForm.value[critere.id].min,
            max: critereUniteForm.value[critere.id].max,
        },
        {
            errorBag: "critereUniteForm",
            preserveScroll: true,
        }
    );
};

const deleteCritere = (disciplineCategorieCritere) => {
    router.delete(
        route("categories-disciplines-criteres.destroy", {
            lienDisciplineCategorieCritere: disciplineCategorieCritere,
        }),
        {
            preserveScroll: true,
            lienDisciplineCategorieCritere: disciplineCategorieCritere,
        }
    );
};

const updateUniteSousCritere = (souscritere) => {
    router.put(
        route("dcc-sous-criteres.update", {
            souscritere: souscritere,
        }),
        {
            unite: souscritereUniteForm.value[souscritere.id].unite,
            min: souscritereUniteForm.value[souscritere.id].min,
            max: souscritereUniteForm.value[souscritere.id].max,
        },
        {
            errorBag: "souscritereUniteForm",
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

const ordreCriteres = computed(() => {
    return props.categorie.criteres.map((critere, index) => index + 1);
});

const ordreValeurs = computed(() => {
    const ordres = {};
    props.categorie.criteres.forEach((critere) => {
        const countValeurs = critere.valeurs.length;
        ordres[critere.id] = Array.from(
            { length: countValeurs },
            (_, index) => index + 1
        );
    });
    return ordres;
});

const ordreSousValeurs = computed(() => {
    const ordres = {};
    props.categorie.criteres.forEach((critere) => {
        critere.valeurs.forEach((valeur) => {
            valeur.sous_criteres.forEach((souscritere) => {
                const countSousValeurs =
                    souscritere.sous_criteres_valeurs.length;
                ordres[souscritere.id] = Array.from(
                    { length: countSousValeurs },
                    (_, index) => index + 1
                );
            });
        });
    });
    return ordres;
});

const updateValeur = (valeur) => {
    router.patch(
        route("categories-disciplines-criteres-valeurs.update", {
            lienDisCatCritValeur: valeur.id,
        }),
        {
            valeur: valeurForm.value[valeur.id].valeur,
            ordre: valeurForm.value[valeur.id].ordre,
            inclus_all: !!valeurForm.value[valeur.id].inclus_all,
            id: valeurForm.value[valeur.id].id,
        },
        {
            errorBag: "valeurForm",
            preserveScroll: true,
        }
    );
};

const valeurFormsVisibility = ref({});

const toggleAddValeurForm = (critere) => {
    valeurFormsVisibility.value[critere.id] =
        !valeurFormsVisibility.value[critere.id];
};

const showAddValeurForm = (critere) => {
    return valeurFormsVisibility.value[critere.id] || false;
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
            errorBag: "addValeurForm",
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
            ordre: sousvaleurForm.value[sousvaleur.id].ordre,
            id: sousvaleurForm.value[sousvaleur.id].id,
        },
        {
            errorBag: "sousvaleurForm",
            preserveScroll: true,
        }
    );
};

const sousValeurFormsVisibility = ref({});

const toggleAddSousValeurForm = (souscritere) => {
    sousValeurFormsVisibility.value[souscritere.id] =
        !sousValeurFormsVisibility.value[souscritere.id];
};

const showAddSousValeurForm = (souscritere) => {
    return sousValeurFormsVisibility.value[souscritere.id] || false;
};

const addSousValeurForm = useForm({
    valeur: ref(null),
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
            errorBag: "addSousValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                addSousValeurForm.reset();
                initializeValeurForm();
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

const addCritereForm = useForm({
    critere: props.listeCriteres[0],
    type_champ: props.type_champs[0],
    nom: null,
    remember: true,
});

watch(
    () => addCritereForm.critere,
    (newValue) => {
        addCritereForm.nom = newValue.nom;
    }
);

const addCritere = (categorie) => {
    router.post(
        route("categories-disciplines-criteres.store"),
        {
            critere: addCritereForm.critere,
            categorie: categorie,
            type_champ: addCritereForm.type_champ,
            nom: addCritereForm.nom,
        },
        {
            errorBag: "addCritereForm",
            preserveScroll: true,
            onSuccess: () => {
                addCritereForm.reset();
                toggleAddCritereForm(categorie);
                initializeValeurForm();
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
    type_champ: ref(props.type_champs[0]),
    remember: true,
});

const addSousCritere = (valeur) => {
    router.post(
        route("dcc-sous-criteres.store", {
            valeur: valeur,
        }),
        {
            nom: addSousCritereForm.nom,
            type_champ: addSousCritereForm.type_champ,
        },
        {
            errorBag: "addSousCritereForm",
            preserveScroll: true,
            onSuccess: () => {
                addSousCritereForm.reset();
                toggleAddSousCritereForm(valeur);
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
                    Critères associés à la catégorie
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

        <div class="space-y-16 px-2 py-6 md:px-6">
            <div
                class="rounded-md border border-indigo-300 bg-gray-50 px-1 py-6 shadow-lg md:px-3"
            >
                <p
                    class="text-center text-lg text-slate-600 underline decoration-yellow-400 decoration-4 underline-offset-4"
                >
                    <span class="font-semibold">Critères associés</span> à la
                    catégorie
                    <span
                        v-if="categorie.nom_categorie_client"
                        class="font-semibold"
                        >{{ categorie.nom_categorie_client }}</span
                    ><span v-else class="font-semibold">{{
                        categorie.categorie.nom
                    }}</span>
                </p>

                <ul
                    ref="toAnimateOne"
                    class="list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                >
                    <li
                        v-for="critere in categorie.criteres"
                        :key="critere.id"
                        class="flex flex-col space-y-4 border-b-8 border-white text-base text-slate-600"
                    >
                        <div
                            class="w-full flex-col items-start justify-between"
                        >
                            <div
                                class="flex w-full items-start justify-between space-x-6 pb-2"
                            >
                                <div
                                    class="underline decoration-blue-500 decoration-2 underline-offset-2"
                                >
                                    <span class="text-xl font-semibold">
                                        {{ critere.nom }}
                                        <span
                                            class="text-sm font-medium italic"
                                        >
                                            Type de champ:
                                            {{ critere.type_champ_form }}</span
                                        >
                                    </span>
                                </div>
                                <!-- Nom et modif du nom du critere -->
                                <div>
                                    <button
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                        v-if="!isUpdateFormVisible(critere.id)"
                                        type="button"
                                        @click="
                                            toggleUpdateNomCritereForm(
                                                critere.id
                                            )
                                        "
                                    >
                                        <div>
                                            Modifier le nom du critère
                                            <span class="font-semibold">{{
                                                critere.nom
                                            }}</span>
                                        </div>
                                    </button>
                                    <form
                                        @submit.prevent="
                                            updateNameCritere(critere)
                                        "
                                        v-if="isUpdateFormVisible(critere.id)"
                                        class="mt-1 flex flex-col rounded-md"
                                    >
                                        <label
                                            for="nom critere"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                            >Modifier le nom du critère
                                            <span class="font-semibold">{{
                                                critere.nom
                                            }}</span>
                                            ?</label
                                        >
                                        <div class="flex">
                                            <input
                                                v-model="
                                                    critereNameForm[critere.id]
                                                        .nom
                                                "
                                                type="text"
                                                name="nom critere"
                                                id="nom critere"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />

                                            <button
                                                :disabled="
                                                    critereNameForm[critere.id]
                                                        .processing
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
                                                    toggleUpdateNomCritereForm(
                                                        critere.id
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
                                            v-if="errors.critereNameForm"
                                            class="text-xs text-red-500"
                                        >
                                            {{ errors.critereNameForm.nom }}
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Ordre et visibilite des criteres -->
                            <div
                                class="flex flex-col items-start space-y-2 md:flex-row md:items-center md:justify-between md:space-x-2 md:space-y-0"
                            >
                                <div
                                    class="flex max-w-sm items-center space-x-2"
                                >
                                    <label
                                        for="ordre"
                                        class="block text-sm font-medium normal-case text-gray-700"
                                    >
                                        Ordre: {{ critere.ordre }}
                                    </label>
                                    <div
                                        class="mt-1 flex flex-1 rounded-md md:flex-auto"
                                    >
                                        <select
                                            ref="select"
                                            name="ordre"
                                            id="ordre"
                                            v-model="
                                                critereVisibilityForm[
                                                    critere.id
                                                ].ordre
                                            "
                                            @change="
                                                updateCritereVisibility(critere)
                                            "
                                            class="block w-full rounded-md border-gray-300 text-sm text-gray-800 shadow-sm"
                                        >
                                            <option disabled value="">
                                                Selectionner un ordre
                                            </option>
                                            <option
                                                v-for="numero in ordreCriteres"
                                                :key="numero"
                                                :value="numero"
                                            >
                                                {{ numero }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="
                                            critereVisibilityForm[critere.id]
                                                .visible_front
                                        "
                                        @change="
                                            updateCritereVisibility(critere)
                                        "
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Visible Front (Critère pour pages de
                                        résultats)</span
                                    ></label
                                >

                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="
                                            critereVisibilityForm[critere.id]
                                                .visible_back
                                        "
                                        @change="
                                            updateCritereVisibility(critere)
                                        "
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Visible Back (Visible à l'ajout
                                        d'activité)</span
                                    ></label
                                >

                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="
                                            critereVisibilityForm[critere.id]
                                                .visible_block
                                        "
                                        @change="
                                            updateCritereVisibility(critere)
                                        "
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Visible Bloc (Visible pour bloc de
                                        résultats)</span
                                    ></label
                                >

                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="
                                            critereVisibilityForm[critere.id]
                                                .indexable
                                        "
                                        @change="
                                            updateCritereVisibility(critere)
                                        "
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >Indexable</span
                                    ></label
                                >

                                <button
                                    type="button"
                                    class="inline-flex items-center self-end"
                                    @click="deleteCritere(critere)"
                                >
                                    <TrashIcon
                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </div>
                            <!-- Form Unite for Range -->
                            <form
                                @submit.prevent="updateUniteCritere(critere)"
                                v-if="
                                    critere.type_champ_form === 'range' ||
                                    critere.type_champ_form === 'range multiple'
                                "
                                class="my-6 flex flex-col items-center space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                            >
                                <label class="text-sm font-semibold" for="unite"
                                    >Unité pour les champs de type
                                    "range"</label
                                >
                                <InputText
                                    class="text-sm"
                                    id="unité"
                                    placeholder="unité"
                                    v-model="critereUniteForm[critere.id].unite"
                                />
                                <label class="text-sm font-semibold" for="min"
                                    >Min</label
                                >
                                <InputNumber
                                    inputId="integeronly"
                                    class="text-sm"
                                    id="min"
                                    placeholder="Min"
                                    v-model="critereUniteForm[critere.id].min"
                                />
                                <label class="text-sm font-semibold" for="max"
                                    >Max</label
                                >
                                <InputNumber
                                    inputId="integeronly"
                                    class="text-sm"
                                    id="max"
                                    placeholder="Max"
                                    v-model="critereUniteForm[critere.id].max"
                                />
                                <button type="submit">
                                    <ArrowPathIcon
                                        class="h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                    />
                                </button>
                            </form>
                        </div>
                        <!-- Valeurs des criteres -->
                        <ul
                            class="list-inside list-disc py-2 marker:text-indigo-600"
                        >
                            <li
                                v-for="valeur in critere.valeurs"
                                :key="valeur.id"
                                class="space-y-2 text-sm text-slate-600"
                            >
                                <template v-if="valeurForm[valeur.id]">
                                    <form
                                        v-if="valeur"
                                        class="inline-flex flex-col space-y-2 md:flex-row md:items-center md:space-x-2 md:space-y-0"
                                        @submit.prevent="updateValeur(valeur)"
                                    >
                                        <div class="flex flex-col">
                                            <RangeMultiple
                                                v-if="
                                                    critere.type_champ_form ===
                                                    'range multiple'
                                                "
                                                v-model="
                                                    valeurForm[valeur.id].valeur
                                                "
                                                :unite="critere.unite"
                                                :min="critere.min"
                                                :max="critere.max"
                                            />
                                            <input
                                                v-else
                                                v-model="
                                                    valeurForm[valeur.id].valeur
                                                "
                                                type="text"
                                                :name="valeur[valeur.id]"
                                                :id="valeur[valeur.id]"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                            <div
                                                v-if="
                                                    valeurForm[valeur.id].errors
                                                        .valeur
                                                "
                                                class="mt-1 text-xs text-red-500"
                                            >
                                                {{
                                                    valeurForm[valeur.id].errors
                                                        .valeur[0]
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="flex max-w-sm items-center space-x-2"
                                        >
                                            <label
                                                for="ordre"
                                                class="block text-sm font-medium normal-case text-gray-700"
                                            >
                                                Ordre: {{ valeur.ordre }}
                                            </label>
                                            <div
                                                class="mt-1 flex flex-1 rounded-md md:flex-auto"
                                            >
                                                <select
                                                    ref="select"
                                                    name="ordre"
                                                    id="ordre"
                                                    v-model="
                                                        valeurForm[valeur.id]
                                                            .ordre
                                                    "
                                                    class="block w-full rounded-md border-gray-300 text-sm text-gray-800 shadow-sm"
                                                >
                                                    <option disabled value="">
                                                        Selectionner un ordre
                                                    </option>
                                                    <option
                                                        v-for="numero in ordreValeurs[
                                                            critere.id
                                                        ]"
                                                        :key="numero"
                                                        :value="numero"
                                                    >
                                                        {{ numero }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <label class="flex items-center">
                                            <Checkbox
                                                v-model:checked="
                                                    valeurForm[valeur.id]
                                                        .inclus_all
                                                "
                                            />
                                            <span
                                                class="ml-2 text-sm text-gray-600"
                                                >Inclus toutes les valeurs</span
                                            ></label
                                        >

                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <button type="submit">
                                                <ArrowPathIcon
                                                    class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                />
                                                <span class="sr-only"
                                                    >Mettre à jour la
                                                    valeur</span
                                                >
                                            </button>
                                            <button
                                                type="button"
                                                class="inline-flex items-center"
                                                @click="removeValeur(valeur)"
                                            >
                                                <TrashIcon
                                                    class="h-6 w-6 text-red-500 hover:text-red-700"
                                                />
                                            </button>
                                            <button
                                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-3 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                type="button"
                                                @click="
                                                    toggleAddSousCritereForm(
                                                        valeur
                                                    )
                                                "
                                            >
                                                <div class="">
                                                    Ajouter un sous critère à
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
                                    <!-- Sous criteres -->
                                    <div v-if="valeur.sous_criteres">
                                        <div
                                            v-for="souscritere in valeur.sous_criteres"
                                            :key="souscritere.id"
                                            class="ml-4 list-inside list-disc marker:text-indigo-600"
                                        >
                                            <div class="py-2">
                                                <div
                                                    class="flex flex-grow items-center justify-start space-x-3"
                                                >
                                                    <div
                                                        class="underline decoration-gray-300 decoration-2 underline-offset-2"
                                                    >
                                                        Sous critère:
                                                        <span
                                                            class="font-semibold"
                                                            >{{
                                                                souscritere.nom
                                                            }}
                                                            <span
                                                                class="text-sm font-medium italic"
                                                                >(Type de champ:
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
                                                            class="h-6 w-6 text-red-500 hover:text-red-700"
                                                        />
                                                    </button>
                                                </div>
                                                <!-- Form Sous critere Range -->
                                                <form
                                                    @submit.prevent="
                                                        updateUniteSousCritere(
                                                            souscritere
                                                        )
                                                    "
                                                    v-if="
                                                        souscritere.type_champ_form ===
                                                            'range' ||
                                                        souscritere.type_champ_form ===
                                                            'range multiple'
                                                    "
                                                    class="my-6 flex flex-col items-center space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                                >
                                                    <label
                                                        class="text-sm font-semibold"
                                                        for="unite"
                                                        >Unité pour les champs
                                                        de type "range"</label
                                                    >
                                                    <div
                                                        class="flex flex-col items-start"
                                                    >
                                                        <InputText
                                                            class="text-sm"
                                                            id="unité"
                                                            placeholder="unité"
                                                            v-model="
                                                                souscritereUniteForm[
                                                                    souscritere
                                                                        .id
                                                                ].unite
                                                            "
                                                        />
                                                        <div
                                                            v-if="
                                                                errors.souscritereUniteForm
                                                            "
                                                            class="mt-2 text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors
                                                                    .souscritereUniteForm
                                                                    .unite
                                                            }}
                                                        </div>
                                                    </div>

                                                    <label
                                                        class="text-sm font-semibold"
                                                        for="min"
                                                        >Min</label
                                                    >
                                                    <InputNumber
                                                        inputId="integeronly"
                                                        class="text-sm"
                                                        id="min"
                                                        placeholder="Min"
                                                        v-model="
                                                            souscritereUniteForm[
                                                                souscritere.id
                                                            ].min
                                                        "
                                                    />
                                                    <label
                                                        class="text-sm font-semibold"
                                                        for="max"
                                                        >Max</label
                                                    >
                                                    <InputNumber
                                                        inputId="integeronly"
                                                        class="text-sm"
                                                        id="max"
                                                        placeholder="Max"
                                                        v-model="
                                                            souscritereUniteForm[
                                                                souscritere.id
                                                            ].max
                                                        "
                                                    />
                                                    <button type="submit">
                                                        <ArrowPathIcon
                                                            class="h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                                        />
                                                    </button>
                                                </form>
                                                <ul
                                                    v-if="
                                                        souscritere.sous_criteres_valeurs
                                                    "
                                                    class="ml-4 mt-2 list-inside list-disc space-y-3 marker:text-indigo-600"
                                                >
                                                    <li
                                                        v-for="sousvaleur in souscritere.sous_criteres_valeurs"
                                                        :key="sousvaleur.id"
                                                        class="text-sm text-slate-600"
                                                    >
                                                        <form
                                                            v-if="sousvaleur"
                                                            class="inline-flex flex-col space-y-2 md:flex-row md:space-x-4 md:space-y-0"
                                                            @submit.prevent="
                                                                updateSousValeur(
                                                                    sousvaleur
                                                                )
                                                            "
                                                        >
                                                            <div>
                                                                <label
                                                                    :for="
                                                                        sousvaleur[
                                                                            sousvaleur
                                                                                .id
                                                                        ]
                                                                    "
                                                                    class="block text-sm font-medium normal-case text-gray-700"
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
                                                                                sousvaleur
                                                                                    .id
                                                                            ]
                                                                                .valeur
                                                                        "
                                                                        type="text"
                                                                        :name="
                                                                            sousvaleur[
                                                                                sousvaleur
                                                                                    .id
                                                                            ]
                                                                        "
                                                                        :id="
                                                                            sousvaleur[
                                                                                sousvaleur
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
                                                                            sousvaleur
                                                                                .id
                                                                        ].errors
                                                                            .valeur
                                                                    "
                                                                    class="text-xs text-red-500"
                                                                >
                                                                    {{
                                                                        sousvaleurForm[
                                                                            sousvaleur
                                                                                .id
                                                                        ].errors
                                                                            .valeur
                                                                    }}
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="flex max-w-sm items-center space-x-2"
                                                            >
                                                                <label
                                                                    for="ordre"
                                                                    class="block text-sm font-medium normal-case text-gray-700"
                                                                >
                                                                    Ordre:
                                                                    {{
                                                                        sousvaleur.ordre
                                                                    }}
                                                                </label>
                                                                <div
                                                                    class="mt-1 flex flex-1 rounded-md md:flex-auto"
                                                                >
                                                                    <select
                                                                        ref="select"
                                                                        name="ordre"
                                                                        id="ordre"
                                                                        v-model="
                                                                            sousvaleurForm[
                                                                                sousvaleur
                                                                                    .id
                                                                            ]
                                                                                .ordre
                                                                        "
                                                                        class="block w-full rounded-md border-gray-300 text-sm text-gray-800 shadow-sm"
                                                                    >
                                                                        <option
                                                                            disabled
                                                                            value=""
                                                                        >
                                                                            Selectionner
                                                                            un
                                                                            ordre
                                                                        </option>
                                                                        <option
                                                                            v-for="numero in ordreSousValeurs[
                                                                                souscritere
                                                                                    .id
                                                                            ]"
                                                                            :key="
                                                                                numero
                                                                            "
                                                                            :value="
                                                                                numero
                                                                            "
                                                                        >
                                                                            {{
                                                                                numero
                                                                            }}
                                                                        </option>
                                                                    </select>
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
                                                                        à jour
                                                                        la
                                                                        valeur</span
                                                                    ></button
                                                                ><button
                                                                    type="button"
                                                                    class="inline-flex items-center"
                                                                    @click="
                                                                        removeSousValeur(
                                                                            sousvaleur
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
                                                                        placeholder="Valeur"
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
                                                            souscritere.type_champ &&
                                                            souscritere
                                                                .type_champ
                                                                .sous_criterable
                                                        "
                                                        type="button"
                                                        @click="
                                                            toggleAddSousValeurForm(
                                                                souscritere
                                                            )
                                                        "
                                                    >
                                                        <div>
                                                            Ajouter une valeur
                                                            au sous critère
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
                                    <!-- ajout de sous criteres -->
                                    <form
                                        v-if="showAddSousCritereForm(valeur)"
                                        class="inline-flex w-full max-w-sm flex-grow items-end justify-between py-2"
                                        @submit.prevent="addSousCritere(valeur)"
                                    >
                                        <div
                                            class="flex w-full flex-grow flex-col"
                                        >
                                            <label for="newSousCritere"
                                                >Ajouter un sous critère:</label
                                            >
                                            <div class="mt-1 flex rounded-md">
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
                                                v-if="addSousCritereForm.nom"
                                                class="w-full flex-grow"
                                                v-model="
                                                    addSousCritereForm.type_champ
                                                "
                                            >
                                                <div class="relative mt-1">
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
                                                                v-for="type_champ in type_champs"
                                                                :key="
                                                                    type_champ.id
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
                                                                    ><span
                                                                        v-if="
                                                                            type_champ.criterable
                                                                        "
                                                                        class="text-sm text-blue-500"
                                                                    >
                                                                        Module
                                                                        criterable</span
                                                                    ><span
                                                                        v-else
                                                                        class="text-sm text-blue-500"
                                                                    >
                                                                        Module
                                                                        informationnel
                                                                        (non
                                                                        criterable)</span
                                                                    >
                                                                    /
                                                                    <span
                                                                        v-if="
                                                                            type_champ.sous_criterable
                                                                        "
                                                                        class="text-sm text-green-500"
                                                                    >
                                                                        Sous
                                                                        criterable</span
                                                                    ><span
                                                                        v-else
                                                                        class="text-sm text-green-500"
                                                                        >Non
                                                                        sous
                                                                        criterable</span
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
                                                toggleAddSousCritereForm(valeur)
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
                        <!-- Ajout de valeur de critere -->
                        <ul
                            v-if="showAddValeurForm(critere)"
                            class="list-inside list-disc marker:text-indigo-600"
                        >
                            <li class="text-sm text-slate-600">
                                <form
                                    class="inline-flex flex-grow items-center justify-between"
                                    @submit.prevent="addValeur(critere)"
                                >
                                    <RangeMultiple
                                        v-if="
                                            critere.type_champ_form ===
                                            'range multiple'
                                        "
                                        v-model="addValeurForm.valeur"
                                        :unite="critere.unite"
                                        :min="critere.min"
                                        :max="critere.max"
                                    />
                                    <div v-else>
                                        <label for="newValeur"></label>
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="addValeurForm.valeur"
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
                                        @click="toggleAddValeurForm(critere)"
                                        type="button"
                                        class="ml-4 inline-flex items-center"
                                    >
                                        <XCircleIcon
                                            class="h-6 w-6 text-red-500 hover:text-red-700"
                                        />
                                    </button>

                                    <div
                                        v-if="addValeurForm.errors.valeur"
                                        class="text-xs text-red-500"
                                    >
                                        {{ addValeurForm.errors.valeur[0] }}
                                    </div>
                                </form>
                            </li>
                        </ul>
                        <div
                            class="flex w-full items-center justify-center py-2"
                        >
                            <!--  -->
                            <button
                                class="inline-flex items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                v-if="
                                    !showAddValeurForm(critere) &&
                                    critere.type_champ.sous_criterable
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
                <!-- Ajout de critere -->
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
                    <!-- formulaire ajout de critere -->
                    <form
                        v-if="showAddCritereForm(categorie)"
                        class="inline-flex max-w-md flex-grow items-center justify-between"
                        @submit.prevent="addCritere(categorie)"
                    >
                        <div class="flex w-full flex-grow flex-col space-y-3">
                            <Listbox
                                as="div"
                                class="w-full flex-grow"
                                v-model="addCritereForm.critere"
                            >
                                <div class="relative mt-1">
                                    <ListboxLabel
                                        class="text-sm font-medium text-gray-700"
                                        >Critere:</ListboxLabel
                                    >

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
                                                v-slot="{ active, selected }"
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
                                                        >{{ critere.nom }}</span
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
                                as="div"
                                v-if="addCritereForm.critere"
                                class="w-full flex-grow"
                                v-model="addCritereForm.type_champ"
                            >
                                <div class="relative mt-1">
                                    <ListboxLabel
                                        class="text-sm font-medium text-gray-700"
                                        >Type de champ:</ListboxLabel
                                    >
                                    <ListboxButton
                                        class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                    >
                                        <span class="block truncate">{{
                                            addCritereForm.type_champ.type
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
                                                v-for="type_champ in type_champs"
                                                :key="type_champ.id"
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
                                                            type_champ.criterable
                                                        "
                                                        class="text-sm text-blue-500"
                                                    >
                                                        Module criterable</span
                                                    ><span
                                                        v-else
                                                        class="text-sm text-blue-500"
                                                    >
                                                        Module informationnel
                                                        (non criterable)</span
                                                    >
                                                    /
                                                    <span
                                                        v-if="
                                                            type_champ.sous_criterable
                                                        "
                                                        class="text-sm text-green-500"
                                                    >
                                                        Sous criterable</span
                                                    ><span
                                                        v-else
                                                        class="text-sm text-green-500"
                                                        >Non sous
                                                        criterable</span
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
                                v-if="addCritereForm.critere"
                                class="mt-1 flex flex-col rounded-md"
                            >
                                <label
                                    for="nom critere"
                                    class="block text-sm font-medium normal-case text-gray-700"
                                    >Modifier le nom du critère?</label
                                >
                                <input
                                    v-model="addCritereForm.nom"
                                    type="text"
                                    name="nom critere"
                                    id="nom critere"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <div
                                    v-if="addCritereForm.errors.nom"
                                    class="text-xs text-red-500"
                                >
                                    {{ addCritereForm.errors.nom[0] }}
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
            </div>
        </div>
    </AdminLayout>
</template>
