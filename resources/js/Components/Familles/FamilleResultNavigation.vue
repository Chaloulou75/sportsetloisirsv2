<script setup>
import { ref, computed, onMounted } from "vue";
import { HSCopyMarkup as HSStaticMethods } from "preline";
import { Link } from "@inertiajs/vue3";
import {
    MapPinIcon,
    ChevronRightIcon,
    ChevronDownIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    familles: Object,
    currentDiscipline: Object,
    currentDepartement: Object,
    currentCity: Object,
    citiesAround: Object,
    currentCategory: Object,
});

const hoveredFamille = ref(null);
const hoveredFamilleDisciplines = computed(() => {
    if (hoveredFamille.value) {
        return hoveredFamille.value.disciplines;
    }
    return [];
});

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

onMounted(() => {
    window.HSStaticMethods.autoInit();
});
</script>
<template>
    <header
        @mouseleave="hoveredFamille = null"
        class="z-50 flex w-full flex-wrap bg-gray-200/30 py-3 text-base md:flex-nowrap md:justify-start md:py-0"
    >
        <nav class="mx-auto w-full max-w-full" aria-label="Global">
            <div class="relative md:flex md:items-center md:justify-between">
                <div class="flex items-center justify-end md:hidden">
                    <div class="md:hidden">
                        <button
                            type="button"
                            class="hs-collapse-toggle flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-gray-800 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            data-hs-collapse="#navbar-collapse-with-animation"
                            aria-controls="navbar-collapse-with-animation"
                            aria-label="Toggle navigation"
                        >
                            <svg
                                class="h-4 w-4 flex-shrink-0 hs-collapse-open:hidden"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line x1="3" x2="21" y1="6" y2="6" />
                                <line x1="3" x2="21" y1="12" y2="12" />
                                <line x1="3" x2="21" y1="18" y2="18" />
                            </svg>
                            <svg
                                class="hidden h-4 w-4 flex-shrink-0 hs-collapse-open:block"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    id="navbar-collapse-with-animation"
                    class="hs-collapse hidden grow basis-full overflow-hidden transition-all duration-300 md:block"
                >
                    <div
                        class="max-h-[75vh] overflow-hidden overflow-y-auto [&::-webkit-scrollbar-thumb]:rounded-sm [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar]:w-2"
                    >
                        <div
                            class="mt-5 flex flex-col gap-x-0 divide-y divide-dashed divide-gray-200 md:mt-0 md:flex-row md:items-center md:justify-between md:gap-x-7 md:divide-y-0 md:divide-solid md:ps-7"
                        >
                            <div
                                class="flex flex-col gap-x-0 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-start md:gap-x-7 md:divide-y-0 md:divide-solid"
                            >
                                <div
                                    v-if="currentDepartement"
                                    class="hs-dropdown py-3 [--adaptive:none] [--strategy:static] md:py-4 md:[--strategy:absolute] md:[--trigger:hover]"
                                >
                                    <button
                                        type="button"
                                        class="group flex w-full items-center rounded px-2 md:bg-white md:py-2 md:font-semibold"
                                    >
                                        <MapPinIcon
                                            class="mr-2 h-4 w-4 flex-shrink-0"
                                        />
                                        {{
                                            formatCityName(
                                                currentDepartement.departement
                                            )
                                        }}
                                        <ChevronRightIcon
                                            class="ms-2 h-4 w-4 flex-shrink-0"
                                        />
                                    </button>
                                    <div
                                        class="hs-dropdown-menu start-0 top-full z-[1200] hidden w-full min-w-[15rem] rounded-lg bg-white py-2 opacity-0 transition-[opacity,margin] duration-[0.1ms] before:absolute before:-top-5 before:start-0 before:h-5 before:w-full hs-dropdown-open:opacity-100 md:p-4 md:shadow-2xl md:duration-[150ms]"
                                    >
                                        <span
                                            class="mb-2 text-xs font-semibold uppercase text-gray-800"
                                            >{{
                                                formatCityName(
                                                    currentDepartement.departement
                                                )
                                            }}</span
                                        >
                                        <div
                                            class="gap-4 md:grid md:grid-cols-2 lg:grid-cols-3"
                                        >
                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'departements.show',
                                                            {
                                                                departement:
                                                                    currentDepartement,
                                                            }
                                                        )
                                                    "
                                                    preserve-scroll
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                formatCityName(
                                                                    currentDepartement.departement
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            pratiquées dans
                                                            votre département.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>

                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="route('posts.index')"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Blog
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrir les
                                                            derniers articles en
                                                            ligne.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <circle
                                                            cx="12"
                                                            cy="12"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Disciplines
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Rechercher parmi
                                                            plus de 300
                                                            disciplines.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'structures.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                        />
                                                        <circle
                                                            cx="9"
                                                            cy="7"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                                                        />
                                                        <path
                                                            d="M16 3.13a4 4 0 0 1 0 7.75"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Structures et
                                                            activités
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrez les
                                                            structures et
                                                            activités près de
                                                            chez vous.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="currentCity"
                                    class="hs-dropdown py-3 [--adaptive:none] [--strategy:static] md:py-4 md:[--strategy:absolute] md:[--trigger:hover]"
                                >
                                    <button
                                        type="button"
                                        class="group flex w-full items-center rounded px-2 md:bg-white md:py-2 md:font-semibold"
                                    >
                                        <MapPinIcon
                                            class="mr-2 h-4 w-4 flex-shrink-0"
                                        />
                                        {{ formatCityName(currentCity.ville) }}
                                        <ChevronRightIcon
                                            class="ms-2 h-4 w-4 flex-shrink-0"
                                        />
                                    </button>
                                    <div
                                        class="hs-dropdown-menu start-0 top-full z-[1200] hidden w-full min-w-[15rem] rounded-lg bg-white py-2 opacity-0 transition-[opacity,margin] duration-[0.1ms] before:absolute before:-top-5 before:start-0 before:h-5 before:w-full hs-dropdown-open:opacity-100 md:p-4 md:shadow-2xl md:duration-[150ms]"
                                    >
                                        <span
                                            class="mb-2 text-xs font-semibold uppercase text-gray-800"
                                            >{{
                                                formatCityName(
                                                    currentCity.ville
                                                )
                                            }}</span
                                        >
                                        <div
                                            class="gap-4 md:grid md:grid-cols-2 lg:grid-cols-3"
                                        >
                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="
                                                        route('villes.show', {
                                                            city: currentCity,
                                                        })
                                                    "
                                                    preserve-scroll
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                formatCityName(
                                                                    currentCity.ville
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            pratiquées dans
                                                            votre ville.
                                                        </p>
                                                    </div>
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'departements.show',
                                                            {
                                                                departement:
                                                                    currentCity.city_departement,
                                                            }
                                                        )
                                                    "
                                                    preserve-scroll
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                formatCityName(
                                                                    currentCity
                                                                        .city_departement
                                                                        .departement
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            pratiquées dans
                                                            votre département.
                                                        </p>
                                                    </div>
                                                </Link>
                                                <Link
                                                    v-for="cityAround in citiesAround"
                                                    :key="cityAround.id"
                                                    :href="
                                                        route('villes.show', {
                                                            city: cityAround,
                                                        })
                                                    "
                                                    preserve-scroll
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                formatCityName(
                                                                    cityAround.ville
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            pratiquées aux
                                                            alentours.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>

                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="route('posts.index')"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Blog
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrir les
                                                            derniers articles en
                                                            ligne.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <circle
                                                            cx="12"
                                                            cy="12"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Disciplines
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Rechercher parmi
                                                            plus de 300
                                                            disciplines.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'structures.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                        />
                                                        <circle
                                                            cx="9"
                                                            cy="7"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                                                        />
                                                        <path
                                                            d="M16 3.13a4 4 0 0 1 0 7.75"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Structures et
                                                            activités
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrez les
                                                            structures et
                                                            activités près de
                                                            chez vous.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="currentDiscipline"
                                    class="hs-dropdown py-3 [--adaptive:none] [--strategy:static] md:py-4 md:[--strategy:absolute] md:[--trigger:hover]"
                                >
                                    <button
                                        type="button"
                                        :class="{
                                            'rounded px-2 text-gray-800 group-hover:text-black md:bg-white md:py-2':
                                                !currentCity &&
                                                !currentDepartement,
                                            'text-gray-100 group-hover:text-white':
                                                currentDiscipline?.theme ===
                                                'dark',
                                            'text-gray-800 group-hover:text-black':
                                                currentDiscipline?.theme ===
                                                    'light' ||
                                                !currentDiscipline,
                                        }"
                                        class="flex w-full items-center md:font-semibold"
                                    >
                                        {{ currentDiscipline.name }}
                                        <svg
                                            class="ms-2 h-4 w-4 flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>
                                    <div
                                        class="hs-dropdown-menu start-0 top-full z-[1200] hidden w-full min-w-[15rem] rounded-lg bg-white py-2 opacity-0 transition-[opacity,margin] duration-[0.1ms] before:absolute before:-top-5 before:start-0 before:h-5 before:w-full hs-dropdown-open:opacity-100 md:p-4 md:shadow-2xl md:duration-[150ms]"
                                    >
                                        <span
                                            class="mb-2 text-xs font-semibold uppercase text-gray-800"
                                            >{{ currentDiscipline.name }}</span
                                        >
                                        <div
                                            class="gap-4 md:grid md:grid-cols-2 lg:grid-cols-3"
                                        >
                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.show',
                                                            {
                                                                discipline:
                                                                    discipline,
                                                            }
                                                        )
                                                    "
                                                    preserve-scroll
                                                    v-for="discipline in currentDiscipline.disciplines_similaires"
                                                    :key="discipline.id"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                discipline.name
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            liées au
                                                            {{
                                                                discipline.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>

                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="route('posts.index')"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Blog
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrir les
                                                            derniers articles en
                                                            ligne.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <circle
                                                            cx="12"
                                                            cy="12"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Disciplines
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Rechercher parmi
                                                            plus de 300
                                                            disciplines.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'structures.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                        />
                                                        <circle
                                                            cx="9"
                                                            cy="7"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                                                        />
                                                        <path
                                                            d="M16 3.13a4 4 0 0 1 0 7.75"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Structures et
                                                            activités
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrez les
                                                            structures et
                                                            activités près de
                                                            chez vous.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex flex-1 flex-col gap-x-0 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-center md:gap-x-7 md:divide-y-0 md:divide-solid"
                            >
                                <div
                                    v-for="famille in familles"
                                    :key="famille.id"
                                    @mouseenter="hoveredFamille = famille"
                                    class="hs-dropdown px-4 py-3 [--adaptive:none] [--strategy:static] md:px-0 md:py-4 md:[--strategy:absolute] md:[--trigger:hover]"
                                >
                                    <button
                                        type="button"
                                        :class="{
                                            'text-gray-100 group-hover:text-white':
                                                currentDiscipline?.theme ===
                                                'dark',
                                            'text-gray-800 group-hover:text-black':
                                                currentDiscipline?.theme ===
                                                    'light' ||
                                                !currentDiscipline,
                                        }"
                                        class="flex w-full items-center font-semibold"
                                    >
                                        {{ famille.name }}
                                        <ChevronDownIcon
                                            class="ms-2 h-4 w-4 flex-shrink-0"
                                        />
                                    </button>

                                    <div
                                        class="hs-dropdown-menu start-0 top-full z-[1200] hidden w-full min-w-[15rem] rounded-lg bg-white py-2 opacity-0 transition-[opacity,margin] duration-[0.1ms] before:absolute before:-top-5 before:start-0 before:h-5 before:w-full hs-dropdown-open:opacity-100 md:p-4 md:shadow-2xl md:duration-[150ms]"
                                    >
                                        <span
                                            class="mb-2 text-xs font-semibold uppercase text-gray-800"
                                            >{{ famille.name }}</span
                                        >
                                        <div
                                            class="gap-4 md:grid md:grid-cols-2 lg:grid-cols-3"
                                        >
                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.show',
                                                            {
                                                                discipline:
                                                                    discipline,
                                                            }
                                                        )
                                                    "
                                                    preserve-scroll
                                                    v-for="discipline in hoveredFamilleDisciplines"
                                                    :key="discipline.id"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800 group-hover:text-gray-900"
                                                        >
                                                            {{
                                                                discipline.name
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Voir les activités
                                                            liées au
                                                            {{
                                                                discipline.name
                                                            }}
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>

                                            <div
                                                class="mx-1 flex flex-col md:mx-0"
                                            >
                                                <Link
                                                    :href="route('posts.index')"
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                        />
                                                        <path
                                                            d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Blog
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrir les
                                                            derniers articles en
                                                            ligne.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'disciplines.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <circle
                                                            cx="12"
                                                            cy="12"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Disciplines
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Rechercher parmi
                                                            plus de 300
                                                            disciplines.
                                                        </p>
                                                    </div>
                                                </Link>

                                                <Link
                                                    :href="
                                                        route(
                                                            'structures.index'
                                                        )
                                                    "
                                                    class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100"
                                                >
                                                    <svg
                                                        class="mt-1 h-5 w-5 flex-shrink-0"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path
                                                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                        />
                                                        <circle
                                                            cx="9"
                                                            cy="7"
                                                            r="4"
                                                        />
                                                        <path
                                                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                                                        />
                                                        <path
                                                            d="M16 3.13a4 4 0 0 1 0 7.75"
                                                        />
                                                    </svg>
                                                    <div class="grow">
                                                        <p
                                                            class="font-medium text-gray-800"
                                                        >
                                                            Structures et
                                                            activités
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-500 group-hover:text-gray-800"
                                                        >
                                                            Découvrez les
                                                            structures et
                                                            activités près de
                                                            chez vous.
                                                        </p>
                                                    </div>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
