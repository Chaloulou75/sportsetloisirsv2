<script setup>
import { ref, computed } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
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
});
const page = usePage();
const user = computed(() => page.props.auth.user);
const showingNavigationDropdown = ref(false);

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
            // router.get(`/villes/${city}/disciplines/${disciplineSlug}`);
        } else if (city) {
            router.get(route("villes.show", { city }));
        } else if (disciplineSlug) {
            router.get(route("disciplines.show", { disciplineSlug }));
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
</script>

<template>
    <nav class="border-b border-gray-100 bg-slate-900/80">
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

                <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                    <section
                        class="mx-auto flex w-full items-center justify-center px-2 md:flex-row md:space-x-4 md:space-y-0"
                    >
                        <AutocompleteCityNav
                            :cities="allCities"
                            v-model="localite"
                        />
                        <AutocompleteDisciplineNav
                            :disciplines="listDisciplines"
                            v-model="search"
                        />
                        <div class="w-full md:w-auto">
                            <button
                                @click="submitForm"
                                :disabled="processing"
                                type="submit"
                                class="mb-0.5 flex w-full items-center justify-center rounded border border-indigo-500 bg-white px-2 py-2 text-sm font-medium text-indigo-500 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                            >
                                <MagnifyingGlassIcon class="h-5 w-5" />
                                <span class="sr-only">Rechercher</span>
                            </button>
                        </div>
                    </section>
                </div>

                <div
                    class="hidden h-full lg:ml-6 lg:flex lg:items-center lg:space-x-2"
                >
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
                                        <UserIcon class="ml-2 h-8 w-8" />
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
                                >
                                    Mon profil
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
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
                                    v-if="user && !user.structures.length > 0"
                                    :href="route('structures.create')"
                                >
                                    Inscrire une structure
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    v-if="
                                        user && $page.props.user_can.view_admin
                                    "
                                    :href="route('admin.index')"
                                >
                                    Gestion du site
                                </BreezeDropdownLink>

                                <BreezeDropdownLink
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
                        <div
                            class="hidden h-full space-x-4 lg:-my-px lg:ml-10 lg:flex"
                        >
                            <BreezeNavLink
                                class="text-white"
                                :href="route('login')"
                                :active="route().current('login')"
                            >
                                Connexion
                            </BreezeNavLink>
                            <BreezeNavLink
                                class="text-white"
                                :href="route('register')"
                                :active="route().current('register')"
                            >
                                Inscription
                            </BreezeNavLink>
                        </div>
                    </template>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center lg:hidden">
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                    >
                        <svg
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
                        </svg>
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
                <BreezeResponsiveNavLink
                    :href="route('favoris.index')"
                    :active="route().current('favoris.index')"
                >
                    Mes Favoris
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    v-if="user && !user.structures.length > 0"
                    :href="route('structures.create')"
                    :active="route().current('structures.create')"
                >
                    Inscrire votre structure
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
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
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    v-if="user && $page.props.user_can.view_admin"
                    :href="route('admin.index')"
                >
                    Gestion du site
                </BreezeResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-gray-200 py-1">
                <div class="px-4" v-if="user">
                    <div class="text-base font-medium text-gray-800">
                        {{ user.name }}
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                        {{ user.email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1" v-if="user">
                    <BreezeResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Se déconnecter
                    </BreezeResponsiveNavLink>
                </div>

                <div v-else>
                    <BreezeResponsiveNavLink
                        :href="route('login')"
                        :active="route().current('login')"
                    >
                        Connexion
                    </BreezeResponsiveNavLink>
                    <BreezeResponsiveNavLink
                        :href="route('register')"
                        :active="route().current('register')"
                    >
                        Inscription
                    </BreezeResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
