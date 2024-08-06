<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import NavAdminDisCatParametres from "@/Components/Admin/NavAdminDisCatParametres.vue";
import NavAdminDisCatTarifsForm from "@/Components/Admin/NavAdminDisCatTarifsForm.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import Dropdown from "primevue/dropdown";
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
    categorie: Object,
    categories: Object,
    tarifType: Object,
    user_can: Object,
    type_champs: Object,
});

const toggleShowPlanningForm = useForm({
    show_planning: true,
});

const postShowPlanning = () => {
    toggleShowPlanningForm.patch(
        route("admin.disciplines.categories.tarifs.update_show_planning", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: props.tarifType,
        }),
        {
            errorBag: "toggleShowPlanningForm",
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};

const showAddBookingFieldForm = ref(false);
const toggleAddBookingFieldForm = () => {
    showAddBookingFieldForm.value = !showAddBookingFieldForm.value;
};

const addValeurFormVisibility = ref([]);
const toggleAddValeurForm = (field) => {
    addValeurFormVisibility.value[field.id] =
        !addValeurFormVisibility.value[field.id];
};
const showAddValeurForm = (field) => {
    return addValeurFormVisibility.value[field.id] || false;
};

const addSousFieldFormVisibility = ref([]);
const toggleAddSousFieldForm = (valeur) => {
    addSousFieldFormVisibility.value[valeur.id] =
        !addSousFieldFormVisibility.value[valeur.id];
};
const showAddSousFieldForm = (valeur) => {
    return addSousFieldFormVisibility.value[valeur.id] || false;
};

const addSousFieldValeurFormVisibility = ref([]);
const toggleAddSousFieldValeurForm = (sousField) => {
    addSousFieldValeurFormVisibility.value[sousField.id] =
        !addSousFieldValeurFormVisibility.value[sousField.id];
};
const showAddSousFieldValeurForm = (sousField) => {
    return addSousFieldValeurFormVisibility.value[sousField.id] || false;
};

const addBookingFieldForm = useForm({
    nom: null,
    type_champ: props.type_champs[0],
    remember: true,
});

const addValeurForm = useForm({
    valeur: null,
    remember: true,
});

const addSousFieldForm = useForm({
    nom: null,
    type_champ: props.type_champs[0],
    remember: true,
});

const addSousFieldValeurForm = useForm({
    valeur: null,
    remember: true,
});

const addTarifBookingField = () => {
    addBookingFieldForm.post(
        route("admin.disciplines.categories.tarifs.bookingfields.store", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: props.tarifType,
        }),
        {
            errorBag: "addBookingFieldForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
                addBookingFieldForm.reset();
                toggleAddBookingFieldForm();
            },
        }
    );
};

const updateBookingFieldForm = ref({});
const updateValeurForm = ref({});
const updateSousFieldForm = ref({});
const updateSousFieldValeurForm = ref({});

const initializeBookingFieldForm = () => {
    for (const fieldId in props.tarifType.tarif_booking_fields) {
        const field = props.tarifType.tarif_booking_fields[fieldId];
        updateBookingFieldForm.value[field.id] = useForm({
            nom: ref(field.nom),
            remember: true,
        });

        for (const valeurId in field.valeurs) {
            const valeur = field.valeurs[valeurId];
            updateValeurForm.value[valeur.id] = useForm({
                valeur: ref(valeur.valeur),
                remember: true,
            });

            for (const sousFieldId in valeur.sous_fields) {
                const sousField = valeur.sous_fields[sousFieldId];
                updateSousFieldForm.value[sousField.id] = useForm({
                    nom: ref(sousField.nom),
                    remember: true,
                });
                for (const valeurId in sousField.valeurs) {
                    const valeur = sousField.valeurs[valeurId];
                    updateSousFieldValeurForm.value[valeur.id] = useForm({
                        valeur: ref(valeur.valeur),
                        remember: true,
                    });
                }
            }
        }
    }
};

const deleteTarifTypeBookingField = (field) => {
    router.delete(
        route("admin.disciplines.categories.tarifs.bookingfields.destroy", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: props.tarifType,
            bookingfield: field,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const updateBookingField = (field) => {
    router.patch(
        route("admin.disciplines.categories.tarifs.bookingfields.update", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: props.tarifType,
            bookingfield: field,
        }),
        {
            nom: updateBookingFieldForm.value[field.id].nom,
        },
        {
            errorBag: "updateBookingFieldForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const addTarifFieldValeur = (field) => {
    addValeurForm.post(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.store",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
            }
        ),
        {
            errorBag: "addValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
                addValeurForm.reset();
                toggleAddValeurForm(field);
            },
        }
    );
};

const updateValeur = (field, valeur) => {
    router.patch(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.update",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
            }
        ),
        {
            valeur: updateValeurForm.value[valeur.id].valeur,
        },
        {
            errorBag: "updateValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const deleteTarifTypeFieldValeur = (field, valeur) => {
    router.delete(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.destroy",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
            }
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const addBookingFieldSousField = (field, valeur) => {
    addSousFieldForm.post(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.store",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
            }
        ),
        {
            errorBag: "addSousFieldForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
                addSousFieldForm.reset();
                toggleAddSousFieldForm(valeur);
            },
        }
    );
};

const updateSousField = (field, valeur, sousField) => {
    console.log(valeur);
    router.patch(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.update",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
                sousField: sousField,
            }
        ),
        {
            nom: updateSousFieldForm.value[sousField.id].nom,
        },
        {
            errorBag: "updateSousFieldForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const deleteSousField = (field, valeur, sousField) => {
    router.delete(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.destroy",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
                sousField: sousField,
            }
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const addTarifBookingFieldSousFieldValeur = (field, valeur, sousField) => {
    addSousFieldValeurForm.post(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.valeurs.store",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
                sousField: sousField,
            }
        ),
        {
            errorBag: "addSousFieldValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
                addSousFieldValeurForm.reset();
                toggleAddSousFieldValeurForm(sousField);
            },
        }
    );
};

const updateSousFieldValeur = (field, valeur, sousField, sousFieldValeur) => {
    router.patch(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.valeurs.update",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
                sousField: sousField,
                sousFieldValeur: sousFieldValeur,
            }
        ),
        {
            valeur: updateSousFieldValeurForm.value[valeur.id].valeur,
        },
        {
            errorBag: "updateSousFieldValeurForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

const deleteSousFieldValeur = (field, valeur, sousField, sousFieldValeur) => {
    router.delete(
        route(
            "admin.disciplines.categories.tarifs.bookingfields.valeurs.sous_fields.valeurs.destroy",
            {
                discipline: props.discipline,
                categorie: props.categorie,
                tarifType: props.tarifType,
                bookingfield: field,
                valeur: valeur,
                sousField: sousField,
                sousFieldValeur: sousFieldValeur,
            }
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                initializeBookingFieldForm();
            },
        }
    );
};

onMounted(() => {
    toggleShowPlanningForm.show_planning = ref(props.tarifType.show_planning);
    initializeBookingFieldForm();
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
                    Gestion du formulaire de réservation pour
                    <span class="text-indigo-600">{{ tarifType.nom }}</span>
                    de
                    <span class="text-indigo-600"
                        >{{ categorie.nom_categorie_client }} de
                        {{ discipline.name }}</span
                    >
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
                    <span class="font-semibold">{{ tarifType.nom }} </span>
                </p>
                <!-- liste BookingFields -->
                <label class="my-4 flex items-center">
                    <Checkbox
                        v-model:checked="toggleShowPlanningForm.show_planning"
                        @change="postShowPlanning"
                    />
                    <span class="ml-2 text-sm text-gray-600"
                        >Montrer le planning de l'activité</span
                    ></label
                >
                <!-- liste des fields booking -->

                <ul
                    v-if="
                        tarifType.tarif_booking_fields &&
                        tarifType.tarif_booking_fields.length > 0
                    "
                    class="ml-6 list-inside list-disc space-y-2 py-4 text-sm text-slate-600 marker:text-indigo-600"
                >
                    <p class="underline underline-offset-2">
                        Liste des champs pour le formulaire de
                        <span class="font-semibold">{{ tarifType.nom }}</span
                        >:
                    </p>
                    <li
                        v-for="field in tarifType.tarif_booking_fields"
                        :key="field.id"
                        class="space-y-1 text-sm text-slate-600"
                    >
                        <form
                            v-if="field"
                            class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                            @submit.prevent="updateBookingField(field)"
                        >
                            <div class="flex flex-col">
                                <input
                                    v-if="updateBookingFieldForm[field.id]"
                                    v-model="
                                        updateBookingFieldForm[field.id].nom
                                    "
                                    type="text"
                                    :name="field.nom"
                                    :id="field.nom"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <div
                                    v-if="errors.updateBookingFieldForm"
                                    class="mt-1 text-xs text-red-500"
                                >
                                    {{ errors.updateBookingFieldForm.nom }}
                                </div>
                            </div>
                            <div>
                                Type de champ:
                                <span class="font-semibold">{{
                                    field.type_champ_form
                                }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="submit">
                                    <ArrowPathIcon
                                        class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                    />
                                    <span class="sr-only"
                                        >Mettre à jour le champ</span
                                    >
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex items-center"
                                    @click="deleteTarifTypeBookingField(field)"
                                >
                                    <TrashIcon
                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </div>
                        </form>
                        <!-- liste des valeurs pour fields-->
                        <ul
                            v-if="field.valeurs.length > 0"
                            class="ml-6 list-inside list-disc gap-3 py-2 text-sm text-slate-600 marker:text-indigo-600"
                        >
                            <p class="text-sm underline underline-offset-2">
                                Liste des valeurs pour
                                <span class="font-semibold">{{
                                    field.nom
                                }}</span
                                >:
                            </p>
                            <li
                                v-for="valeur in field.valeurs"
                                :key="valeur.id"
                                class="space-y-1 text-sm text-slate-600"
                            >
                                <form
                                    v-if="valeur"
                                    class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                    @submit.prevent="
                                        updateValeur(field, valeur)
                                    "
                                >
                                    <div class="flex flex-col">
                                        <input
                                            v-if="updateValeurForm[valeur.id]"
                                            v-model="
                                                updateValeurForm[valeur.id]
                                                    .valeur
                                            "
                                            type="text"
                                            :name="valeur.valeur"
                                            :id="valeur.valeur"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                        <div
                                            v-if="errors.updateValeurForm"
                                            class="mt-1 text-xs text-red-500"
                                        >
                                            {{ errors.updateValeurForm.valeur }}
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <button type="submit">
                                            <ArrowPathIcon
                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                            />
                                            <span class="sr-only"
                                                >Mettre à jour la valeur du
                                                champ</span
                                            >
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center"
                                            @click="
                                                deleteTarifTypeFieldValeur(
                                                    field,
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
                                                toggleAddSousFieldForm(valeur)
                                            "
                                        >
                                            <div class="">
                                                Ajouter un sous champ à la
                                                valeur
                                                <span class="font-semibold">{{
                                                    valeur.valeur
                                                }}</span>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                <!-- Ajout sous fields à valeur-->
                                <div v-if="showAddSousFieldForm(valeur)">
                                    <form
                                        class="my-2 ml-6 inline-flex flex-grow items-end justify-between bg-gray-100 p-1 text-center text-xs font-medium text-gray-600"
                                        @submit.prevent="
                                            addBookingFieldSousField(
                                                field,
                                                valeur
                                            )
                                        "
                                    >
                                        <div
                                            class="flex flex-col items-start space-y-2"
                                        >
                                            <label for="newSousAttribut">
                                                Ajouter un sous champ à la
                                                valeur
                                                <span class="font-semibold">{{
                                                    valeur.valeur
                                                }}</span
                                                >:
                                            </label>
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    v-model="
                                                        addSousFieldForm.nom
                                                    "
                                                    type="text"
                                                    name="newSousField"
                                                    id="newSousField"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="errors.addSousFieldForm"
                                                class="text-xs text-red-500"
                                            >
                                                {{
                                                    errors.addSousFieldForm.nom
                                                }}
                                            </div>
                                            <div>
                                                <label
                                                    for="ssFieldChamp"
                                                    class="block text-left text-xs font-medium normal-case text-gray-700"
                                                >
                                                    Type de champ:
                                                </label>
                                                <div class="mt-1 text-left">
                                                    <Dropdown
                                                        v-model="
                                                            addSousFieldForm.type_champ
                                                        "
                                                        :options="type_champs"
                                                        optionLabel="type"
                                                        id="ssFieldChamp"
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
                                                toggleAddSousFieldForm(valeur)
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

                                <!-- liste sous fields -->
                                <ul
                                    v-if="valeur.sous_fields.length > 0"
                                    class="ml-6 list-inside list-disc space-y-2 py-2 text-sm text-slate-600 marker:text-indigo-600"
                                >
                                    <p class="underline underline-offset-2">
                                        Sous champs de formulaire pour
                                        <span class="font-semibold">{{
                                            valeur.valeur
                                        }}</span
                                        >:
                                    </p>
                                    <li
                                        v-for="sousField in valeur.sous_fields"
                                        :key="sousField.id"
                                        class="gap-2 text-sm text-slate-600"
                                    >
                                        <form
                                            v-if="sousField"
                                            class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                            @submit.prevent="
                                                updateSousField(
                                                    field,
                                                    valeur,
                                                    sousField
                                                )
                                            "
                                        >
                                            <div class="flex flex-col">
                                                <input
                                                    v-if="
                                                        updateSousFieldForm[
                                                            sousField.id
                                                        ]
                                                    "
                                                    v-model="
                                                        updateSousFieldForm[
                                                            sousField.id
                                                        ].nom
                                                    "
                                                    type="text"
                                                    :name="sousField.nom"
                                                    :id="sousField.nom"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                                <div
                                                    v-if="
                                                        errors.updateSousFieldForm
                                                    "
                                                    class="mt-1 text-xs text-red-500"
                                                >
                                                    {{
                                                        errors
                                                            .updateSousFieldForm
                                                            .nom
                                                    }}
                                                </div>
                                            </div>
                                            <div>
                                                Type de champ:
                                                <span class="font-semibold">{{
                                                    sousField.type_champ_form
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
                                                        >Mettre à jour le sous
                                                        champ</span
                                                    >
                                                </button>
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center"
                                                    @click="
                                                        deleteSousField(
                                                            field,
                                                            valeur,
                                                            sousField
                                                        )
                                                    "
                                                >
                                                    <TrashIcon
                                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                                    />
                                                </button>
                                            </div>
                                        </form>

                                        <!-- liste valeurs de sous fields -->
                                        <ul
                                            v-if="sousField.valeurs.length > 0"
                                            class="ml-6 list-inside list-disc space-y-1 py-2 text-sm text-slate-600 marker:text-indigo-600"
                                        >
                                            <p
                                                class="underline underline-offset-2"
                                            >
                                                Liste des valeurs pour
                                                <span class="font-semibold">{{
                                                    sousField.nom
                                                }}</span
                                                >:
                                            </p>
                                            <li
                                                v-for="sousFieldValeur in sousField.valeurs"
                                                :key="sousFieldValeur.id"
                                                class="space-y-1 text-sm text-slate-600"
                                            >
                                                <form
                                                    v-if="sousFieldValeur"
                                                    class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                                                    @submit.prevent="
                                                        updateSousFieldValeur(
                                                            field,
                                                            valeur,
                                                            sousField,
                                                            sousFieldValeur
                                                        )
                                                    "
                                                >
                                                    <div class="flex flex-col">
                                                        <input
                                                            v-if="
                                                                updateSousFieldValeurForm[
                                                                    sousFieldValeur
                                                                        .id
                                                                ]
                                                            "
                                                            v-model="
                                                                updateSousFieldValeurForm[
                                                                    sousFieldValeur
                                                                        .id
                                                                ].valeur
                                                            "
                                                            type="text"
                                                            :name="
                                                                sousFieldValeur.valeur
                                                            "
                                                            :id="
                                                                sousFieldValeur.valeur
                                                            "
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                        <div
                                                            v-if="
                                                                errors.updateSousFieldValeurForm
                                                            "
                                                            class="mt-1 text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors
                                                                    .updateSousFieldValeurForm
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
                                                                sous champ</span
                                                            >
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="inline-flex items-center"
                                                            @click="
                                                                deleteSousFieldValeur(
                                                                    field,
                                                                    valeur,
                                                                    sousField,
                                                                    sousFieldValeur
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
                                        <!-- Ajout valeur de sous fields -->
                                        <div
                                            v-if="
                                                !showAddSousFieldValeurForm(
                                                    sousField
                                                ) &&
                                                sousField.type_champ &&
                                                sousField.type_champ
                                                    .sous_criterable
                                            "
                                            class="py-2"
                                        >
                                            <button
                                                @click="
                                                    toggleAddSousFieldValeurForm(
                                                        sousField
                                                    )
                                                "
                                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                type="button"
                                            >
                                                <div>
                                                    Ajouter une valeur au sous
                                                    champ
                                                    <span
                                                        class="font-semibold"
                                                        >{{
                                                            sousField.nom
                                                        }}</span
                                                    >
                                                </div>
                                            </button>
                                        </div>

                                        <div
                                            v-if="
                                                showAddSousFieldValeurForm(
                                                    sousField
                                                )
                                            "
                                        >
                                            <form
                                                class="inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                                                @submit.prevent="
                                                    addTarifBookingFieldSousFieldValeur(
                                                        field,
                                                        valeur,
                                                        sousField
                                                    )
                                                "
                                            >
                                                <div
                                                    class="flex flex-col items-start"
                                                >
                                                    <label
                                                        for="newSousAttributValeur"
                                                        >Ajouter une valeur à
                                                        <span
                                                            class="font-semibold"
                                                            >{{
                                                                sousField.nom
                                                            }}</span
                                                        >:</label
                                                    >
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                addSousFieldValeurForm.valeur
                                                            "
                                                            type="text"
                                                            name="newSousFieldValeur"
                                                            id="newSousFieldValeur"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <div
                                                        v-if="
                                                            errors.addSousFieldValeurForm
                                                        "
                                                        class="text-xs text-red-500"
                                                    >
                                                        {{
                                                            errors
                                                                .addSousFieldValeurForm
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
                                                        toggleAddSousFieldValeurForm(
                                                            sousField
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
                            </li>
                        </ul>

                        <!-- Ajout valeur field -->
                        <div>
                            <button
                                v-if="
                                    !showAddValeurForm(field) &&
                                    field.type_champ &&
                                    field.type_champ.sous_criterable
                                "
                                @click="toggleAddValeurForm(field)"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                type="button"
                            >
                                <div>
                                    Ajouter une valeur à
                                    <span class="font-semibold">{{
                                        field.nom
                                    }}</span>
                                </div>
                            </button>
                            <div v-if="showAddValeurForm(field)">
                                <form
                                    class="inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                                    @submit.prevent="addTarifFieldValeur(field)"
                                >
                                    <div class="flex flex-col items-start">
                                        <label for="newSousAttributValeur"
                                            >Ajouter une valeur à
                                            <span class="font-semibold">{{
                                                field.nom
                                            }}</span
                                            >:</label
                                        >
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="addValeurForm.valeur"
                                                type="text"
                                                name="newSousAttributValeur"
                                                id="newSousAttributValeur"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="errors.addValeurForm"
                                            class="text-xs text-red-500"
                                        >
                                            {{ errors.addValeurForm.valeur }}
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
                                        @click="toggleAddValeurForm(field)"
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
                <!-- Ajouter un champ au formulaire -->
                <div>
                    <button
                        v-if="!showAddBookingFieldForm"
                        @click="toggleAddBookingFieldForm()"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        type="button"
                    >
                        <div>
                            Ajouter un champ au formulaire de réservation de
                            <span class="font-semibold">{{
                                tarifType.nom
                            }}</span>
                        </div>
                    </button>
                    <form
                        v-if="showAddBookingFieldForm"
                        class="ml-6 inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
                        @submit.prevent="addTarifBookingField"
                    >
                        <div class="flex flex-col items-start space-y-2">
                            <label for="newBookingField"
                                >Ajouter un champ au formulaire de réservation
                                de
                                <span class="font-semibold">{{
                                    tarifType.nom
                                }}</span
                                >:</label
                            >
                            <div class="mt-1 flex rounded-md">
                                <input
                                    v-model="addBookingFieldForm.nom"
                                    type="text"
                                    name="newBookingField"
                                    id="newBookingField"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                            </div>
                            <div
                                v-if="errors.addBookingFieldForm"
                                class="text-xs text-red-500"
                            >
                                {{ errors.addBookingFieldForm.nom }}
                            </div>
                            <div>
                                <label
                                    for="bookingFieldChamp"
                                    class="block text-left text-xs font-medium normal-case text-gray-700"
                                >
                                    Type de champ:
                                </label>
                                <div class="mt-1 text-left">
                                    <Dropdown
                                        v-model="addBookingFieldForm.type_champ"
                                        :options="type_champs"
                                        optionLabel="type"
                                        id="bookingFieldChamp"
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
                            @click="toggleAddBookingFieldForm()"
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
