<script setup>
import { ref, computed } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { HeartIcon } from "@heroicons/vue/24/solid";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
const page = usePage();
const user = computed(() => page.props.auth.user);
const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="border-b border-gray-100 bg-white">
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

                    <!-- Navigation Links -->
                    <div
                        class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex"
                    ></div>
                </div>

                <div class="hidden h-full lg:ml-6 lg:flex lg:items-center">
                    <!-- Settings Dropdown -->
                    <div class="relative ml-3" v-if="user">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ user.name }}

                                        <svg
                                            class="-mr-0.5 ml-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink
                                    :href="route('favoris.index')"
                                    class="flex items-center"
                                >
                                    Mes favoris
                                    <HeartIcon
                                        class="ml-2 h-4 w-4 text-red-500"
                                    />
                                </BreezeDropdownLink>
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
                                class="has-tooltip"
                                :href="route('favoris.index')"
                                :active="route().current('favoris.index')"
                            >
                                <span
                                    class="tooltip top-16 rounded bg-gray-200 p-2 text-gray-800 shadow-lg md:mr-5"
                                    >Vous pouvez retrouver la liste de vos
                                    activités et structures favorites grâce aux
                                    données enregistrées anonymement dans votre
                                    navigateur. Il vous suffit, pour conserver
                                    ces informations, de
                                    <Link
                                        :href="route('register')"
                                        class="font-semibold"
                                        >créer un compte</Link
                                    >
                                    sur sports-et-loisirs.fr.</span
                                >

                                <HeartIcon class="h-8 w-8 text-red-500" />
                            </BreezeNavLink>

                            <BreezeNavLink
                                :href="route('login')"
                                :active="route().current('login')"
                            >
                                Connexion
                            </BreezeNavLink>
                            <BreezeNavLink
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
                    :href="route('structures.edit', user.structures[0].slug)"
                    :active="route().current('structures.edit')"
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
