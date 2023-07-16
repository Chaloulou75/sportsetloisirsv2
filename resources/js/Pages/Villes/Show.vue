<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";

let props = defineProps({
    city: Object,
    citiesAround: Object,
    structures: Object,
    filters: Object,
});

let discipline = ref("");
function resetSearch() {
    discipline.value = "";
}

watch(
    discipline,
    debounce(function (value) {
        router.get(
            `/villes/${city.ville_formatee}`,
            { discipline: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

// const flattenedDisciplines = computed(() => {
//     return props.structures.flatMap((structure) => {
//         return structure.disciplines.map((discipline) => {
//             return discipline.discipline;
//         });
//     });
// });

const flattenedDisciplines = computed(() => {
    const uniqueDisciplines = new Map();
    props.structures.forEach((structure) => {
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

// const Pagination = defineAsyncComponent(() =>
//     import("@/Components/Pagination.vue")
// );
</script>

<template>
    <Head
        :title="city.ville"
        :description="
            'Envie de faire du sport à ' +
            city.ville +
            '? Choisissez parmi plus de ' +
            city.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            city.ville
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Les structures disponibles à {{ city.ville }}
                <span class="text-sm text-gray-600"
                    >({{ city.code_postal }})
                </span>
                <span class="text-xs italic text-gray-600"
                    >({{ city.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                Trouvez un club de sport à
                <span class="font-semibold text-gray-800"
                    >{{ city.ville }}
                </span>
                en France. <br />
                Consultez la liste des
                <span
                    v-if="city.structures_count > 1"
                    class="font-semibold text-gray-800"
                    >{{ city.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport à {{ city.ville }} n'a jamais été
                aussi simple!
            </p>
        </template>

        <template v-if="structures.length > 0">
            <div
                class="mx-auto max-w-full px-2 py-6 sm:px-6 md:space-x-4 lg:px-8"
            >
                <h3 class="mb-4 text-center font-semibold text-gray-600">
                    Les disciplines pratiquées à
                    <span class="text-indigo-700">{{ city.ville }}</span>
                    <span class="text-xs text-gray-600"
                        >({{ city.code_postal }})</span
                    >
                </h3>
                <div
                    class="flex w-full flex-col items-center justify-center space-y-2 text-gray-600 md:flex-row md:space-x-4 md:space-y-0"
                >
                    <div
                        v-for="discipline in flattenedDisciplines"
                        :key="discipline.id"
                    >
                        <Link
                            :href="
                                route('villes.disciplines.show', {
                                    city: city.id,
                                    discipline: discipline.slug,
                                })
                            "
                            class="inline-block rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ discipline.name }}
                        </Link>
                    </div>
                </div>
            </div>
            <div
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div class="md:w-1/2">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2"
                    >
                        <StructureCard
                            v-for="(structure, index) in structures"
                            :key="structure.id"
                            :index="index"
                            :structure="structure"
                            @mouseover="showTooltip(structure)"
                            @mouseout="hideTooltip()"
                        />
                    </div>
                    <!-- <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div> -->
                </div>
                <div class="space-y-4 md:sticky md:w-1/2">
                    <LeafletMapMultiple
                        class="md:top-2"
                        :structures="structures"
                        :hovered-structure="hoveredStructure"
                        :zoom="11"
                    />
                    <CitiesAround :citiesAround="citiesAround" />
                </div>
            </div>
        </template>
        <template v-else>
            <div
                class="mx-auto min-h-screen max-w-7xl px-2 py-12 sm:px-6 lg:px-8"
            >
                <p class="font-medium text-gray-700">
                    Dommage, il n'y a pas encore de structures inscrites à
                    <span class="font-semibold text-gray-800">{{
                        city.ville
                    }}</span>
                </p>
            </div>
        </template>
    </AppLayout>
</template>
