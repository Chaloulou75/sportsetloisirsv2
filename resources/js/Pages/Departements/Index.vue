<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { debounce } from "lodash";
import { defineAsyncComponent } from "vue";
import TextInput from "@/Components/TextInput.vue";

let props = defineProps({
    departements: Object,
    filters: Object,
    structuresCount: Number,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const formatCityName = (departement) => {
    return (
        departement.charAt(0).toUpperCase() + departement.slice(1).toLowerCase()
    );
};

let search = ref(props.filters.search);

watch(
    search,
    debounce(function (value) {
        router.get(
            "/departements",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head
        title="Departements"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            departements.total +
            ' disciplines différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Departements
            </h2>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Trouvez un club de sport ou un cours collectif parmi plus de
                <span class="font-semibold text-gray-800">
                    {{ structuresCount }}
                </span>
                structures, dans plus de
                <span class="font-semibold text-gray-800">
                    {{ departements.total }}
                </span>
                départements différents.
                <br />
                Il y en a pour tous les gouts: sports collectifs, sports de
                montagne, sport de combats, danse, musique, ...
            </p>
        </template>

        <div class="py-12">
            <!-- search box -->
            <div
                class="mx-auto mt-4 mb-8 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row"
            >
                <label
                    for="search"
                    value="Rechercher dans votre departement:"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800"
                    >Rechercher un departement:</label
                >

                <TextInput
                    id="search"
                    type="text"
                    class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                    v-model="search"
                    placeholder="departement..."
                />

                <!-- <button type="button" @click="reset">
                    <svg
                        class="w-6 h-6 my-2 text-gray-300 hover:text-gray-200 lg:my-0 lg:h-8 lg:w-8"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button> -->
            </div>
            <div class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('departements.show', departement.numero)"
                        :active="
                            route().current(
                                'departements.show',
                                departement.numero
                            )
                        "
                        v-for="(departement, index) in departements.data"
                        :key="departement.id"
                        :index="index"
                        class="flex flex-col items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        <div>{{ formatCityName(departement.departement) }}</div>
                        <div
                            v-if="departement.structures_count > 0"
                            class="text-xs"
                        >
                            ({{ departement.structures_count }}
                            <span v-if="departement.structures_count > 1"
                                >structures</span
                            >
                            <span v-else>structure</span>)
                        </div>
                        <div v-else class="text-xs">
                            (Pas encore de structure inscrite)
                        </div>
                    </Link>
                </div>
                <div class="flex justify-end p-10">
                    <Pagination :links="departements.links" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>