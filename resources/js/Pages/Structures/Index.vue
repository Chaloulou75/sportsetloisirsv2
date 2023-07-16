<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import StructureCard from "@/Components/Structures/StructureCard.vue";
import { MapPinIcon } from "@heroicons/vue/24/outline";

let props = defineProps({
    structures: Object,
    filters: Object,
    structuresCount: Number,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

let search = ref(props.filters.search);

function resetSearch() {
    search.value = "";
}

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

watch(
    search,
    debounce(function (value) {
        router.get(
            "/structures",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);
</script>

<template>
    <Head
        title="Structures"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            structures.total +
            ' structures différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Structures, clubs de sport et cours collectifs
            </h2>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Plus de
                <span class="font-semibold text-gray-800">{{
                    structures.total
                }}</span>
                structures référencées et prêtes à vous accueillir et vous
                accompagner dans la pratique de votre discipline favorite !
            </p>
        </template>

        <div class="py-12">
            <!-- search box -->
            <div
                class="mx-auto mb-8 mt-4 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row"
            >
                <label
                    for="search"
                    value="Rechercher une structure"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800"
                    >Rechercher une structure:</label
                >

                <TextInput
                    id="search"
                    type="text"
                    class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                    v-model="search"
                    placeholder="structure, discipline, ville..."
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
            <template v-if="structures.data.length > 0">
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                >
                    <div class="md:w-1/2">
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
                            />
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="structures.links" />
                        </div>
                    </div>
                    <LeafletMapMultiple
                        class="md:sticky md:top-2 md:w-1/2"
                        :structures="structures.data"
                        :hovered-structure="hoveredStructure"
                        :zoom="7"
                    />
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Il n'y a pas encore de structures inscrites pour cette
                        requète.
                    </p>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
