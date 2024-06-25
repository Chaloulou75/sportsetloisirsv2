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
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const props = defineProps({
    errors: Object,
    criteres: Object,
    type_champs: Object,
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
    <Head title="Gestion du site, critères">
        <meta
            head-key="description"
            name="description"
            content="Administration du site. Critères"
        />
    </Head>
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
                    Gestion des critères
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les critères existants:
                        </h3>
                        <ul
                            ref="toAnimateOne"
                            class="max-w-3xl list-inside list-disc space-y-2 py-4 text-sm text-slate-600 marker:text-indigo-600"
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
                                    <div class="mt-1 flex flex-col rounded-md">
                                        <input
                                            v-model="
                                                critereForm[critere.id].nom
                                            "
                                            type="text"
                                            :name="critere.nom"
                                            :id="critere.nom"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
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
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un critère:
                        </h3>
                        <button
                            type="button"
                            v-if="!showCreateCritereForm"
                            @click="toggleCreateCritereForm"
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-md transition-all duration-200 ease-in-out hover:border-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 active:bg-indigo-600"
                        >
                            Créer un nouveau critère
                        </button>
                        <form
                            v-if="showCreateCritereForm"
                            class="flex w-full flex-col items-start space-y-4 px-4"
                            @submit.prevent="createCritere"
                        >
                            <div class="mt-1 flex w-full flex-col rounded-md">
                                <InputLabel for="critere_nom" value="Nom:" />
                                <TextInput
                                    v-model="createCritereForm.nom"
                                    type="text"
                                    name="critere_nom"
                                    id="critere_nom"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <InputError
                                    v-if="createCritereForm.errors.nom"
                                    class="text-xs text-red-500"
                                    :message="createCritereForm.errors.nom"
                                />
                            </div>

                            <div
                                class="flex w-full items-center justify-between"
                            >
                                <button
                                    :disabled="createCritereForm.processing"
                                    :class="{
                                        'opacity-25':
                                            createCritereForm.processing,
                                    }"
                                    class="flex w-auto max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                                    type="submit"
                                >
                                    <LoadingSVG
                                        v-if="createCritereForm.processing"
                                    />
                                    Enregistrer
                                </button>

                                <button
                                    class="rounded border border-gray-300 bg-white px-4 py-2 text-center text-base font-medium text-gray-600 shadow-sm"
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
            <div
                class="flex w-full flex-col items-start justify-center space-y-4 py-4"
            >
                <h3
                    class="mb-4 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                >
                    Les types de champs:
                </h3>
                <ul
                    class="max-w-3xl list-inside list-disc space-y-2 py-4 text-sm text-slate-600 marker:text-indigo-600"
                >
                    <li
                        v-for="champ in type_champs"
                        :key="champ.id"
                        class="text-base text-slate-600"
                    >
                        {{ champ.type }}:
                        <span
                            v-if="champ.criterable"
                            class="text-sm text-blue-500"
                        >
                            Module criterable</span
                        ><span v-else class="text-sm text-blue-500">
                            Module informationnel (non criterable)</span
                        >
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
