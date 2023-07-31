<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import TabsComponent from "@/Components/TabsComponent.vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import ModalDeleteStructure from "@/Components/Modals/ModalDeleteStructure.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    structure: Object,
    logoUrl: String,
    disciplines: Object,
    categories: Object,
    familles: Object,
    can: Object,
});

const showModal = ref(false);

// watch(
//     () => discipline_id,
//     async (newDisciplineID) => {
//         axios
//             .get("/api/listdisciplines/" + newDisciplineID)
//             .then((response) => {
//                 categoriesList.value = response.data.data;
//             })
//             .catch((e) => {
//                 console.log(e);
//             });
//     }
// );
</script>

<template>
    <Head
        :title="
            structure.name +
            ' - ' +
            structure.adresses[0].city +
            ' - ' +
            structure.structuretype.name
        "
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
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
                    {{ structure.name }}
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
                                :href="route('structures.show', structure.slug)"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ structure.name }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex flex-col items-start justify-between md:flex-row">
                <div></div>
                <div
                    v-if="$page.props.auth.user"
                    class="mt-4 w-full md:mt-0 md:w-1/4"
                >
                    <div
                        v-if="can.update"
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
                            "
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Ajouter des activités</Link
                        >
                        <Link
                            :href="route('structures.edit', structure.slug)"
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Editer la structure</Link
                        >
                        <button
                            v-if="can.delete"
                            @click="showModal = true"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-red-400 hover:text-white hover:ring-2 hover:ring-red-400 hover:ring-offset-2 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Supprimer cette structure
                        </button>
                        <ModalDeleteStructure
                            :structure="structure"
                            :show="showModal"
                            @close="showModal = false"
                        />
                    </div>
                </div>
            </div>
        </template>

        <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
            <div
                class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 shadow-lg shadow-sky-700 md:flex-row md:space-x-6"
            >
                <div class="w-full md:w-1/3">
                    <div class="my-4 flex items-center justify-center md:mb-8">
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
                    <div class="my-4 space-y-3">
                        <p class="text-base font-semibold text-gray-700">
                            <UserIcon class="inline-block h-4 w-4" />
                            {{ structure.creator.name }}
                        </p>
                        <p
                            v-if="structure.website"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                <div class="w-full space-y-4 md:w-2/3 md:pr-10">
                    <div class="relative mb-4 md:mb-6">
                        <p
                            class="mb-2 text-lg font-medium uppercase tracking-wider text-gray-500"
                        >
                            {{ structure.structuretype.name }}
                        </p>
                        <div
                            class="my-4 flex items-center justify-start space-x-4"
                        >
                            <div v-if="structure.logo">
                                <img
                                    alt="img"
                                    :src="structure.logo"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                            </div>
                            <h2
                                class="inline-block text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ structure.name }}
                            </h2>
                        </div>
                    </div>
                    <div>
                        <p
                            v-if="structure.presentation_longue"
                            class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                        >
                            {{ structure.presentation_longue }}
                        </p>
                        <p
                            v-else
                            class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                        >
                            {{ structure.presentation_courte }}
                        </p>
                    </div>
                    <h3 class="my-4 text-lg font-semibold text-gray-700">
                        Les disciplines proposées:
                    </h3>
                    <div
                        class="flex w-full flex-col items-center justify-start space-y-2 text-gray-600 md:flex-row md:space-x-4 md:space-y-0"
                    >
                        <button
                            v-for="discipline in disciplines"
                            :key="discipline.id"
                            @click=""
                            class="inline-block w-48 rounded border border-gray-600 px-12 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ discipline.name }}
                        </button>
                    </div>
                    <h3 class="my-4 text-lg font-semibold text-gray-700">
                        Les catégories proposées:
                    </h3>
                    <div
                        class="flex w-full flex-col flex-wrap items-stretch justify-between space-y-2 text-gray-600 md:flex-row"
                    >
                        <button
                            v-for="categorie in categories"
                            :key="categorie.id"
                            @click=""
                            class="inline-block w-48 rounded border border-gray-600 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ categorie.nom_categorie_client }}
                        </button>
                    </div>

                    <!-- <TabsComponent :structure="structure"></TabsComponent> -->
                </div>
            </div>
        </section>
        <!-- <section
            v-if="structure.activites.length > 0"
            class="px-2 py-6 mx-auto my-4 space-y-4 max-w-full sm:px-4 lg:px-8"
        >
            <h2 class="text-xl font-bold">Nos activités</h2>
            <div
                v-for="(activite, index) in structure.activites"
                :key="activite.id"
                :index="index"
                class="flex flex-col justify-between w-full px-2 py-4 space-y-4 text-gray-800 border border-gray-500 rounded-lg shadow-md md:flex-row md:space-y-0"
            >
                <div class="flex flex-col justify-start w-full md:w-1/3">
                    <h3 class="mb-3 text-lg font-semibold">{{ activite.name }}</h3>
                    <p class="text-base font-semibold">
                        Discipline pratiquée:
                        <span class="font-normal">{{
                            activite.discipline.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Niveaux:
                        <span class="font-normal">{{
                            activite.nivel.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Type d'activité:
                        <span class="font-normal">{{
                            activite.activitetype.name
                        }}</span>
                    </p>

                    <p class="text-base font-semibold">
                        Public:
                        <span class="font-normal">{{
                            activite.publictype.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Description:
                        <span class="font-medium leading-5 text-gray-700 whitespace-pre-line">{{
                            activite.description
                        }}</span>
                    </p>
                </div>
                <div class="flex flex-col justify-start w-full md:w-1/3">
                    <p class="text-base font-semibold">
                        Adresse:
                        <span class="font-normal">{{ activite.address }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Code postal:
                        <span class="font-normal">{{
                            activite.zip_code
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Ville:
                        <span class="font-normal">{{
                            activite.city
                        }}</span>
                    </p>
                    <LeafletMap
                        :item="activite"
                    />
                </div>
                <div class="flex flex-col items-center justify-end px-4 space-y-2 md:ml-4 md:space-y-6 md:w:1/3">
                    <Link
                        :href="route('structures.activites.edit', {structure:structure.slug, activite: activite})"
                        v-if="can.update"
                        class="flex flex-col items-center justify-center w-full px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                            Editer l'activité
                    </Link>
                    <button
                        v-if="can.delete"
                        @click="destroy(activite)"
                        class="flex flex-col items-center justify-center w-full px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-red-400 hover:text-white hover:ring-2 hover:ring-red-400 hover:ring-offset-2 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        Supprimer cette activité
                    </button>
                </div>
            </div>
        </section> -->
    </AppLayout>
</template>
