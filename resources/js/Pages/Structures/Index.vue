<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { debounce } from "lodash";
import { defineAsyncComponent } from "vue";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import {
    MapPinIcon,
} from "@heroicons/vue/24/outline";

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
                    placeholder="structure, club, rubrique, ville..."
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
                            class="grid h-auto grid-cols-1 place-items-stretch gap-4 md:grid-cols-2"
                        >
                            <Link
                                v-for="(structure, index) in structures.data"
                                :key="structure.id"
                                :index="index"
                                :href="route('structures.show', structure.slug)"
                                :active="
                                    route().current(
                                        'structures.show',
                                        structure.slug
                                    )
                                "
                                class="block rounded-lg p-4 shadow-sm shadow-indigo-100"
                                @mouseover="showTooltip(structure)"
                                @mouseout="hideTooltip()"
                                >
                                <img
                                    alt="Home"
                                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                                    class="h-56 w-full rounded-md object-cover"
                                />

                                <div class="mt-2">
                                    <dl>
                                        <p
                                            class="text-sm font-medium uppercase tracking-widest text-pink-500"
                                        >
                                            {{ structure.structuretype.name }}
                                        </p>
                                        <span
                                            class="font-semibold text-sm"
                                            v-for="(activite, index) in getUniqueActivitesTitre(structure.activites)"
                                            :key="activite.id"
                                        >
                                            {{ activite.titre }}
                                            <span v-if="index < getUniqueActivitesTitre(structure.activites).length - 1"> | </span>
                                        </span>
                                    <div class="py-1.5">
                                        <dt class="sr-only">tarif</dt>

                                        <span
                                            v-for="(tarif, index) in structure.tarifs"
                                            :key="tarif.id"
                                        >
                                            <dd class="text-sm text-gray-500">{{ tarif.tarif_type.type }}: <span class="font-semibold">{{ tarif.amount }} €</span> </dd>
                                            <span v-if="index < structure.tarifs.length - 1"> | </span>

                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <dt class="sr-only">Ville</dt>
                                        <MapPinIcon class="mr-1 h-5 w-5 text-gray-600" />
                                        <dd class="font-medium text-sm">{{ structure.city }} ({{ structure.zip_code }})</dd>
                                    </div>
                                    </dl>

                                    <div class="mt-6 flex items-center gap-8 text-xs">
                                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                            <svg
                                            class="h-4 w-4 text-indigo-700"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
                                            />
                                            </svg>

                                            <div class="mt-1.5 sm:mt-0">
                                            <p class="text-gray-500">Parking</p>

                                            <p class="font-medium">2 spaces</p>
                                            </div>
                                        </div>

                                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                            <svg
                                            class="h-4 w-4 text-indigo-700"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                            />
                                            </svg>

                                            <div class="mt-1.5 sm:mt-0">
                                            <p class="text-gray-500">Bathroom</p>

                                            <p class="font-medium">2 rooms</p>
                                            </div>
                                        </div>

                                        <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                                            <svg
                                            class="h-4 w-4 text-indigo-700"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                                            />
                                            </svg>

                                            <div class="mt-1.5 sm:mt-0">
                                            <p class="text-gray-500">Bedroom</p>

                                            <p class="font-medium">4 rooms</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                            <Link
                                v-for="(structure, index) in structures.data"
                                :key="structure.id"
                                :index="index"
                                :href="route('structures.show', structure.slug)"
                                :active="
                                    route().current(
                                        'structures.show',
                                        structure.slug
                                    )
                                "
                                class="group relative block bg-black"
                            >
                                <img
                                    alt="logo"
                                    :src="structure.logo"
                                    class="absolute inset-0 h-full w-full object-cover opacity-75 transition-opacity group-hover:opacity-50"
                                />
                                <!-- <img
                                    v-else
                                    alt="photo"
                                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                                    class="absolute inset-0 object-cover w-full h-full transition-opacity opacity-75 group-hover:opacity-50"
                                /> -->

                                <div
                                    @mouseover="showTooltip(structure)"
                                    @mouseout="hideTooltip()"
                                    class="relative p-4 sm:p-6 lg:p-8"
                                >
                                    <p
                                        class="text-sm font-medium uppercase tracking-widest text-pink-500"
                                    >
                                        {{ structure.structuretype.name }}
                                    </p>

                                    <p
                                        class="text-xl font-bold text-white sm:text-2xl"
                                    >
                                        {{ structure.name }}
                                    </p>

                                    <div class="mt-32 sm:mt-48 lg:mt-64">
                                        <div
                                            class="translate-y-8 transform opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100"
                                        >
                                            <p
                                                class="mb-3 line-clamp-3 text-base text-white"
                                            >
                                                <span
                                                    class="font-semibold"
                                                    v-for="(activite, index) in getUniqueActivitesDiscipline(structure.activites)"
                                                    :key="activite.id"
                                                >
                                                    {{ activite.discipline.name }}
                                                    <span v-if="index < getUniqueActivitesDiscipline(structure.activites).length - 1"> | </span>
                                                </span>
                                            </p>
                                            <p
                                                class="mb-3 line-clamp-3 text-base text-white"
                                            >
                                                {{
                                                    structure.presentation_courte
                                                }}
                                            </p>
                                            <p class="text-base text-white">
                                                {{ structure.city }} ({{
                                                    structure.zip_code
                                                }})
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="structures.links" />
                        </div>
                    </div>
                    <LeafletMapMultiple
                        class="md:sticky md:top-2 md:w-1/2"
                        :structures="structures.data"
                        :hovered-structure="hoveredStructure"
                    />
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Il n'y a pas encore de structures inscrites
                        pour cette requète.
                    </p>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
