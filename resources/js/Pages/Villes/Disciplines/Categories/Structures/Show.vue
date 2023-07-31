<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import TabsComponent from "@/Components/TabsComponent.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    familles: Object,
    category: Object,
    categories: Object,
    allStructureTypes: Object,
    city: Object,
    citiesAround: Object,
    structure: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
});

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>

<template>
    <Head
        :title="`${category.nom_categorie_client} de ${discipline.name} à ${city.ville}.`"
        :description="`${category.nom_categorie_client} de ${discipline.name} à ${city.ville}. Choisissez parmi plus de ${city.structures_count} structures pour pratiquer une activité sportive ou de loisirs à ${city.ville}`"
    />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-center text-xl font-semibold uppercase leading-tight tracking-widest text-gray-800"
                >
                    {{ category.nom_categorie_client }}
                    <span class="lowercase">de</span> {{ discipline.name }}
                    <span class="lowercase">à</span>
                    {{ formatCityName(city.ville) }}
                    <span class="text-sm text-gray-600"
                        >({{ city.code_postal }})
                    </span>
                </h1>
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
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
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
                                :href="route('villes.show', city.id)"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ formatCityName(city.ville) }}
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
                                    route('villes.disciplines.show', {
                                        city: city.id,
                                        discipline: discipline.slug,
                                    })
                                "
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ discipline.name }}
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
                                        'villes.disciplines.categories.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            category: category.id,
                                        }
                                    )
                                "
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ category.nom_categorie_client }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                <span class="font-semibold text-gray-800"
                    >{{ category.nom_categorie_client }}
                </span>
                de
                <span class="font-semibold text-gray-800"
                    >{{ discipline.name }}
                </span>
                à
                <span class="font-semibold text-gray-800">{{
                    formatCityName(city.ville)
                }}</span>
                en France. <br />
                Consultez la liste des
                <span
                    v-if="city.structures_count > 1"
                    class="font-semibold text-gray-800"
                    >{{ city.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer du
                <span class="font-semibold text-gray-800">{{
                    discipline.name
                }}</span>
                à
                <span class="font-semibold text-gray-800">{{
                    formatCityName(city.ville)
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>
        <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
            <div class="flex items-center justify-around space-x-4">
                <div class="my-4 w-full">
                    <div class="mt-1">
                        <nav
                            class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                        >
                            <Link
                                v-for="categorie in categories"
                                :key="categorie.id"
                                :href="
                                    route(
                                        'villes.disciplines.categories.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            category: categorie.id,
                                        }
                                    )
                                "
                                :class="[
                                    'w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                    route().current(
                                        'villes.disciplines.categories.show'
                                    ) && category.id === categorie.id
                                        ? 'bg-green-600 text-white'
                                        : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                ]"
                            >
                                {{ categorie.nom_categorie_client }}
                            </Link>
                            <Link
                                v-for="structureType in allStructureTypes"
                                :key="structureType.id"
                                :href="
                                    route(
                                        'villes.disciplines.structuretypes.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            structuretype: structureType.id,
                                        }
                                    )
                                "
                                class="w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 hover:bg-green-600 hover:text-white focus:bg-green-600 focus:text-white focus:outline-none focus:ring-2"
                            >
                                {{ structureType.name }}
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="structure">
            <section
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div
                    class="mx-auto flex w-full flex-col-reverse justify-between rounded-lg bg-white px-4 shadow-md shadow-sky-700 md:flex-row md:space-x-6"
                >
                    <div class="w-full md:w-1/3">
                        <div
                            class="my-4 flex items-center justify-center md:mb-8"
                        >
                            <h3 class="text-base font-semibold uppercase">
                                Coordonnées de la structure
                            </h3>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <h3 class="text-base">Localisation</h3>
                            <p class="py-4 font-semibold">
                                {{ structure.adresses[0].city }},
                                {{ structure.adresses[0].zip_code }}
                            </p>
                            <LeafletMap :item="structure" />
                        </div>

                        <div class="my-4 space-y-6">
                            <h3 class="py-4 text-base font-semibold">
                                Activité proposé par:
                            </h3>
                            <p class="text-base font-semibold text-gray-700">
                                <UserIcon class="inline-block h-4 w-4" />
                                {{ structure.creator.name }}
                            </p>
                            <p
                                v-if="structure.website"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Site web:
                                <a
                                    :href="structure.website"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.website }}
                                </a>
                            </p>
                            <p
                                v-if="structure.facebook"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Facebook:
                                <a
                                    :href="structure.facebook"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.facebook }}
                                </a>
                            </p>
                            <p
                                v-if="structure.instagram"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Instagram:
                                <a
                                    :href="structure.instagram"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.instagram }}
                                </a>
                            </p>
                            <p
                                v-if="structure.youtube"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Youtube:
                                <a
                                    :href="structure.youtube"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.youtube }}
                                </a>
                            </p>

                            <p
                                v-if="structure.phone1"
                                class="text-base font-medium text-gray-700"
                            >
                                <PhoneIcon class="inline-block h-4 w-4" />
                                {{ structure.phone1 }}
                            </p>
                            <p
                                v-if="structure.email"
                                class="text-base font-medium text-gray-700"
                            >
                                <AtSymbolIcon class="inline-block h-4 w-4" />
                                {{ structure.email }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full space-y-4 md:w-2/3">
                        <div class="relative mb-4 md:mb-6">
                            <p
                                class="mb-2 text-lg font-medium uppercase tracking-wider text-gray-500"
                            >
                                {{ structure.structuretype.name }}
                            </p>
                            <div
                                class="my-4 flex items-center justify-start space-x-4"
                            >
                                <img
                                    v-if="structure.logo"
                                    alt="img"
                                    :src="structure.logo"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                                <img
                                    v-else
                                    alt="img"
                                    src="https://via.placeholder.com/360x360.png/151f32?text=LOGO"
                                    class="h-20 w-20 shrink-0 rounded-full object-cover object-center"
                                />
                                <h2
                                    class="inline-block text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                                >
                                    {{ structure.name }} - {{ discipline.name }}
                                </h2>
                            </div>
                        </div>
                        <TabsComponent :structure="structure"></TabsComponent>
                        <div class="space-y-4">
                            <CitiesAround
                                v-if="citiesAround.length > 0"
                                :citiesAround="citiesAround"
                            />
                            <DisciplinesSimilaires
                                v-if="disciplinesSimilaires.length > 0"
                                :disciplinesSimilaires="disciplinesSimilaires"
                            />
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
