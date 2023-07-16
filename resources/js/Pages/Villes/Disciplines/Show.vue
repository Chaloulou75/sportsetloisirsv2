<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";


let props = defineProps({
    city: Object,
    citiesAround: Object,
    structures: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
});

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

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

</script>

<template>
    <Head
        :title="city.ville"
        :description="
            'Envie de faire du ' +
            discipline.name +
            ' à ' +
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
                {{ discipline.name }} à {{ city.ville }} <span class="text-sm text-gray-600"
                    >({{ city.code_postal }})
                </span>
                <span class="text-xs italic text-gray-600"
                    >({{ city.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                Trouvez un club de {{ discipline.name }} à
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
                2 clics ! Pratiquer du {{ discipline.name }} à {{ city.ville }} n'a jamais été
                aussi simple!
            </p>
        </template>

        <template v-if="structures.length > 0">
            <div
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8 py-12"
            >
                <div class="md:w-1/2">
                    <div
                        class="grid grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2 h-auto"
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
                </div>
                <div class="md:w-1/2 md:sticky space-y-4">
                    <LeafletMapMultiple
                        class="md:top-2"
                        :structures="structures"
                        :hovered-structure="hoveredStructure"
                        :zoom="11"
                    />
                    <DisciplinesSimilaires :disciplinesSimilaires="disciplinesSimilaires" />
                    <CitiesAround :citiesAround="citiesAround" />
                </div>
            </div>
        </template>
        <template v-else>
            <div
                class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8 py-12"
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
