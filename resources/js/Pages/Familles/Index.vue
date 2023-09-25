<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";

defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    disciplinesCount: Number,
    famillesCount: Number,
    structuresCount: Number,
});
</script>

<template>
    <Head
        title="Familles d'activités"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            disciplinesCount +
            ' disciplines différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <ResultLayout :list-disciplines="listDisciplines" :all-cities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />

            <div class="mx-auto my-6 max-w-full px-2 py-4 md:px-4 lg:px-6">
                <div
                    class="mx-auto my-2 flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:w-1/3"
                >
                    <h1
                        class="border-b-2 border-slate-400 pb-2 text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                    >
                        Rubriques
                    </h1>
                </div>
                <div
                    class="mx-auto flex w-full flex-col items-center justify-center space-y-2 bg-gray-100/60 px-2 py-2 md:w-1/4"
                >
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
                                    :href="route('familles.index')"
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    Rubriques
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </template>

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('familles.show', famille.slug)"
                        :active="route().current('familles.show', famille.slug)"
                        v-for="famille in familles"
                        :key="famille.id"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        {{ famille.name }}
                    </Link>
                </div>
            </div>
        </div>
    </ResultLayout>
</template>
