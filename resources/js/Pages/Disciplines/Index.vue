<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { defineAsyncComponent } from "vue";
import TextInput from "@/Components/TextInput.vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";

let props = defineProps({
    disciplines: Object,
    filters: Object,
    structuresCount: Number,
    familles: Object,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

let search = ref(props.filters.search);

function resetSearch() {
    search.value = "";
}

watch(
    search,
    debounce(function (value) {
        router.get(
            "/disciplines",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);
</script>

<template>
    <Head title="Disciplines" :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            disciplines.total +
            ' disciplines différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        " />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div class="my-4 flex w-full flex-col items-center justify-center space-y-2">
                <h1 class="text-xl font-semibold leading-tight tracking-widest text-gray-800 text-center">
                    Les disciplines et activités
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600">
                        <li class="flex items-center">
                            <Link preserve-scroll :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>

                            <span class="ms-1.5 text-xs font-medium">
                                Accueil
                            </span>
                            </Link>
                        </li>

                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180">
                            </span>

                            <Link preserve-scroll :href="route('disciplines.index')"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900">
                            Disciplines
                            </Link>
                        </li>

                    </ol>
                </nav>
            </div>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Trouvez un club de sport ou un cours collectif parmi plus de
                <span class="font-semibold text-gray-800">
                    {{ structuresCount }}
                </span>
                structures, dans plus de
                <span class="font-semibold text-gray-800">
                    {{ disciplines.total }}
                </span>
                disciplines différentes.
                <br />
                Il y en a pour tous les gouts: sports collectifs, sports de
                montagne, sport de combats, danse, musique, ...
            </p>
        </template>

        <div class="py-12">
            <!-- search box -->
            <div class="mx-auto mb-8 mt-4 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row">
                <label for="search" value="Rechercher une discipline"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800">Rechercher une discipline:</label>

                <TextInput id="search" type="text"
                    class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                    v-model="search" placeholder="discipline..." />

                <button type="button" @click="resetSearch">
                    <svg class="my-3 ml-2 h-6 w-6 text-gray-400 hover:text-gray-700 lg:my-0 lg:h-8 lg:w-8"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8">
                <div class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <Link :href="route('disciplines.show', discipline.slug)" :active="
                            route().current('disciplines.show', discipline.slug)
                        " v-for="(discipline, index) in disciplines.data" :key="discipline.id" :index="index"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    <div>{{ discipline.name }}</div>
                    <div v-if="discipline.structures_count > 0" class="text-xs">
                        ({{ discipline.structures_count }}
                        <span v-if="discipline.structures_count > 1">structures</span>
                        <span v-else>structure</span>)
                    </div>
                    <div v-else class="text-xs">
                        (Pas encore de structure inscrite)
                    </div>
                    </Link>
                </div>

                <div class="flex justify-end p-10">
                    <Pagination :links="disciplines.links" />
                </div>
            </div>
        </div>
        <div ref="landmark"></div>
    </AppLayout>
</template>
