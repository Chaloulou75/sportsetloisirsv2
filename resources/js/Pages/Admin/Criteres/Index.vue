<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    ChevronLeftIcon,
    TrashIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    criteres: Object,
    user_can: Object,
});

const critereForm = ref({});
const showCreateCritereForm = ref(false);
const toggleCreateCritereForm = () => {
    showCreateCritereForm.value = !showCreateCritereForm.value;
};

const initializeCritereForm = () => {
    for (const critereId in props.criteres) {
        const critere = props.criteres[critereId];
        critereForm.value[critere.id] = useForm({
            nom: ref(critere.nom),
            remember: true,
        });
    }
};

initializeCritereForm();

const createCritereForm = useForm({
    nom: null,
});

const createCritere = () => {
    createCritereForm.post(route("admin.criteres.store"), {
        errorBag: "createCritereForm",
        preserveScroll: true,
        onSuccess: () => {
            initializeCritereForm();
            createCritereForm.reset();
            toggleCreateCritereForm();
        },
    });
};

const updateCritere = (critere) => {
    router.patch(
        route("admin.criteres.update", {
            critere: critere,
        }),
        {
            nom: critereForm.value[critere.id].nom,
        },
        {
            errorBag: "critereForm",
            preserveScroll: true,
        }
    );
};

const deleteCritere = (critere) => {
    router.delete(
        route("admin.criteres.destroy", {
            critere: critere,
        }),
        {
            preserveScroll: true,
            critere: critere,
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
    <Head title="Gestion du site" :description="'Administration du site.'" />
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
                    Gestion des critères
                </h1>
            </div>
        </template>

        <div class="w-full px-2 py-6 space-y-16 text-slate-700 md:px-6">
            <div>
                <div
                    class="flex flex-col items-start justify-center w-full py-4 space-y-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les critères existants:
                        </h3>
                        <ul
                            ref="toAnimateOne"
                            class="max-w-3xl py-4 space-y-2 text-sm list-disc list-inside text-slate-600 marker:text-indigo-600"
                        >
                            <li
                                v-for="critere in criteres"
                                :key="critere.id"
                                class="text-base text-slate-600"
                            >
                                <form
                                    v-if="critere"
                                    class="inline-flex space-x-2"
                                    @submit.prevent="updateCritere(critere)"
                                >
                                    <div class="flex flex-col mt-1 rounded-md">
                                        <input
                                            v-model="
                                                critereForm[critere.id].nom
                                            "
                                            type="text"
                                            :name="critere.nom"
                                            :id="critere.nom"
                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                        <div
                                            v-if="
                                                critereForm[critere.id].errors
                                                    .nom
                                            "
                                            class="text-xs text-red-500"
                                        >
                                            {{
                                                critereForm[critere.id].errors
                                                    .nom[0]
                                            }}
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <button type="submit">
                                            <ArrowPathIcon
                                                class="w-6 h-6 mr-1 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                            />
                                            <span class="sr-only"
                                                >Mettre à jour le critère</span
                                            >
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        @click="deleteCritere(critere)"
                                    >
                                        <TrashIcon
                                            class="w-5 h-5 text-red-500"
                                        />
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center w-full md:w-1/3"
                    >
                        <h3
                            class="w-full mb-4 text-lg font-bold text-center underline text-slate-700 decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un critère:
                        </h3>
                        <button
                            type="button"
                            v-if="!showCreateCritereForm"
                            @click="toggleCreateCritereForm"
                            class="inline-flex items-center justify-center w-auto px-4 py-3 space-y-1 text-sm font-medium text-center text-gray-600 border border-gray-600 rounded shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            Créer un nouveau critère
                        </button>
                        <form
                            v-if="showCreateCritereForm"
                            class="flex flex-col items-start space-y-4"
                            @submit.prevent="createCritere"
                        >
                            <div class="flex flex-col mt-1 rounded-md">
                                <input
                                    v-model="createCritereForm.nom"
                                    type="text"
                                    name="critere_nom"
                                    id="critere_nom"
                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <div
                                    v-if="createCritereForm.errors.nom"
                                    class="text-xs text-red-500"
                                >
                                    {{ createCritereForm.errors.nom }}
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between w-full"
                            >
                                <button
                                    :disabled="createCritereForm.processing"
                                    :class="{
                                        'opacity-25':
                                            createCritereForm.processing,
                                    }"
                                    class="px-2 py-2 text-sm font-medium text-center text-white bg-blue-600 border border-gray-300 rounded shadow-sm"
                                    type="submit"
                                >
                                    Enregistrer
                                </button>
                                <button
                                    class="px-2 py-2 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded shadow-sm"
                                    type="button"
                                    @click="toggleCreateCritereForm"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
