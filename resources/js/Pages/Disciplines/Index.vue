<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/Forms/TextInput.vue";
import DisciplineSmallCard from "@/Components/Disciplines/DisciplineSmallCard.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    disciplines: Object,
    filters: Object,
    structuresCount: Number,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
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
            route("disciplines.index"),
            { search: value },
            {
                preserveState: true,
                replace: true,
                only: ["disciplines"],
            }
        );
    }, 300)
);
</script>

<template>
    <Head :title="`Disciplines`">
        <meta
            head-key="description"
            name="description"
            :content="
                'Trouvez un club de sport ou un cours collectif parmi plus de ' +
                disciplines.total +
                ' disciplines différentes en France. Choisissez parmi ' +
                structuresCount +
                ' clubs sur notre site prêts à vous accueillir.'
            "
        />
    </Head>
    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Les disciplines </template>
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
                                    :href="route('disciplines.index')"
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    Disciplines
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
                    value="Rechercher une discipline"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800"
                    >Rechercher une discipline:</label
                >

                <TextInput
                    id="search"
                    type="text"
                    class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                    v-model="search"
                    placeholder="discipline..."
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
                    <DisciplineSmallCard
                        v-for="discipline in disciplines.data"
                        :key="discipline.id"
                        :discipline="discipline"
                        :link="route('disciplines.show', discipline.slug)"
                    />
                </div>

                <div class="flex justify-end p-10">
                    <Pagination
                        :links="disciplines.meta.links"
                        :only="['disciplines']"
                    />
                </div>
            </div>
        </div>
    </ResultLayout>
</template>
