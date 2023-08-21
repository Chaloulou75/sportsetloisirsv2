<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import AutocompleteDiscipline from "@/Components/Home/AutocompleteDiscipline.vue";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    listDisciplines: Object,
    categories: Object,
    can: Object,
});

const discipline = ref(null);
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
                Page d'administration de sports-et-loisirs.fr. Disciplines et
                Catégories.
            </p>
        </template>

        <div class="py-6">
            <div class="px-2 md:px-6">
                <h2
                    class="text-center text-2xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4"
                >
                    Gestion par discipline
                </h2>

                <div class="flex items-end justify-center gap-x-6 py-4">
                    <AutocompleteDiscipline
                        :disciplines="listDisciplines"
                        v-model="discipline"
                    />
                    <template v-if="discipline" class="w-full md:w-auto">
                        <Link
                            :href="route('admin.edit', discipline)"
                            class="mb-0.5 flex w-full items-center justify-center rounded border border-gray-300 bg-white px-4 py-3 text-base font-medium text-gray-600 shadow-sm hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 md:w-auto"
                        >
                            <span class="mr-4 inline-block"
                                >Administrer {{ discipline }}</span
                            >
                            <MagnifyingGlassIcon class="h-5 w-5" />
                            <span class="sr-only">Rechercher</span>
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
