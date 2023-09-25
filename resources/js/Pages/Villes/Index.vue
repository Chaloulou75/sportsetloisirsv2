<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/TextInput.vue";
import { HomeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    cities: Object,
    familles: Object,
    filters: Object,
    structuresCount: Number,
    produitsCount: Number,
    listDisciplines: Object,
    allCities: Object,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

let search = ref(props.filters.search);
function resetSearch() {
    search.value = "";
}

watch(
    search,
    debounce(function (value) {
        router.get(
            "/villes",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head
        title="Villes"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            cities.total +
            ' disciplines différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title> Localités </template>
                <template v-slot:ariane>
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
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
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
                                    :href="route('villes.index')"
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    Localités
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <div class="py-6 md:py-12">
            <!-- search box -->
            <div
                class="mx-auto mb-8 mt-4 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row"
            >
                <label
                    for="search"
                    value="Rechercher dans votre ville:"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800"
                    >Rechercher une ville:</label
                >

                <TextInput
                    id="search"
                    type="text"
                    class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                    v-model="search"
                    placeholder="ville..."
                />

                <button type="button" @click="resetSearch">
                    <svg
                        class="my-3 ml-2 h-6 w-6 text-gray-400 hover:text-gray-700 lg:my-0 lg:h-8 lg:w-8"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
            <div class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('villes.show', city.id)"
                        :active="route().current('villes.show', city.id)"
                        v-for="(city, index) in cities.data"
                        :key="city.id"
                        :index="index"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        <div>
                            {{ formatCityName(city.ville) }}
                            <span class="text-xs"
                                >({{ city.code_postal }})</span
                            >
                        </div>
                        <div v-if="city.produits_count > 0" class="text-xs">
                            ({{ city.produits_count }}
                            <span v-if="city.produits_count > 1"
                                >activités</span
                            >
                            <span v-else>activité</span>)
                        </div>
                        <div v-else class="text-xs italic">
                            (Pas encore d'activité inscrite)
                        </div>
                    </Link>
                </div>
                <div class="flex justify-end p-10">
                    <Pagination :links="cities.links" />
                </div>
            </div>
        </div>
    </ResultLayout>
</template>
