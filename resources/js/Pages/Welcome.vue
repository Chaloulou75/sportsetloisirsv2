<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import AutocompleteDiscipline from "@/Components/Home/AutocompleteDiscipline.vue";
import AutocompleteCity from "@/Components/Home/AutocompleteCity.vue";
import DisciplineSmallCard from "@/Components/Disciplines/DisciplineSmallCard.vue";
import { ArrowSmallRightIcon, CheckIcon } from "@heroicons/vue/24/solid";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    disciplines: Object,
    famillesCount: Number,
    disciplinesCount: Number,
    structuresCount: Number,
    produitsCount: Number,
    citiesCount: Number,
    lastStructures: Object,
    allCities: Object,
    topVilles: Object,
    topDepartements: Object,
    filters: Object,
});

const search = ref(null);
const localite = ref(null);
const processing = ref(false);

const submitForm = async () => {
    processing.value = true;
    try {
        const city = localite.value;
        const disciplineSlug = search.value;

        if (city && disciplineSlug) {
            router.get(
                route("villes.disciplines.show", {
                    city: city,
                    discipline: disciplineSlug,
                })
            );
        } else if (city) {
            router.get(route("villes.show", { city }));
        } else if (disciplineSlug) {
            router.get(
                route("disciplines.show", { discipline: disciplineSlug })
            );
        }

        // Reset the form
        localite.value = "";
        search.value = "";
        processing.value = false;
    } catch (error) {
        console.error("Error:", error);
        processing.value = false;
    }
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>

<template>
    <Head
        title="Accueil"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 structures référencées."
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title> Sports et loisirs </template>
            </ResultsHeader>
        </template>
        <template #default>
            <section
                class="mx-auto flex w-full max-w-full flex-col items-end justify-center space-x-0 space-y-4 px-2 py-8 md:flex-row md:space-x-4 md:space-y-0 md:px-8"
            >
                <AutocompleteCity :cities="allCities" v-model="localite" />
                <AutocompleteDiscipline
                    :disciplines="listDisciplines"
                    v-model="search"
                />

                <div class="w-full md:w-auto">
                    <button
                        @click="submitForm"
                        :disabled="processing"
                        type="submit"
                        :class="{
                            'bg-sky-700 text-white hover:bg-sky-800 hover:font-semibold hover:text-white':
                                localite && search,
                            'bg-white text-gray-700 hover:bg-sky-800 hover:text-white':
                                !localite || !search,
                        }"
                        class="flex w-full items-center justify-center rounded border border-gray-300 px-4 py-3 text-base font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-700 focus:ring-offset-2 md:w-auto"
                    >
                        <MagnifyingGlassIcon
                            class="mr-2 h-6 w-6 md:mr-0 md:h-5 md:w-5"
                        />
                        <span class="inline-block md:hidden">Rechercher</span>
                        <span class="sr-only">Rechercher</span>
                    </button>
                </div>
            </section>

            <section
                class="mx-auto max-w-full bg-transparent px-2 py-8 md:px-8 md:py-20"
            >
                <div
                    class="flex flex-col items-start justify-between space-y-12 md:flex-row md:space-x-20 md:space-y-0"
                >
                    <div
                        class="rounded-lg bg-white px-6 py-4 shadow-xl shadow-red-500"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-red-500 decoration-4 underline-offset-2"
                        >
                            Nombreuses disciplines
                        </h3>
                        <p class="text-gray-600">
                            Trouvez un club de sport ou un des
                            <span class="font-semibold">{{
                                produitsCount
                            }}</span>
                            cours collectifs dans plus de
                            <span class="font-semibold">{{
                                disciplinesCount
                            }}</span>
                            <span class="italic"> disciplines différentes</span
                            >. Il y en a pour tous les gouts : sports
                            collectifs, sports de balle, sport de combats,
                            danse, musique, ...
                        </p>
                    </div>
                    <div
                        class="rounded-lg bg-white px-6 py-4 shadow-lg shadow-sky-600 md:translate-y-20"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-sky-600 decoration-4 underline-offset-2"
                        >
                            Plus de {{ structuresCount }}
                            <span v-if="structuresCount > 1"
                                >structures référencées</span
                            >
                            <span v-else>structure référencée</span>
                        </h3>
                        <p class="text-gray-600">
                            De très nombreuses structures référencées, prêtes à
                            vous accueillir et vous accompagner dans la pratique
                            de votre discipline favorite !
                        </p>
                    </div>
                    <div
                        class="rounded-lg bg-white px-6 py-4 shadow-lg shadow-yellow-500"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-yellow-500 decoration-4 underline-offset-2"
                        >
                            Des fiches détaillées
                        </h3>
                        <p class="text-gray-600">
                            Pour chaque structures, vous pourrez y trouver les
                            activités, les lieux de pratique, les horaires et
                            les tarifs. Un maximum de détails pour vous aider
                            dans votre choix !
                        </p>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-full px-2 py-8 md:px-8 md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Besoin d'inspiration:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <DisciplineSmallCard
                        v-for="discipline in disciplines"
                        :key="discipline.id"
                        :discipline="discipline"
                        :link="route('disciplines.show', discipline.slug)"
                    />
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link
                        :href="route('disciplines.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                    </Link>
                </div>
            </section>

            <section class="mx-auto max-w-full px-2 py-8 md:px-8 md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Top villes:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <Link
                        :href="
                            route('villes.show', {
                                city: city.slug,
                            })
                        "
                        v-for="city in topVilles"
                        :key="city.id"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        {{ formatCityName(city.ville) }}
                        <span class="text-xs italic"
                            >{{ city.produits_count }} activités</span
                        >
                    </Link>
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link
                        :href="route('villes.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                    </Link>
                </div>
            </section>

            <section class="mx-auto max-w-full px-2 py-8 md:px-8 md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Top departements:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <Link
                        :href="route('departements.show', departement.slug)"
                        :active="
                            route().current(
                                'departements.show',
                                departement.slug
                            )
                        "
                        v-for="departement in topDepartements"
                        :key="departement.id"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        {{ formatCityName(departement.departement) }}
                        <span class="text-xs italic"
                            >{{ departement.produits_count }} activités</span
                        ></Link
                    >
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link
                        :href="route('departements.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                    </Link>
                </div>
            </section>

            <section
                class="mx-auto flex max-w-full flex-col justify-between px-2 py-8 md:flex-row md:px-8 md:py-20"
            >
                <div class="mb-6 max-w-full grow sm:mb-0">
                    <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                        Les dernieres structures inscrites:
                    </h3>
                    <div
                        class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            :active="
                                route().current(
                                    'structures.show',
                                    structure.slug
                                )
                            "
                            v-for="structure in lastStructures"
                            :key="structure.id"
                            class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            <div class="mb-1 text-center">
                                {{ structure.name }}
                            </div>
                            <div class="mb-1 text-xs">
                                {{ structure.structuretype.name }}
                            </div>
                            <div class="mb-1 text-xs">
                                {{ structure.city }} ({{ structure.zip_code }})
                            </div>
                        </Link>
                    </div>
                    <div class="mb-4 flex items-center justify-center">
                        <Link
                            :href="route('structures.index')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            Et beaucoup d'autres
                            <span>
                                <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                    </div>
                </div>
                <div class="mx-auto max-w-sm text-gray-700 md:px-8">
                    <h3
                        class="pb-6 text-center text-2xl font-semibold text-gray-700 sm:text-left"
                    >
                        Inscrivez votre structure:
                    </h3>
                    <p
                        class="pb-6 text-center text-lg font-semibold text-gray-700 sm:text-left"
                    >
                        C'est gratuit
                    </p>
                    <p
                        class="pb-6 text-justify text-base font-medium text-gray-700 sm:text-left"
                    >
                        Pour inscrire votre structure, il vous suffit de créer
                        un compte. Vous aurez accès à l'ensemble de nos services
                        gratuitement. Vous pourrez ajouter un grand nombre
                        d'informations, et diffuser vos évenements.
                    </p>
                    <p
                        class="pb-6 text-center text-lg font-semibold text-gray-700 sm:text-left"
                    >
                        Une fiche complète
                    </p>
                    <p class="mb-2 flex items-center font-medium">
                        <CheckIcon
                            class="mr-2 h-5 w-5 text-blue-800"
                        />Présentation de votre structure
                    </p>
                    <p class="mb-2 flex items-center font-medium">
                        <CheckIcon class="mr-2 h-5 w-5 text-blue-800" />Lieux de
                        pratique
                    </p>
                    <p class="mb-2 flex items-center font-medium">
                        <CheckIcon class="mr-2 h-5 w-5 text-blue-800" />Horaires
                        de pratique
                    </p>
                    <p class="mb-2 flex items-center font-medium">
                        <CheckIcon class="mr-2 h-5 w-5 text-blue-800" />Tarifs
                    </p>
                    <p class="mb-8 flex items-center font-medium">
                        <CheckIcon class="mr-2 h-5 w-5 text-blue-800" />Photos
                    </p>
                    <div class="mb-4 flex items-center justify-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('structures.create')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            Créer votre structure
                            <span>
                                <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                        <Link
                            v-else
                            :href="route('register')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            S'inscrire
                            <span>
                                <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                    </div>
                </div>
            </section>
        </template>
    </ResultLayout>
</template>
