<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    ChevronLeftIcon,
    TrashIcon,
    ArrowPathIcon,
    XCircleIcon,
    PlusCircleIcon,
} from "@heroicons/vue/24/outline";

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

const updateAttributForm = ref({});
const initializeAttributForm = () => {
    for (const tarifId in props.tarifs) {
        const tarif = props.tarifs[tarifId];

        for (const attributId in tarif.tariftypeattributs) {
            const attribut = tarif.tariftypeattributs[attributId];
            updateAttributForm.value[attribut.id] = useForm({
                attribut: ref(attribut.attribut),
                remember: true,
            });
        }
    }
};

initializeAttributForm();

const attributFormsVisibility = ref({});

const toggleAddAttributForm = (tarif) => {
    attributFormsVisibility.value[tarif.id] =
        !attributFormsVisibility.value[tarif.id];
};

const showAddAttributForm = (tarif) => {
    return attributFormsVisibility.value[tarif.id] || false;
};

const addAttributForm = useForm({
    attribut: null,
    remember: true,
});

const addAttribut = (tarif) => {
    addAttributForm.post(
        route("admin.tarifs.attributs.store", {
            tarif: tarif,
        }),
        {
            errorBag: "addAttributForm",
            preserveScroll: true,
            onSuccess: () => {
                initializeAttributForm();
                addAttributForm.reset();
                toggleAddAttributForm(tarif);
            },
        }
    );
};

const updateAttribut = (tarif, attribut) => {
    router.patch(
        route("admin.tarifs.attributs.update", {
            tarif: tarif,
            attribut: attribut,
        }),
        {
            attribut: updateAttributForm.value[attribut.id].attribut,
        },
        {
            errorBag: "updateAttributForm",
            preserveScroll: true,
        }
    );
};

const removeAttribut = (tarif, attribut) => {
    router.delete(
        route("admin.tarifs.attributs.destroy", {
            tarif: tarif,
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

const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head title="Gestion du site" :description="'Administration des tarifs.'" />
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion des tarifs
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div
                class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <h3
                        class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Gérer les types de tarifs:
                    </h3>
                    <ul
                        ref="toAnimateOne"
                        class="max-w-3xl list-inside list-disc space-y-4 py-4 text-sm text-slate-600 marker:text-indigo-600"
                    >
                        <li
                            v-for="tarif in tarifs"
                            :key="tarif.id"
                            class="text-base text-slate-600"
                        >
                            <form
                                v-if="tarif"
                                class="inline-flex space-x-2"
                                @submit.prevent="updateTarif(tarif)"
                            >
                                <div class="mt-1 flex flex-col rounded-md">
                                    <input
                                        v-model="tarifForm[tarif.id].type"
                                        type="text"
                                        :name="tarif.type"
                                        :id="tarif.type"
                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                            class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
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
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                    <!-- <button
                                        v-if="!showAddAttributForm(tarif)"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-center text-xs font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
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
                                class="ml-6 list-inside list-disc space-y-3 py-2 marker:text-indigo-600"
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
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                                    removeAttribut(
                                                        tarif,
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
                                </li>
                            </ul>
                            <form
                                v-if="showAddAttributForm(tarif)"
                                class="ml-6 inline-flex flex-grow items-end justify-between text-center text-xs font-medium text-gray-600"
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
                                    <div class="mt-1 flex rounded-md">
                                        <input
                                            v-model="addAttributForm.attribut"
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
                                        {{ errors.addAttributForm.attribut }}
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
                                    @click="toggleAddAttributForm(tarif)"
                                    type="button"
                                    class="ml-4 inline-flex items-center"
                                >
                                    <XCircleIcon
                                        class="h-6 w-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </form> -->
                        </li>
                    </ul>
                </div>

                <!-- Create type de tarif -->
                <div
                    class="flex w-full flex-col items-center justify-center md:w-1/3"
                >
                    <h3
                        class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Créer un type de tarif:
                    </h3>
                    <button
                        type="button"
                        v-if="!showCreateTarifForm"
                        @click="toggleCreateTarifForm"
                        class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Créer un type de tarif
                    </button>
                    <form
                        v-if="showCreateTarifForm"
                        class="flex flex-col items-start space-y-4"
                        @submit.prevent="createTarif"
                    >
                        <div class="mt-1 flex flex-col rounded-md">
                            <input
                                v-model="createtarifForm.type"
                                type="text"
                                name="tarif_type"
                                id="tarif_type"
                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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

                        <div class="flex w-full items-center justify-between">
                            <button
                                :disabled="createtarifForm.processing"
                                class="rounded border border-gray-300 bg-blue-600 px-2 py-2 text-center text-sm font-medium text-white shadow-sm"
                                type="submit"
                            >
                                Enregistrer
                            </button>
                            <button
                                class="rounded border border-gray-300 bg-white px-2 py-2 text-center text-sm font-medium text-gray-600 shadow-sm"
                                type="button"
                                @click="toggleCreateTarifForm"
                            >
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
