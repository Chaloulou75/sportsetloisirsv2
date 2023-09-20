<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    departement: Object,
    structures: Object,
    filters: Object,
});

const mapStructure = ref(null);
const mapIsVisible = useElementVisibility(mapStructure);
const listeStructure = ref(null);
const listeIsVisible = useElementVisibility(listeStructure);

const goToMap = () => {
    mapStructure.value.scrollIntoView({ behavior: "smooth" });
};

const goToListe = () => {
    listeStructure.value.scrollIntoView({ behavior: "smooth" });
};

const flattenedDisciplines = computed(() => {
    const uniqueDisciplines = new Map();
    props.structures.data.forEach((structure) => {
        structure.disciplines.forEach((discipline) => {
            const disciplineId = discipline.discipline_id;
            if (!uniqueDisciplines.has(disciplineId)) {
                uniqueDisciplines.set(disciplineId, discipline.discipline);
            }
        });
    });
    return Array.from(uniqueDisciplines.values());
});

const hoveredStructure = ref(null);

function showTooltip(structure) {
    hoveredStructure.value = structure.id;
}
function hideTooltip() {
    hoveredStructure.value = null;
}

const getUniqueActivitesDiscipline = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.discipline.name)) {
            uniqueNames.add(activite.discipline.name);
            return true;
        }
        return false;
    });
};

const getUniqueActivitesTitre = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.titre)) {
            uniqueNames.add(activite.titre);
            return true;
        }
        return false;
    });
};

let discipline = ref("");

watch(
    discipline,
    debounce(function (value) {
        router.get(
            "/departements",
            { discipline: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head
        :title="departement.departement"
        :description="
            'Envie de faire du sport ' +
            departement.prefixe +
            departement.departement +
            '? Choisissez parmi plus de ' +
            departement.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            departement.prefixe +
            departement.departement
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    <h1
                        class="border-b-2 border-slate-400 pb-2 text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                    >
                        {{ departement.departement }}
                    </h1>
                </template>
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
                                    :href="
                                        route(
                                            'departements.show',
                                            departement.id
                                        )
                                    "
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template v-if="departement.structures_count > 0">
            <div
                class="mx-auto max-w-full px-2 py-12 sm:px-6 md:space-x-4 lg:px-8"
            >
                <h3 class="mb-4 text-center font-semibold text-gray-600">
                    Les disciplines pratiquées {{ departement.prefixe }}
                    {{ departement.departement }}
                </h3>
                <div
                    class="flex w-full flex-col flex-wrap items-center justify-center gap-3 text-gray-700 md:flex-row"
                >
                    <div
                        v-for="discipline in flattenedDisciplines"
                        :key="discipline.id"
                    >
                        <Link
                            :href="
                                route('departements.disciplines.show', {
                                    departement: departement.id,
                                    discipline: discipline.slug,
                                })
                            "
                            class="inline-block w-48 rounded border border-gray-600 px-4 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ discipline.name }}
                        </Link>
                    </div>
                    <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div>
                </div>
            </div>
            <div
                class="mx-auto min-h-screen max-w-full px-2 py-12 sm:px-6 lg:px-8"
            >
                <div
                    class="relative mx-auto flex min-h-screen max-w-full flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                >
                    <div ref="listeStructure" class="md:w-1/2">
                        <div
                            class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2"
                        >
                            <StructureCard
                                v-for="(structure, index) in structures.data"
                                :key="structure.id"
                                :index="index"
                                :structure="structure"
                                @mouseover="showTooltip(structure)"
                                @mouseout="hideTooltip()"
                                :link="
                                    route('structures.show', {
                                        structure: structure.slug,
                                    })
                                "
                                :data="{
                                    departement: departement.id,
                                }"
                            />
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="structures.links" />
                        </div>
                        <button
                            v-if="!mapIsVisible && listeIsVisible"
                            type="button"
                            class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                            @click="goToMap"
                        >
                            <MapIcon class="mr-2 h-5 w-5" />
                            Carte
                        </button>
                    </div>
                    <div class="space-y-4 md:sticky md:w-1/2">
                        <div ref="mapStructure">
                            <LeafletMapMultiple
                                class="md:top-2"
                                :structures="structures.data"
                                :hovered-structure="hoveredStructure"
                                :zoom="12"
                            />
                            <button
                                v-if="mapIsVisible"
                                type="button"
                                class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                                @click="goToListe"
                            >
                                <ListBulletIcon class="mr-2 h-5 w-5" />
                                Liste
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Dommage, il n'y a pas encore de structures inscrites
                        {{ departement.prefixe }}
                        <span class="font-semibold text-gray-800">{{
                            departement.departement
                        }}</span>
                    </p>
                </div>
            </div>
        </template>
    </ResultLayout>
</template>
