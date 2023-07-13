<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

let props = defineProps({
    departement: Object,
    structures: Object,
    filters: Object,
});

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

// const Pagination = defineAsyncComponent(() =>
//     import("@/Components/Pagination.vue")
// );
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

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Les structures disponibles {{ departement.prefixe }}
                {{ departement.departement }}
                <span class="text-xs italic text-gray-600"
                    >({{ departement.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                Trouvez un club de sport {{ departement.prefixe }}
                <span class="font-semibold text-gray-800"
                    >{{ departement.departement }}
                </span>
                en France. <br />
                Consultez la liste des
                <span
                    v-if="departement.structures_count > 0"
                    class="font-semibold text-gray-800"
                    >{{ departement.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport {{ departement.prefixe }}
                {{ departement.departement }} n'a jamais été aussi simple!
            </p>
        </template>

        <template v-if="departement.structures_count > 0">
            <div class="mx-auto max-w-full px-2 sm:px-6 md:space-x-4 lg:px-8 py-6">
                <h3 class="text-center text-gray-600 font-semibold mb-4">Les disciplines pratiquées {{ departement.prefixe }} {{ departement.departement }}</h3>
                <div class="text-gray-600 w-full flex flex-col md:flex-row justify-center md:space-x-4 space-y-2 md:space-y-0 items-center">
                    <div v-for="discipline in flattenedDisciplines" :key="discipline.id">
                        <Link :href="route('disciplines.show', discipline.slug)"
                                :active="
                                    route().current(
                                        'disciplines.show',
                                        discipline.slug
                                    )"
                                class="inline-block rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 hover:bg-green-500 hover:text-white focus:outline-none focus:ring active:bg-indigo-500 shadow-sm hover:shadow-lg"
                            >
                            {{ discipline.name }}
                        </Link>
                    </div>
                </div>
            </div>
            <div
                class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8 py-12"
            >
                <div
                    class="mx-auto flex min-h-screen max-w-7xl flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                >
                    <div class="md:w-1/2">
                        <div class="grid grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2 h-auto">
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
                            <Pagination :links="city.structures.links" />
                        </div> -->
                    </div>
                    <div class="md:w-1/2 md:sticky space-y-4">
                        <LeafletMapMultiple class="md:top-2"
                            :structures="structures"
                            :hovered-structure="hoveredStructure"
                            :zoom="11"
                        />

                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8"
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
    </AppLayout>
</template>
