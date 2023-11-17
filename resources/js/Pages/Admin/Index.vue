<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    ChevronLeftIcon,
    MagnifyingGlassIcon,
    TrashIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const CreateInfoBase = defineAsyncComponent(() =>
    import("@/Components/Disciplines/CreateInfoBase.vue")
);
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const props = defineProps({
    listDisciplines: Object,
    categories: Object,
    structures: Object,
    errors: Object,
    users: Object,
    criteres: Object,
    can: Object,
});

const discipline = ref(null);

const displayCreateDisciplineForm = ref(false);

const showCreateDisciplineForm = () => {
    return (displayCreateDisciplineForm.value =
        !displayCreateDisciplineForm.value);
};

const createInfoBaseForm = useForm({
    name: null,
    description: null,
    theme: "light",
    remember: true,
});

const submitCreateInfoBase = () => {
    router.post(
        route("disciplines.create"),
        {
            name: createInfoBaseForm.name,
            description: createInfoBaseForm.description,
            theme: createInfoBaseForm.theme,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                createInfoBaseForm.reset();
                showCreateDisciplineForm();
            },
        }
    );
};

const critereForm = ref({});

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
</script>
<template>
    <Head title="Gestion du site" :description="'Administration du site.'" />
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
                    Panel d'administration
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des disciplines.
            </h2>

            <div
                class="flex w-full flex-col items-start justify-between gap-x-4 space-y-4"
            >
                <AutocompleteDisciplineNav
                    :disciplines="listDisciplines"
                    v-model="discipline"
                />
                <template v-if="discipline" class="w-full md:w-auto">
                    <Link
                        :href="route('admin.disciplines.edit', discipline)"
                        class="group inline-flex w-full max-w-md items-center justify-center rounded border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-600 shadow-sm hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2"
                    >
                        <span class="mr-4 inline-flex group-hover:text-white"
                            >Gérer {{ discipline }}</span
                        >
                        <MagnifyingGlassIcon
                            class="h-5 w-5 text-indigo-500 group-hover:text-white"
                        />
                    </Link>
                </template>

                <template
                    class="flex w-full flex-col items-end justify-center gap-x-4 space-y-2 md:flex-row md:space-y-0"
                    v-if="!discipline"
                >
                    <button
                        v-if="!displayCreateDisciplineForm"
                        @click="showCreateDisciplineForm"
                        type="button"
                        class="inline-flex w-full max-w-md justify-center rounded border border-transparent bg-indigo-600 px-4 py-2.5 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Créer une nouvelle discipline
                    </button>
                    <form
                        v-if="displayCreateDisciplineForm"
                        @submit.prevent="submitCreateInfoBase"
                        class="w-full max-w-md space-y-4"
                    >
                        <CreateInfoBase
                            :errors="errors"
                            v-model:name="createInfoBaseForm.name"
                            v-model:description="createInfoBaseForm.description"
                            v-model:theme="createInfoBaseForm.theme"
                        />

                        <div class="flex max-w-md space-x-2">
                            <button
                                @click="showCreateDisciplineForm"
                                type="button"
                                class="inline-flex w-full justify-center rounded border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 focus:ring-offset-2"
                            >
                                Annuler
                            </button>
                            <button
                                :disabled="createInfoBaseForm.processing"
                                type="submit"
                                class="inline-flex w-full justify-center rounded border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <LoadingSVG
                                    v-if="createInfoBaseForm.processing"
                                />
                                Créer la discipline
                            </button>
                        </div>
                    </form>
                </template>
            </div>
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des structures
            </h2>
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les structures existantes:
                        </h3>
                        <div
                            class="flex flex-wrap items-stretch justify-center gap-4"
                        >
                            <div
                                v-for="structure in structures"
                                :key="structures.id"
                            >
                                <Link
                                    class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                    :href="
                                        route('structures.edit', structure.slug)
                                    "
                                >
                                    {{ structure.name }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un structure:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            :href="route('structures.create')"
                            >Créer une nouvelle structure</Link
                        >
                    </div>
                </div>
            </div>
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des utilisateurs
            </h2>
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les utilisateurs existants:
                        </h3>
                        <div
                            class="flex flex-wrap items-stretch justify-center gap-4"
                        >
                            <Link
                                v-for="user in users.data"
                                :key="user.id"
                                class="flex w-auto flex-col items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                :href="route('profile.edit', user)"
                            >
                                {{ user.name }}
                                <span class="text-xs">{{ user.email }}</span>
                            </Link>

                            <Pagination :links="users.links" />
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un utilisateur:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            :href="route('register')"
                            >Créer une nouvel utilisateur</Link
                        >
                    </div>
                </div>
            </div>

            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des critères
            </h2>
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les critères existants:
                        </h3>
                        <ul
                            class="max-w-sm list-inside list-disc space-y-2 py-4 text-sm text-slate-600 marker:text-indigo-600"
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
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un critère:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            :href="route('register')"
                            >Créer une nouveau critère</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
