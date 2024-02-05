<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    TrashIcon,
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    ChevronUpDownIcon,
    ChevronLeftIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/outline";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    tarifs: Object,
    user_can: Object,
});

const tarifForm = ref({});
const showCreateTarifForm = ref(false);
const toggleCreateTarifForm = () => {
    showCreateTarifForm.value = !showCreateTarifForm.value;
};

const initializetarifForm = () => {
    for (const tarifId in props.tarifs) {
        const tarif = props.tarifs[tarifId];
        tarifForm.value[tarif.id] = useForm({
            type: ref(tarif.type),
            remember: true,
        });
    }
};

initializetarifForm();

const createtarifForm = useForm({
    type: null,
});

const createTarif = () => {
    createtarifForm.post(route("admin.tarifs.store"), {
        errorBag: "createtarifForm",
        preserveScroll: true,
        onSuccess: () => {
            initializetarifForm();
            createtarifForm.reset();
            toggleCreateTarifForm();
        },
    });
};

const updateTarif = (tarif) => {
    router.patch(
        route("admin.tarifs.update", {
            tarif: tarif,
        }),
        {
            type: tarifForm.value[tarif.id].type,
        },
        {
            errorBag: "updateTarifForm",
            preserveScroll: true,
        }
    );
};

const deleteTarif = (tarif) => {
    router.delete(
        route("admin.tarifs.destroy", {
            tarif: tarif,
        }),
        {
            preserveScroll: true,
            tarif: tarif,
        }
    );
};

const showCategories = ref({});

props.tarifs.forEach((tarifType) => {
    if (!showCategories.value[tarifType.id]) {
        showCategories.value[tarifType.id] = ref(false);
    }
});

const toggleShowCategories = (tarifType) => {
    showCategories.value[tarifType.id] = !showCategories.value[tarifType.id];
};

const attachAllDisCat = (tarifType) => {
    const isConfirmed = window.confirm(
        "Sûr de vouloir lier toutes les disciplines/catégories à ce type de tarif?"
    );
    if (isConfirmed) {
        router.post(
            route("admin.tariftypes.discats.store", {
                tarifType: tarifType,
            }),
            {
                preserveScroll: true,
            }
        );
    }
};

const detachAllDisCat = (tarifType) => {
    const isConfirmed = window.confirm(
        "Attention! Sûr de vouloir délier toutes les disciplines/catégories à ce type de tarif? Cela supprimera les attributs et les tarifs associés..."
    );
    if (isConfirmed) {
        router.delete(
            route("admin.tariftypes.discats.destroy", {
                tarifType: tarifType,
            }),
            {
                preserveScroll: true,
            }
        );
    }
};

const duplicateAttribut = (tarifType, attribut) => {
    const isConfirmed = window.confirm(
        "Attention! Sûr de vouloir dupliquer l'attribut à toutes les disciplines/catégories lié à ce type de tarif?"
    );
    if (isConfirmed) {
        router.post(
            route("admin.tariftypes.discats.attributs.store", {
                tarifType: tarifType,
                attribut: attribut,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {},
            }
        );
    }
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
        title="Gestion des types de tarifs"
        :description="'Administration des types de tarifs.'"
    />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion des types de tarifs
                </h1>
            </div>
        </template>

        <div class="w-full px-2 py-6 space-y-16 text-slate-700 md:px-6">
            <div
                class="flex flex-col items-start justify-center w-full py-4 space-y-4 md:flex-row md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <h3
                        class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Gérer les types de tarifs:
                    </h3>
                    <ul
                        ref="toAnimateOne"
                        class="max-w-3xl py-4 space-y-4 text-sm list-disc list-inside text-slate-600 marker:text-indigo-600"
                    >
                        <li
                            v-for="tarif in tarifs"
                            :key="tarif.id"
                            class="w-full text-base text-slate-600"
                        >
                            <form
                                v-if="tarif"
                                class="inline-flex space-x-2"
                                @submit.prevent="updateTarif(tarif)"
                            >
                                <div class="flex flex-col mt-1 rounded-md">
                                    <input
                                        v-model="tarifForm[tarif.id].type"
                                        type="text"
                                        :name="tarif.type"
                                        :id="tarif.type"
                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                        placeholder=""
                                        autocomplete="none"
                                    />
                                    <div
                                        v-if="errors.updateTarifForm"
                                        class="text-xs text-red-500"
                                    >
                                        {{ errors.updateTarifForm.type }}
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <button type="submit">
                                        <ArrowPathIcon
                                            class="w-6 h-6 mr-1 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                        />
                                        <span class="sr-only"
                                            >Mettre à jour le tarif</span
                                        >
                                    </button>
                                    <button
                                        type="button"
                                        @click="deleteTarif(tarif)"
                                    >
                                        <TrashIcon
                                            class="w-5 h-5 text-red-500"
                                        />
                                    </button>
                                    <!-- <button
                                        v-if="!showAddAttributForm(tarif)"
                                        class="inline-flex items-center justify-center p-2 text-xs font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                        type="button"
                                        @click="toggleAddAttributForm(tarif)"
                                    >
                                        <div>
                                            Ajouter un attribut à
                                            <span class="font-semibold">
                                                {{ tarif.type }}</span
                                            >
                                        </div>
                                    </button> -->
                                </div>
                            </form>

                            <!-- <ul
                                class="py-2 ml-6 space-y-3 list-disc list-inside marker:text-indigo-600"
                            >
                                <li
                                    v-for="attribut in tarif.tariftypeattributs"
                                    :key="attribut.id"
                                    class="space-y-2 text-sm text-slate-600"
                                >
                                    <form
                                        v-if="attribut"
                                        class="inline-flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0"
                                        @submit.prevent="
                                            updateAttribut(tarif, attribut)
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
                                                    ].attribut
                                                "
                                                type="text"
                                                :name="attribut.attribut"
                                                :id="attribut.attribut"
                                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                            <div
                                                v-if="errors.updateAttributForm"
                                                class="mt-1 text-xs text-red-500"
                                            >
                                                {{
                                                    errors.updateAttributForm
                                                        .attribut
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <button type="submit">
                                                <ArrowPathIcon
                                                    class="w-6 h-6 mr-1 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
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
                                                    removeAttribut(
                                                        tarif,
                                                        attribut
                                                    )
                                                "
                                            >
                                                <TrashIcon
                                                    class="w-6 h-6 text-red-500 hover:text-red-700"
                                                />
                                            </button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                            <form
                                v-if="showAddAttributForm(tarif)"
                                class="inline-flex items-end justify-between flex-grow ml-6 text-xs font-medium text-center text-gray-600"
                                @submit.prevent="addAttribut(tarif)"
                            >
                                <div class="flex flex-col items-start">
                                    <label for="newAttribut"
                                        >Ajouter un attribut à
                                        <span class="font-semibold">{{
                                            tarif.type
                                        }}</span
                                        >:</label
                                    >
                                    <div class="flex mt-1 rounded-md">
                                        <input
                                            v-model="addAttributForm.attribut"
                                            type="text"
                                            name="newAttribut"
                                            id="newAttribut"
                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                    </div>
                                    <div
                                        v-if="errors.addAttributForm"
                                        class="text-xs text-red-500"
                                    >
                                        {{ errors.addAttributForm.attribut }}
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    class="inline-flex items-end ml-4"
                                >
                                    <PlusCircleIcon
                                        class="w-6 h-6 text-indigo-500 hover:text-indigo-700"
                                    />
                                </button>
                                <button
                                    @click="toggleAddAttributForm(tarif)"
                                    type="button"
                                    class="inline-flex items-center ml-4"
                                >
                                    <XCircleIcon
                                        class="w-6 h-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </form> -->
                        </li>
                    </ul>
                </div>

                <!-- Create type de tarif -->
                <div
                    class="flex flex-col items-center justify-center w-full md:w-1/3"
                >
                    <h3
                        class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Créer un type de tarif:
                    </h3>
                    <button
                        type="button"
                        v-if="!showCreateTarifForm"
                        @click="toggleCreateTarifForm"
                        class="inline-flex items-center justify-center w-auto px-4 py-3 space-y-1 text-sm font-medium text-center text-gray-600 border border-gray-600 rounded shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Créer un type de tarif
                    </button>
                    <form
                        v-if="showCreateTarifForm"
                        class="flex flex-col items-start space-y-4"
                        @submit.prevent="createTarif"
                    >
                        <div class="flex flex-col mt-1 rounded-md">
                            <input
                                v-model="createtarifForm.type"
                                type="text"
                                name="tarif_type"
                                id="tarif_type"
                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder=""
                                autocomplete="none"
                            />
                            <div
                                v-if="errors.createtarifForm"
                                class="text-xs text-red-500"
                            >
                                {{ errors.createtarifForm.type }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full">
                            <button
                                :disabled="createtarifForm.processing"
                                :class="{
                                    'opacity-25': createtarifForm.processing,
                                }"
                                class="px-2 py-2 text-sm font-medium text-center text-white bg-blue-600 border border-gray-300 rounded shadow-sm"
                                type="submit"
                            >
                                Enregistrer
                            </button>
                            <button
                                class="px-2 py-2 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded shadow-sm"
                                type="button"
                                @click="toggleCreateTarifForm"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div
                class="flex flex-col items-start justify-center w-full py-4 space-y-4"
            >
                <h3
                    class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                >
                    Liaison des types de tarifs aux couples "disciplines /
                    categories" et leurs attributs / sous attributs associés:
                    <span class="text-xs italic"
                        >(Vérifier dans les parties "gestion des catégories" ou
                        "gestion des disciplines" le lien entre les disciplines
                        et catégories!)</span
                    >
                </h3>
                <div
                    class="flex flex-col items-start justify-around h-full gap-8 text-base text-slate-600 md:flex-row md:flex-wrap"
                >
                    <div
                        class="flex flex-col items-center justify-between max-w-md px-4 py-3 border border-gray-100 shadow bg-gray-50"
                        v-for="tarifType in tarifs"
                        :key="tarifType.id"
                    >
                        <div>
                            <h3>
                                <span
                                    class="text-lg font-semibold text-indigo-600"
                                >
                                    {{ tarifType.type }}</span
                                >
                                est lié aux
                                <span class="font-semibold"
                                    >disciplines-catégories</span
                                >
                                suivantes:
                            </h3>
                        </div>
                        <button
                            v-if="tarifType.categories.length > 0"
                            @click="toggleShowCategories(tarifType)"
                            class="w-full px-3 py-2 my-3 text-sm bg-white border border-gray-200 rounded-md"
                        >
                            Montrer / fermer toutes les disciplines-categories
                        </button>
                        <TransitionRoot
                            :show="showCategories[tarifType.id]"
                            enter="transition-opacity duration-75"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="transition-opacity duration-150"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <div
                                v-if="tarifType.categories.length > 0"
                                class="my-4"
                            >
                                <ul class="ml-4 list-disc list-inside">
                                    <li
                                        v-for="disCat in tarifType.categories"
                                        :key="disCat.id"
                                    >
                                        <span class="text-base font-semibold"
                                            >{{
                                                disCat.categorie.discipline.name
                                            }}
                                            /
                                            {{
                                                disCat.categorie
                                                    .nom_categorie_client
                                            }}
                                        </span>
                                        <span
                                            class="text-xs"
                                            v-if="
                                                disCat.tarif_attributs.length >
                                                0
                                            "
                                            >, avec pour attributs:</span
                                        >
                                        <ul class="ml-4 list-disc list-inside">
                                            <li
                                                v-for="attribut in disCat.tarif_attributs"
                                                :key="attribut.id"
                                            >
                                                <span
                                                    class="italic font-semibold"
                                                    >{{ attribut.nom }} ({{
                                                        attribut.type_champ_form
                                                    }})
                                                </span>
                                                <ul
                                                    class="ml-4 list-disc list-inside"
                                                    v-if="
                                                        attribut.valeurs
                                                            .length > 0
                                                    "
                                                >
                                                    <li
                                                        class="ml-4 text-sm"
                                                        v-for="valeur in attribut.valeurs"
                                                        :key="valeur.id"
                                                    >
                                                        {{ valeur.valeur }},
                                                    </li>
                                                </ul>
                                                <span
                                                    class="text-xs"
                                                    v-if="
                                                        attribut.sous_attributs
                                                            .length > 0
                                                    "
                                                >
                                                    avec pour sous
                                                    attributs:</span
                                                >
                                                <ul
                                                    class="ml-4 text-sm list-disc list-inside"
                                                >
                                                    <li
                                                        v-for="ssAttr in attribut.sous_attributs"
                                                        :key="ssAttr.id"
                                                    >
                                                        {{ ssAttr.nom }} ({{
                                                            ssAttr.type_champ_form
                                                        }})
                                                        <ul
                                                            v-if="
                                                                ssAttr.valeurs
                                                                    .length > 0
                                                            "
                                                            class="ml-4 text-xs list-disc list-inside"
                                                        >
                                                            <li
                                                                v-for="valeur in ssAttr.valeurs"
                                                                :key="valeur.id"
                                                            >
                                                                {{
                                                                    valeur.valeur
                                                                }}
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                                <div class="my-2">
                                                    <button
                                                        type="button"
                                                        @click.prevent="
                                                            duplicateAttribut(
                                                                tarifType,
                                                                attribut
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm group hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                                    >
                                                        <div>
                                                            Dupliquer
                                                            <span
                                                                class="font-semibold text-red-500 group-hover:text-white"
                                                                >{{
                                                                    attribut.nom
                                                                }}</span
                                                            >
                                                            à toutes les
                                                            disciplines-categories
                                                            liées à
                                                            {{
                                                                tarifType.type
                                                            }}?
                                                        </div>
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </TransitionRoot>
                        <div class="mt-auto space-y-3">
                            <button
                                type="button"
                                @click.prevent="attachAllDisCat(tarifType)"
                                class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm group hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                <div>
                                    Lier
                                    <span
                                        class="text-red-500 group-hover:text-white"
                                        >{{ tarifType.type }}</span
                                    >
                                    à toutes les disciplines-catégories
                                    existantes?
                                </div>
                            </button>
                            <button
                                type="button"
                                @click.prevent="detachAllDisCat(tarifType)"
                                class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm group hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                <div>
                                    Délier
                                    <span
                                        class="text-red-500 group-hover:text-white"
                                        >{{ tarifType.type }}</span
                                    >
                                    à toutes ses disciplines-categories?
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
