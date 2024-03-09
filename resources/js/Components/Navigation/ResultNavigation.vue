<script setup>
import { ref, computed } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import AutocompleteCityNav from "@/Components/Navigation/AutocompleteCityNav.vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import {
    HeartIcon,
    MagnifyingGlassIcon,
    ShoppingCartIcon,
    UserIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    listDisciplines: Object,
    allCities: Object,
    currentDiscipline: Object,
    currentCity: Object,
    currentCategory: Object,
    categories: Object,
    isCategoriesVisible: Boolean,
    isCriteresVisible: Boolean,
});
const page = usePage();
const user = computed(() => page.props.auth.user);

const productsInSession = computed(() => page.props.productsReservations);
const productCountInSession = computed(
    () => Object.keys(productsInSession.value).length
);

const showingNavigationDropdown = ref(false);
const showingSearchForm = ref(false);

const search = ref(props.currentDiscipline ? props.currentDiscipline : null);
const localite = ref(props.currentCity ? props.currentCity : null);
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
            router.get(route("villes.show", { city: city }));
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
        processing.value = false;
    }
};
</script>

<template>
    <nav
        class="fixed inset-x-0 top-0 z-[9998] border-b border-gray-100 bg-slate-900/60 backdrop-blur-sm backdrop-opacity-60"
    >
        <!-- Primary Navigation Menu -->
        <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex items-center shrink-0">
                        <Link :href="route('welcome')">
                            <BreezeApplicationLogo class="block w-auto h-9" />
                        </Link>
                    </div>
                </div>

                <div
                    v-if="!route().current('welcome')"
                    class="hidden space-x-8 lg:ml-10 lg:flex lg:w-full"
                >
                    <section
                        class="flex items-center justify-center w-full px-2 mx-auto md:flex-row md:space-y-0"
                    >
                        <AutocompleteCityNav
                            :cities="allCities"
                            :current-city="currentCity"
                            v-model="localite"
                            class="mr-4"
                        />

                        <AutocompleteDisciplineNav
                            :disciplines="listDisciplines"
                            :current-discipline="currentDiscipline"
                            v-model="search"
                            class="mr-4"
                        />
                        <div class="w-full md:w-auto">
                            <button
                                @click="submitForm"
                                :disabled="processing"
                                type="submit"
                                :class="{
                                    'bg-white text-gray-800 ring-sky-700 ring-offset-2 hover:font-semibold hover:text-gray-900':
                                        localite && search,
                                    'bg-white text-gray-500 hover:text-gray-900':
                                        !localite || !search,
                                }"
                                class="flex items-center justify-center w-full px-2 py-2 text-sm font-medium border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-2 md:w-auto"
                            >
                                <MagnifyingGlassIcon
                                    class="w-5 h-5 md:h-7 md:w-7"
                                />
                                <span class="sr-only">Rechercher</span>
                            </button>
                        </div>
                    </section>
                </div>

                <div class="hidden h-full lg:flex lg:items-center lg:space-x-2">
                    <div class="relative ml-3">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-1 py-2 text-white transition duration-150 ease-in-out hover:text-red-500 focus:text-red-500"
                                    >
                                        <HeartIcon class="w-8 h-8" />
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    :href="route('favoris.index')"
                                    :active="route().current('favoris.index')"
                                >
                                    Mes favoris
                                </BreezeDropdownLink>
                                <p class="px-2 py-2 text-sm text-gray-700">
                                    Vous pouvez retrouver la liste de vos
                                    activités et structures favorites grâce aux
                                    données enregistrées anonymement dans votre
                                    navigateur. Il vous suffit, pour conserver
                                    ces informations, de
                                    <Link
                                        preserve-scroll
                                        :href="route('register')"
                                        class="font-semibold"
                                        >créer un compte</Link
                                    >
                                    sur sports-et-loisirs.fr.
                                </p>
                            </template>
                        </BreezeDropdown>
                    </div>
                    <Link
                        preserve-scroll
                        :href="route('panier.index')"
                        :active="route().current('panier.index')"
                        class="relative"
                    >
                        <ShoppingCartIcon
                            class="w-8 h-8 text-white hover:text-indigo-500 focus:text-indigo-500"
                        />
                        <span
                            v-if="productCountInSession"
                            class="absolute top-0 right-0 px-1 -mt-1 -mr-1 text-xs text-white bg-red-500 rounded-full"
                        >
                            {{ productCountInSession }}
                        </span>
                    </Link>
                    <!-- Settings Dropdown User -->
                    <div class="relative" v-if="user">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-1 py-2 text-white hover:text-indigo-500 focus:text-indigo-500"
                                    >
                                        <UserIcon class="w-8 h-8" />
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div
                                    class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700"
                                >
                                    {{ user.name }}
                                </div>
                                <BreezeDropdownLink
                                    :href="route('posts.index')"
                                    preserve-scroll
                                >
                                    Blog
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    :href="route('profile.edit')"
                                    preserve-scroll
                                >
                                    Mon profil
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    v-if="user && user.structures.length > 0"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            user.structures[0].slug
                                        )
                                    "
                                    :active="
                                        route().current(
                                            'structures.gestion.index'
                                        )
                                    "
                                >
                                    Gestion de ma structure
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    v-if="user && !user.structures.length > 0"
                                    :href="route('structures.create')"
                                >
                                    Inscrire une structure
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    v-if="
                                        user && $page.props.user_can.view_admin
                                    "
                                    :href="route('admin.index')"
                                >
                                    Gestion du site
                                </BreezeDropdownLink>

                                <BreezeDropdownLink
                                    preserve-scroll
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Se déconnecter
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </div>
                    <template v-else>
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-1 py-2 text-white hover:text-indigo-500 focus:text-indigo-500"
                                    >
                                        <UserIcon class="w-8 h-8" />
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink
                                    :href="route('posts.index')"
                                    preserve-scroll
                                >
                                    Blog
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    :href="route('login')"
                                    preserve-scroll
                                >
                                    Connexion
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    :href="route('register')"
                                >
                                    Inscription
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </template>
                </div>

                <!-- svg -->
                <div class="flex items-center -mr-2 space-x-2 lg:hidden">
                    <button
                        @click="showingSearchForm = !showingSearchForm"
                        type="button"
                        class="items-center justify-center px-2 py-2 text-white bg-transparent rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <MagnifyingGlassIcon class="w-6 h-6" />
                        <span class="sr-only">Rechercher</span>
                    </button>
                    <Link
                        preserve-scroll
                        :href="route('panier.index')"
                        :active="route().current('panier.index')"
                        class="relative items-center justify-center px-2 py-2 text-white bg-transparent rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <ShoppingCartIcon class="w-6 h-6" />
                        <span
                            v-if="productCountInSession"
                            class="absolute top-0 right-0 px-1 text-xs text-white bg-red-500 rounded-full"
                        >
                            {{ productCountInSession }}
                        </span>
                    </Link>
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="items-center justify-center px-2 py-2 text-white bg-transparent rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <UserIcon class="w-6 h-6" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="lg:hidden"
        >
            <div class="pt-2 pb-3 space-y-1">
                <Link
                    preserve-scroll
                    class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    :href="route('posts.index')"
                    :active="route().current('posts.index')"
                >
                    Blog
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    :href="route('favoris.index')"
                    :active="route().current('favoris.index')"
                >
                    Mes Favoris
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && !user.structures.length > 0"
                    :href="route('structures.create')"
                    :active="route().current('structures.create')"
                >
                    Inscrire votre structure
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && user.structures.length > 0"
                    :href="
                        route(
                            'structures.gestion.index',
                            user.structures[0].slug
                        )
                    "
                    :active="route().current('structures.gestion.index')"
                >
                    Gestion de ma structure
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && $page.props.user_can.view_admin"
                    :href="route('admin.index')"
                >
                    Gestion du site
                </Link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="py-1 border-t border-gray-200">
                <div class="px-4" v-if="user">
                    <div class="text-base font-medium text-gray-200">
                        {{ user.name }}
                    </div>
                    <div class="text-sm font-medium text-gray-300">
                        {{ user.email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1" v-if="user">
                    <Link
                        preserve-scroll
                        class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Se déconnecter
                    </Link>
                </div>

                <div v-else>
                    <Link
                        preserve-scroll
                        class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                        :href="route('login')"
                        :active="route().current('login')"
                    >
                        Connexion
                    </Link>
                    <Link
                        preserve-scroll
                        class="block w-full py-2 pl-3 pr-4 text-base font-medium text-left text-white transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                        :href="route('register')"
                        :active="route().current('register')"
                    >
                        Inscription
                    </Link>
                </div>
            </div>
        </div>
        <div
            :class="{
                block: showingSearchForm,
                hidden: !showingSearchForm,
            }"
            class="pt-2 pb-3 lg:hidden"
        >
            <section
                class="flex flex-col items-center justify-center w-full px-2 mx-auto space-y-2 md:flex-row md:space-y-0 lg:hidden"
            >
                <AutocompleteCityNav
                    :cities="allCities"
                    :current-city="currentCity"
                    v-model="localite"
                />
                <AutocompleteDisciplineNav
                    :disciplines="listDisciplines"
                    :current-discipline="currentDiscipline"
                    v-model="search"
                />
                <div class="w-full">
                    <button
                        @click="submitForm"
                        :disabled="processing"
                        type="submit"
                        :class="{
                            'bg-white text-gray-800 ring-sky-700 ring-offset-2  hover:font-semibold hover:text-gray-900':
                                localite && search,
                            'bg-white text-gray-700 hover:text-gray-900':
                                !localite || !search,
                        }"
                        class="flex items-center justify-center w-full px-2 py-2 text-base font-semibold border border-gray-500 rounded-md shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                    >
                        <MagnifyingGlassIcon class="w-6 h-6 mr-2" />
                        <span class="">Rechercher</span>
                    </button>
                </div>
            </section>
        </div>
    </nav>
</template>
