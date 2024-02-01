<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import ProduitFormuleCard from "@/Components/Produits/ProduitFormuleCard.vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import { HomeIcon } from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
dayjs.locale("fr");

const props = defineProps({
    departement: Object,
    city: Object,
    citiesAround: Object,
    allCities: Object,
    familles: Object,
    listDisciplines: Object,
    discipline: Object,
    activite: Object,
    criteres: Object,
    activiteSimilaires: Object,
    selectedProduit: Object,
    requestCategory: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    structuretypeElected: Object,
    produits: Object,
});

const reservationForm = useForm({
    produit: null,
    formule: null,
    planning: null,
    remember: false,
});

const submitReservation = () => {
    reservationForm.post("/product_reservations", {
        preserveScroll: true,
        onSuccess: () => {
            reservationForm.reset();
            displayPlanning.value = false;
        },
    });
};
</script>

<template>
    <Head
        :title="activite.titre + ' - ' + activite.structure.name"
        :description="
            'Fiche détaillée de ' + activite.titre + '. Horaires et tarifs.'
        "
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :departement="departement"
        :city="city"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> {{ activite.titre }} </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex text-gray-600 border border-gray-200 rounded-lg"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="w-4 h-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
                                        Accueil
                                    </span>
                                </Link>
                            </li>

                            <li v-if="city" class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('villes.show', city.slug)"
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ formatCityName(city.ville) }}
                                </Link>
                            </li>

                            <li
                                v-if="departement"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'departements.show',
                                            departement.slug
                                        )
                                    "
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>

                            <li
                                v-if="discipline"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'disciplines.show',
                                            discipline.slug
                                        )
                                    "
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ discipline.name }}
                                </Link>
                            </li>

                            <li
                                v-if="requestCategory"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('disciplines.categories.show', {
                                            discipline: discipline.slug,
                                            category: requestCategory.slug,
                                        })
                                    "
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ requestCategory.nom_categorie_client }}
                                </Link>
                            </li>

                            <li
                                v-if="structuretypeElected"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'disciplines.structuretypes.show',
                                            {
                                                discipline: discipline.slug,
                                                structuretype:
                                                    structuretypeElected.id,
                                            }
                                        )
                                    "
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ structuretypeElected.name }}
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
                                        route('structures.activites.show', {
                                            activite: activite,
                                        })
                                    "
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    {{ activite.titre }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template #default>
            <div
                class="sticky left-0 right-0 top-16 z-[1199] bg-transparent backdrop-blur-md"
                ref="categoriesEl"
                v-if="categories && categories.length > 0"
            >
                <CategoriesResultNavigation
                    :city="city"
                    :departement="departement"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :category="requestCategory"
                    :structuretype-elected="structuretypeElected"
                />
            </div>

            <section class="max-w-full px-0 py-6 mx-auto my-4 sm:px-4 lg:px-8">
                <div
                    class="flex flex-col justify-between px-4 py-6 bg-white rounded-lg shadow text-slate-600 md:flex-row md:items-start md:space-x-6"
                >
                    <div class="w-full">
                        <div class="relative space-y-12">
                            <!-- titre -->
                            <div
                                class="flex items-center justify-start my-4 space-x-4"
                            >
                                <h1
                                    class="inline-block w-full text-xl font-semibold text-center sm:text-2xl sm:leading-7 md:text-3xl"
                                >
                                    Page à refaire totalement:
                                    {{ activite.titre }}
                                </h1>
                            </div>
                            <!-- Resume -->
                            <div>
                                <p
                                    v-if="activite.description"
                                    class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line"
                                >
                                    {{ activite.description }}
                                </p>
                                <p
                                    v-else-if="
                                        activite.structure.presentation_longue
                                    "
                                    class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line"
                                >
                                    {{ activite.structure.presentation_longue }}
                                </p>
                                <p
                                    v-else
                                    class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line"
                                >
                                    {{ activite.structure.presentation_courte }}
                                </p>
                            </div>
                            <!-- instructeurs -->
                            <div v-if="activite.instructeurs.length > 0">
                                <p class="text-base font-medium text-gray-700">
                                    Vos instructeurs:
                                </p>
                                <ul>
                                    <li
                                        v-for="instructeur in activite.instructeurs"
                                        class="text-base font-semibold text-gray-600 list-disc list-inside"
                                    >
                                        {{ instructeur.pivot.contact }} -
                                        {{ instructeur.pivot.email }}
                                    </li>
                                </ul>
                            </div>
                            <h3 class="text-xl text-gray-700">
                                Selectionner une formule:
                            </h3>
                            <div
                                class="grid h-auto grid-cols-1 gap-4 place-content-stretch place-items-stretch lg:grid-cols-2"
                            >
                                <ProduitFormuleCard
                                    v-for="produit in produits"
                                    :key="produit.id"
                                    :produit="produit"
                                    :discipline="produit.discipline"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white">
                <div
                    class="max-w-full px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-2xl font-semibold tracking-tight text-center text-gray-700 sm:text-3xl"
                    >
                        Les derniers avis sur cette activité
                    </h2>

                    <div
                        class="grid grid-cols-1 gap-4 mt-12 md:grid-cols-3 md:gap-8"
                    >
                        <blockquote class="p-8 bg-gray-100 rounded-lg">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="object-cover w-16 h-16 rounded-full"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4 text-white" />
                                        <StarIcon class="w-4 h-4 text-white" />
                                        <StarIcon class="w-4 h-4 text-white" />
                                        <StarIcon class="w-4 h-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Paul Jacquemimou
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 text-gray-500 line-clamp-2 sm:line-clamp-none"
                            >
                                Très mauvaise expérience! A fuir!
                            </p>
                        </blockquote>

                        <blockquote class="p-8 bg-gray-100 rounded-lg">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="object-cover w-16 h-16 rounded-full"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Jacques Miteux
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 text-gray-500 line-clamp-2 sm:line-clamp-none"
                            >
                                C'était à chier, mais je mets 5 étoiles pour le
                                sourire de Roberta.
                            </p>
                        </blockquote>

                        <blockquote class="p-8 bg-gray-100 rounded-lg">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="object-cover w-16 h-16 rounded-full"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4" />
                                        <StarIcon class="w-4 h-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Antoine Boss
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 text-gray-500 line-clamp-2 sm:line-clamp-none"
                            >
                                C'était vraiment sensationnel.
                            </p>
                        </blockquote>
                    </div>
                </div>
            </section>
            <section v-if="activiteSimilaires.length > 0" class="bg-white">
                <div
                    class="max-w-full px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-2xl font-semibold tracking-tight text-center text-gray-700 sm:text-3xl"
                    >
                        Les activités similaires
                    </h2>
                    <div
                        class="grid grid-cols-1 gap-4 mt-12 md:grid-cols-3 md:gap-8"
                    >
                        <ActiviteCard
                            v-for="activite in activiteSimilaires"
                            :key="activite.id"
                            :activite="activite"
                            :link="
                                route('structures.activites.show', {
                                    activite: activite,
                                })
                            "
                        />
                    </div>
                </div>
            </section>
        </template>
    </ResultLayout>
</template>

<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
