<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import AutocompleteDiscipline from "@/Components/Home/AutocompleteDiscipline.vue";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";
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
    can: Object,
});

const discipline = ref(null);

const displayCreateDisciplineForm = ref(false);

const showCreateDisciplineForm = () => {
    return (displayCreateDisciplineForm.value =
        !displayCreateDisciplineForm.value);
};

const createInfoBaseForm = useForm({
    name: ref(null),
    description: ref(null),
    remember: true,
});

const submitCreateInfoBase = () => {
    router.post(
        route("disciplines.create"),
        {
            name: createInfoBaseForm.name,
            description: createInfoBaseForm.description,
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
</script>
<template>
    <Head title="Gestion du site" :description="'Administration du site.'" />
    <AppLayout>
        <template #header>
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-xl font-semibold leading-tight tracking-widest text-gray-800"
                >
                    Administration du site
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol
                        class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                    >
                        <li class="flex items-center">
                            <Link
                                preserve-scroll
                                :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
                                    Accueil
                                </span>
                            </Link>
                        </li>

                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                            >
                            </span>

                            <Link
                                preserve-scroll
                                :href="route('admin.index')"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                Admin
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Panel d'administration du site sports-et-loisirs.fr.
            </p>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des disciplines, catégories, critères.
            </h2>
            <div>
                <div
                    class="flex flex-col items-end justify-around space-y-6 md:flex-row md:justify-center md:space-x-6 md:space-y-0"
                >
                    <div
                        class="flex w-full flex-col items-end justify-center gap-x-4 md:w-2/3 md:flex-row"
                    >
                        <AutocompleteDiscipline
                            :disciplines="listDisciplines"
                            v-model="discipline"
                        />
                        <template v-if="discipline" class="w-full md:w-auto">
                            <Link
                                :href="route('admin.edit', discipline)"
                                class="group mb-0.5 flex w-full items-center justify-center rounded border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-600 shadow-sm hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2 md:w-auto"
                            >
                                <span
                                    class="mr-4 inline-block group-hover:text-white"
                                    >Gérer {{ discipline }}</span
                                >
                                <MagnifyingGlassIcon
                                    class="h-5 w-5 text-indigo-500 group-hover:text-white"
                                />
                                <span class="sr-only">Rechercher</span>
                            </Link>
                        </template>
                    </div>
                    <div class="w-full md:w-1/3">
                        <button
                            v-if="!displayCreateDisciplineForm"
                            @click="showCreateDisciplineForm"
                            type="button"
                            class="mb-0.5 inline-flex w-full justify-center rounded border border-transparent bg-indigo-600 px-4 py-2.5 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Créer une nouvelle discipline
                        </button>
                        <form
                            v-if="displayCreateDisciplineForm"
                            @submit.prevent="submitCreateInfoBase"
                            class="w-full space-y-4"
                        >
                            <CreateInfoBase
                                :errors="errors"
                                v-model:name="createInfoBaseForm.name"
                                v-model:description="
                                    createInfoBaseForm.description
                                "
                            />
                            <button
                                :disabled="createInfoBaseForm.processing"
                                type="submit"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Créer la discipline
                            </button>
                        </form>
                    </div>
                </div>
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
                            class="flex flex-wrap items-stretch justify-start gap-4"
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
        </div>
    </AppLayout>
</template>
