<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import BreezeNavLink from "@/Components/NavLink.vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
import { ArrowSmallRightIcon, CheckIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    categories: Object,
    disciplines: Object,
    categoriesCount: Number,
    disciplinesCount: Number,
    structuresCount: Number,
    citiesCount: Number,
    lastStructures: Object,
    topVilles: Object,
    filters: Object,
});

let search = ref(props.filters.search);
const searchbox = ref(null);

onMounted(() => {
    searchbox.value.focus();
});

const formatCityName = (ville) => {
    return ville.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
};

watch(
    search,
    debounce(function (value) {
        router.get(
            "/structures",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);
</script>

<template>
    <Head
        title="Accueil"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 structures référencées."
    />

    <AppLayout>
        <header>
            <div class="hidden bg-white shadow-lg shadow-sky-600/20 sm:block">
                <div class="mx-auto max-w-7xl px-2 py-6 sm:px-3 lg:px-6">
                    <div
                        class="grid grid-cols-1 place-items-start gap-2 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-10 lg:place-items-center"
                    >
                        <BreezeNavLink
                            :href="route('categories.show', category.slug)"
                            :active="
                                route().current(
                                    'categories.show',
                                    category.slug
                                )
                            "
                            v-for="(category, index) in categories"
                            :key="index"
                            >{{ category.name }}</BreezeNavLink
                        >
                    </div>
                </div>
            </div>
        </header>
        <div>
            <section
                class="mx-auto flex w-full max-w-7xl flex-col items-center justify-center space-y-4 px-2 py-8 md:flex-row md:space-x-4 md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <label
                        for="search"
                        value="Rechercher une structure"
                        class="mb-1 pr-2 text-xl font-medium text-gray-800"
                        >Rechercher une structure, une discipline, une
                        ville...</label
                    >

                    <TextInput
                        ref="searchbox"
                        id="search"
                        type="text"
                        class="mt-1 block h-20 w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                        v-model="search"
                        placeholder="structure, discipline, ville, code postal..."
                    />
                </div>
            </section>
            <section
                class="font-Mochiy relative mx-auto my-16 flex min-h-full max-w-7xl flex-col items-center justify-center bg-transparent md:my-0 md:min-h-screen"
            >
                <div
                    class="max-w-sm -rotate-3 text-center text-5xl font-black text-white md:max-w-3xl md:text-8xl"
                >
                    <h1
                        class="bg-gradient-to-br from-green-500 via-blue-600 to-orange-400 bg-clip-text px-4 py-2 text-transparent"
                    >
                        Trouvez votre Club
                        <span class="block">près de chez vous!</span>
                    </h1>
                </div>
                <div
                    class="absolute top-0 left-0 mx-2 my-10 hidden sm:top-10 md:block"
                >
                    <div
                        class="-rotate-3 bg-gradient-to-br from-red-500 to-blue-500 bg-clip-text text-xl font-bold text-transparent sm:text-3xl"
                    >
                        {{ structuresCount }} clubs référencés!
                    </div>
                </div>
                <div
                    class="absolute right-0 top-10 mx-2 hidden py-10 sm:top-0 md:block"
                >
                    <div
                        class="rotate-3 bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text py-2 text-xl font-bold text-transparent sm:text-3xl"
                    >
                        {{ disciplinesCount }} disciplines disponibles!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 right-0 mx-2 my-24 hidden md:block"
                >
                    <div
                        class="-rotate-3 bg-gradient-to-br from-sky-600 to-blue-600 bg-clip-text py-2 text-xl font-bold text-transparent sm:text-2xl"
                    >
                        + {{ citiesCount }} villes!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 left-0 mx-2 my-20 hidden md:block"
                >
                    <div
                        class="rotate-3 bg-gradient-to-br from-orange-400 to-red-500 bg-clip-text py-2 text-xl font-bold text-transparent sm:text-2xl"
                    >
                        +100 000 pratiquants!
                    </div>
                </div>
            </section>

            <section
                class="mx-auto max-w-7xl bg-transparent px-2 py-8 md:py-20"
            >
                <div
                    class="flex flex-col items-start justify-between space-y-12 md:flex-row md:space-y-0 md:space-x-20"
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
                            Trouvez un club de sport ou un cours collectif dans
                            plus de
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
                            Plus de {{ structuresCount }} structures référencées
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

            <section class="mx-auto max-w-7xl px-2 py-8 md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Besoin d'inspiration:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <Link
                        :href="route('disciplines.show', discipline.slug)"
                        :active="
                            route().current('disciplines.show', discipline.slug)
                        "
                        v-for="discipline in disciplines"
                        :key="discipline.id"
                        class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >{{ discipline.name }}</Link
                    >
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link
                        :href="route('disciplines.index')"
                        class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                    </Link>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-2 py-8 md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Top villes:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <Link
                        :href="route('villes.show', city.ville_formatee)"
                        :active="
                            route().current('villes.show', city.ville_formatee)
                        "
                        v-for="city in topVilles"
                        :key="city.id"
                        class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >{{ formatCityName(city.ville) }}</Link
                    >
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <Link
                        :href="route('villes.index')"
                        class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                        </span>
                    </Link>
                </div>
            </section>

            <section
                class="mx-auto flex max-w-7xl flex-col justify-between px-2 py-8 md:flex-row md:py-20"
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
                            class="flex flex-col items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            <div class="mb-1 text-center">
                                {{ structure.name }}
                            </div>
                            <div class="mb-1 text-xs">
                                {{ structure.category.name }}
                            </div>
                            <div class="mb-1 text-xs">
                                {{ structure.city }} ({{ structure.zip_code }})
                            </div>
                        </Link>
                    </div>
                    <div class="mb-4 flex items-center justify-center">
                        <Link
                            :href="route('structures.index')"
                            class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
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
                            class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Créer votre structure
                            <span>
                                <ArrowSmallRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                        <Link
                            v-else
                            :href="route('register')"
                            class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
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
