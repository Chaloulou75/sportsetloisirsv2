<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    TrashIcon,
    ArrowPathIcon,
    ChevronLeftIcon,
} from "@heroicons/vue/24/outline";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

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

const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head title="Gestion des types de tarifs">
        <meta
            head-key="description"
            name="description"
            :content="`Administration des types de tarifs.`"
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
                    Gestion des types de tarifs
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div
                class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <h3
                        class="mb-4 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
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
                            class="w-full text-base text-slate-600"
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
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Create type de tarif -->
                <div
                    class="flex w-full flex-col items-center justify-center md:w-1/3"
                >
                    <h3
                        class="mb-4 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                    >
                        Créer un type de tarif:
                    </h3>
                    <button
                        type="button"
                        v-if="!showCreateTarifForm"
                        @click="toggleCreateTarifForm"
                        class="inline-flex w-auto items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-md transition-all duration-200 ease-in-out hover:border-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 active:bg-indigo-600"
                    >
                        Créer un type de tarif
                    </button>
                    <form
                        v-if="showCreateTarifForm"
                        class="flex w-full flex-col items-start space-y-4 px-4"
                        @submit.prevent="createTarif"
                    >
                        <div class="mt-1 flex w-full flex-col rounded-md">
                            <InputLabel for="tarif_type" value="Type:" />
                            <TextInput
                                v-model="createtarifForm.type"
                                type="text"
                                name="tarif_type"
                                id="tarif_type"
                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                placeholder=""
                                autocomplete="none"
                            />
                            <InputError
                                v-if="errors.createtarifForm"
                                class="text-xs text-red-500"
                                :message="errors.createtarifForm.type"
                            />
                        </div>

                        <div class="flex w-full items-center justify-between">
                            <button
                                :disabled="createtarifForm.processing"
                                :class="{
                                    'opacity-25': createtarifForm.processing,
                                }"
                                class="flex w-auto max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                                type="submit"
                            >
                                <LoadingSVG v-if="createtarifForm.processing" />
                                Enregistrer
                            </button>
                            <button
                                class="rounded border border-gray-300 bg-white px-4 py-2 text-center text-base font-medium text-gray-600 shadow-sm"
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
