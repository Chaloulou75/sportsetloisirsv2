<script setup>
import { ref, computed, inject } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import AutocompleteCityNav from "@/Components/Navigation/AutocompleteCityNav.vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { TransitionRoot } from "@headlessui/vue";
import {
    HeartIcon,
    MagnifyingGlassIcon,
    ShoppingCartIcon,
    UserIcon,
    AdjustmentsHorizontalIcon,
} from "@heroicons/vue/24/solid";

const scrollToCriteres = inject("scrollToCriteres", null);
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    listDisciplines: Object,
    allCities: Object,
    currentDiscipline: Object,
    currentCity: Object,
    currentCategory: Object,
    isCriteresVisible: Boolean,
});
const page = usePage();
const user = computed(() => page.props.auth.user);
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
        class="fixed inset-x-0 top-0 z-[9999] border-b border-gray-100 bg-slate-900/80"
    >
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('welcome')">
                            <BreezeApplicationLogo class="block h-9 w-auto" />
                        </Link>
                    </div>
                </div>

                <div class="hidden space-x-8 lg:ml-10 lg:flex lg:w-full">
                    <section
                        class="mx-auto flex w-full items-center justify-center px-2 md:flex-row md:space-y-0"
                    >
                        <AutocompleteCityNav
                            :cities="allCities"
                            v-model="localite"
                            :current-city="currentCity"
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
                                class="flex w-full items-center justify-center rounded border border-gray-300 bg-gray-50 px-2 py-2 text-sm font-medium text-gray-400 shadow-sm hover:bg-white hover:text-gray-800 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-2 md:w-auto"
                            >
                                <MagnifyingGlassIcon
                                    class="h-5 w-5 md:h-7 md:w-7"
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
                                        <HeartIcon class="h-8 w-8" />
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
                                <p class="px-4 py-2 text-sm text-gray-700">
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
                    <div v-if="currentCategory">
                        <TransitionRoot
                            :show="!isCriteresVisible"
                            enter="transition-opacity duration-150"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="transition-opacity duration-150"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <BreezeDropdown align="right">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-1 py-2 text-white transition duration-150 ease-in-out hover:text-red-500 focus:text-red-500"
                                        >
                                            <AdjustmentsHorizontalIcon
                                                class="h-8 w-8 text-white hover:text-indigo-500 focus:text-indigo-500"
                                            />
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <button
                                        type="button"
                                        @click="scrollToCriteres"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700"
                                    >
                                        Choix des criteres
                                    </button>
                                </template>
                            </BreezeDropdown>
                        </TransitionRoot>
                    </div>
                    <Link
                        preserve-scroll
                        :href="route('welcome')"
                        :active="route().current('welcome')"
                    >
                        <ShoppingCartIcon
                            class="h-8 w-8 text-white hover:text-indigo-500 focus:text-indigo-500"
                        />
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
                                        <UserIcon class="h-8 w-8" />
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700"
                                >
                                    {{ user.name }}
                                </div>
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
                                        <UserIcon class="h-8 w-8" />
                                    </button>
                                </span>
                            </template>

                            <template #content>
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
                <div class="-mr-2 flex items-center space-x-2 lg:hidden">
                    <button
                        @click="showingSearchForm = !showingSearchForm"
                        type="button"
                        class="items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <MagnifyingGlassIcon class="h-6 w-6" />
                        <span class="sr-only">Rechercher</span>
                    </button>
                    <div v-if="currentCategory">
                        <TransitionRoot
                            :show="!isCriteresVisible"
                            enter="transition-opacity duration-150"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="transition-opacity duration-150"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <button
                                type="button"
                                @click="scrollToCriteres"
                                class="inline-flex items-center px-1 py-2 text-white transition duration-150 ease-in-out hover:text-red-500 focus:text-red-500"
                            >
                                <AdjustmentsHorizontalIcon
                                    class="h-8 w-8 text-white hover:text-indigo-500 focus:text-indigo-500"
                                />
                            </button>
                        </TransitionRoot>
                    </div>
                    <button
                        type="button"
                        class="items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <ShoppingCartIcon class="h-6 w-6" />
                    </button>
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <UserIcon class="h-6 w-6" />
                        <!-- <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg> -->
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
            <div class="space-y-1 pb-3 pt-2">
                <Link
                    preserve-scroll
                    class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    :href="route('favoris.index')"
                    :active="route().current('favoris.index')"
                >
                    Mes Favoris
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && !user.structures.length > 0"
                    :href="route('structures.create')"
                    :active="route().current('structures.create')"
                >
                    Inscrire votre structure
                </Link>
                <Link
                    preserve-scroll
                    class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
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
                    class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && $page.props.user_can.view_admin"
                    :href="route('admin.index')"
                >
                    Gestion du site
                </Link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-gray-200 py-1">
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
                        class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
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
                        class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                        :href="route('login')"
                        :active="route().current('login')"
                    >
                        Connexion
                    </Link>
                    <Link
                        preserve-scroll
                        class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
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
            class="pb-3 pt-2 lg:hidden"
        >
            <section
                class="mx-auto flex w-full flex-col items-center justify-center space-y-2 px-2 md:flex-row md:space-y-0 lg:hidden"
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
                        class="flex w-full items-center justify-center rounded-md border border-gray-500 bg-white px-2 py-2 text-base font-semibold text-gray-800 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                    >
                        <MagnifyingGlassIcon class="mr-2 h-6 w-6" />
                        <span class="">Rechercher</span>
                    </button>
                </div>
            </section>
        </div>
    </nav>
</template>
