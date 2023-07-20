<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

let props = defineProps({
    familles: Object,
    departement: Object,
    structures: Object,
    filters: Object,
});

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
    <Head :title="departement.departement" :description="
            'Envie de faire du sport ' +
            departement.prefixe +
            departement.departement +
            '? Choisissez parmi plus de ' +
            departement.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            departement.prefixe +
            departement.departement
        " />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div class="my-4 flex w-full flex-col items-center justify-center space-y-2">
                <p class="text-base font-semibold text-gray-700">Sports et loisirs à </p>
                <h1 class="text-xl font-semibold leading-tight tracking-widest text-gray-800">
                    {{ departement.prefixe }} {{ departement.departement }}
                    <span class="text-xs italic tracking-tight text-gray-600">({{ departement.view_count }} vues)
                    </span>
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

                            <Link preserve-scroll :href="route('departements.show', departement.id)"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900">
                            {{ departement.departement }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                Trouvez une activité {{ departement.prefixe }}
                <span class="font-semibold text-gray-800">{{ departement.departement }}
                </span>. <br />
                Consultez la liste des
                <span v-if="departement.structures_count > 1" class="font-semibold text-gray-800">{{
                    departement.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport {{ departement.prefixe }}
                {{ departement.departement }} n'a jamais été aussi simple!
            </p>

        </template>

        <template v-if="departement.structures_count > 0">
            <div class="mx-auto max-w-full px-2 py-6 sm:px-6 md:space-x-4 lg:px-8">
                <h3 class="mb-4 text-center font-semibold text-gray-600">
                    Les disciplines pratiquées {{ departement.prefixe }}
                    {{ departement.departement }}
                </h3>
                <div
                    class="flex w-full flex-col items-center justify-center space-y-2 text-gray-600 md:flex-row md:space-x-4 md:space-y-0">
                    <div v-for="discipline in flattenedDisciplines" :key="discipline.id">
                        <Link :href="route('departements.disciplines.show', {
                                    departement: departement.id,
                                    discipline: discipline.slug,
                                })"
                            class="inline-block rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                        {{ discipline.name }}
                        </Link>
                    </div>
                    <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div>
                </div>
            </div>
            <div class="mx-auto min-h-screen max-w-full px-2 py-12 sm:px-6 lg:px-8">
                <div class="mx-auto flex min-h-screen max-w-full flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8">
                    <div class="md:w-1/2">
                        <div class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2">
                            <StructureCard v-for="(structure, index) in structures.data" :key="structure.id" :index="index"
                                :structure="structure" @mouseover="showTooltip(structure)" @mouseout="hideTooltip()" />
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="structures.links" />
                        </div>
                    </div>
                    <div class="space-y-4 md:sticky md:w-1/2">
                        <LeafletMapMultiple class="md:top-2" :structures="structures.data"
                            :hovered-structure="hoveredStructure" :zoom="11" />
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8">
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
