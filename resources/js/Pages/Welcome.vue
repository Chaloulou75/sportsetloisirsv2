<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import AutocompleteDiscipline from "@/Components/Home/AutocompleteDiscipline.vue";
import AutocompleteCity from "@/Components/Home/AutocompleteCity.vue";
import { ArrowSmallRightIcon, CheckIcon } from "@heroicons/vue/24/solid";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    disciplines: Object,
    famillesCount: Number,
    disciplinesCount: Number,
    structuresCount: Number,
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
            router.get(`/villes/${city}/disciplines/${disciplineSlug}`);
        } else if (city) {
            router.get(`/villes/${city}`);
        } else if (disciplineSlug) {
            router.get(`/disciplines/${disciplineSlug}`);
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
    <Head title="Accueil"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 structures référencées." />

    <AppLayout>
        <header>
            <FamilleNavigation :familles="familles" class="my-3" />
        </header>
        <div>
            <section
                class="bg-gradient-to-br from-green-100 to-blue-100 mx-auto flex w-full max-w-full flex-col items-end justify-center space-x-0 space-y-4 px-2 md:px-8 py-8 md:flex-row md:space-x-4 md:space-y-0">
                <AutocompleteCity :cities="allCities" v-model="localite" />
                <AutocompleteDiscipline :disciplines="listDisciplines" v-model="search" />
                <div class="w-full md:w-auto ">
                    <button @click="submitForm" :disabled="processing" type="submit"
                        class="w-full md:w-auto flex items-center justify-center rounded border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 mb-0.5">
                        <span class="inline-block md:hidden mr-4">Trouver</span>
                        <MagnifyingGlassIcon class="h-5 w-5" />
                        <span class="sr-only">Rechercher</span>
                    </button>
                </div>
            </section>
            <!-- <section
                class="min-h-full mx-auto my-16 max-w-full md:my-0 md:min-h-screen"
            >
                <swiper
                    :slides-per-view="1"
                    :space-between="30"
                    :loop="true"
                    :centeredSlides="true"
                    :autoplay="{
                        delay: 2600,
                    }"
                    :modules="modules"
                    class="mySwiper"
                >
                    <swiper-slide v-for="(image, index) in imagePaths">
                        <img
                            :src="image" :key="index" alt="Image"
                            class="rounded-2xl h-full w-full object-cover opacity-95"
                        />
                    </swiper-slide>
                </swiper>
            </section>
            <section
                class="relative flex flex-col items-center justify-center min-h-full mx-auto my-16 bg-transparent max-w-full md:my-0 md:min-h-screen"
            >
                <div
                    class="max-w-sm text-5xl font-black text-center text-white -rotate-3 md:max-w-3xl md:text-8xl"
                >
                    <h1
                        class="px-4 py-2 text-transparent bg-gradient-to-br from-green-500 via-blue-600 to-orange-400 bg-clip-text"
                    >
                        Trouvez votre Club
                        <span class="block">près de chez vous!</span>
                    </h1>
                </div>
                <div
                    class="absolute top-0 left-0 hidden mx-2 my-10 sm:top-10 md:block"
                >
                    <div
                        class="text-xl font-bold text-transparent -rotate-3 bg-gradient-to-br from-red-500 to-blue-500 bg-clip-text sm:text-3xl"
                    >
                        {{ structuresCount }} clubs référencés!
                    </div>
                </div>
                <div
                    class="absolute right-0 hidden py-10 mx-2 top-10 sm:top-0 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent rotate-3 bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text sm:text-3xl"
                    >
                        {{ disciplinesCount }} disciplines disponibles!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 right-0 hidden mx-2 my-24 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent -rotate-3 bg-gradient-to-br from-sky-600 to-blue-600 bg-clip-text sm:text-2xl"
                    >
                        + {{ citiesCount }} villes!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 left-0 hidden mx-2 my-20 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent rotate-3 bg-gradient-to-br from-orange-400 to-red-500 bg-clip-text sm:text-2xl"
                    >
                        +100 000 pratiquants!
                    </div>
                </div>
            </section> -->

            <section class="mx-auto max-w-full bg-transparent px-2 md:px-8 py-8 md:py-20">
                <div class="flex flex-col items-start justify-between space-y-12 md:flex-row md:space-x-20 md:space-y-0">
                    <div class="rounded-lg bg-white px-6 py-4 shadow-xl shadow-red-500">
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-red-500 decoration-4 underline-offset-2">
                            Nombreuses disciplines
                        </h3>
                        <p class="text-gray-600">
                            Trouvez un club de sport ou un cours collectif dans
                            plus de
                            <span class="font-semibold">{{
                                disciplinesCount
                                }}</span>
                            <span class="italic"> disciplines différentes</span>. Il y en a pour tous les gouts : sports
                            collectifs, sports de balle, sport de combats,
                            danse, musique, ...
                        </p>
                    </div>
                    <div class="rounded-lg bg-white px-6 py-4 shadow-lg shadow-sky-600 md:translate-y-20">
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-sky-600 decoration-4 underline-offset-2">
                            Plus de {{ structuresCount }} structures référencées
                        </h3>
                        <p class="text-gray-600">
                            De très nombreuses structures référencées, prêtes à
                            vous accueillir et vous accompagner dans la pratique
                            de votre discipline favorite !
                        </p>
                    </div>
                    <div class="rounded-lg bg-white px-6 py-4 shadow-lg shadow-yellow-500">
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-yellow-500 decoration-4 underline-offset-2">
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
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4">
                    <Link :href="route('disciplines.show', discipline.slug)" :active="
                            route().current('disciplines.show', discipline.slug)
                        " v-for="discipline in disciplines" :key="discipline.id"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    {{ discipline.name }}
                    </Link>
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link :href="route('disciplines.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
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
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4">
                    <Link :href="route('villes.show', city.id)" :active="route().current('villes.show', city.id)"
                        v-for="city in topVilles" :key="city.id"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    {{ formatCityName(city.ville) }}</Link>
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link :href="route('villes.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
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
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4">
                    <Link :href="route('departements.show', departement.id)" :active="
                            route().current('departements.show', departement.id)
                        " v-for="departement in topDepartements" :key="departement.id"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    {{ formatCityName(departement.departement) }}</Link>
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link :href="route('departements.index')"
                        class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    Et beaucoup d'autres
                    <span>
                        <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                    </span>
                    </Link>
                </div>
            </section>

            <section class="mx-auto flex max-w-full flex-col justify-between px-2 md:px-8 py-8 md:flex-row md:py-20">
                <div class="mb-6 max-w-full grow sm:mb-0">
                    <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                        Les dernieres structures inscrites:
                    </h3>
                    <div class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link :href="route('structures.show', structure.slug)" :active="
                                route().current(
                                    'structures.show',
                                    structure.slug
                                )
                            " v-for="structure in lastStructures" :key="structure.id"
                            class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
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
                        <Link :href="route('structures.index')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                        </Link>
                    </div>
                </div>
                <div class="mx-auto max-w-sm text-gray-700 md:px-8">
                    <h3 class="pb-6 text-center text-2xl font-semibold text-gray-700 sm:text-left">
                        Inscrivez votre structure:
                    </h3>
                    <p class="pb-6 text-center text-lg font-semibold text-gray-700 sm:text-left">
                        C'est gratuit
                    </p>
                    <p class="pb-6 text-justify text-base font-medium text-gray-700 sm:text-left">
                        Pour inscrire votre structure, il vous suffit de créer
                        un compte. Vous aurez accès à l'ensemble de nos services
                        gratuitement. Vous pourrez ajouter un grand nombre
                        d'informations, et diffuser vos évenements.
                    </p>
                    <p class="pb-6 text-center text-lg font-semibold text-gray-700 sm:text-left">
                        Une fiche complète
                    </p>
                    <p class="mb-2 flex items-center font-medium">
                        <CheckIcon class="mr-2 h-5 w-5 text-blue-800" />Présentation de votre structure
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
                        <Link v-if="$page.props.auth.user" :href="route('structures.create')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                        Créer votre structure
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                        </Link>
                        <Link v-else :href="route('register')"
                            class="flex items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                        S'inscrire
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
