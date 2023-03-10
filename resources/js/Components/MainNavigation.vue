<script setup>
import { ref } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex items-center shrink-0">
                        <Link :href="route('welcome')">
                            <BreezeApplicationLogo class="block w-auto h-9" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                        <BreezeNavLink
                            :href="route('category.index')"
                            :active="route().current('category.index')"
                        >
                            Categories
                        </BreezeNavLink>
                        <BreezeNavLink
                            :href="route('discipline.index')"
                            :active="route().current('discipline.index')"
                        >
                            Disciplines
                        </BreezeNavLink>
                        <!-- <BreezeNavLink
                            :href="route('cities.index')"
                            :active="route().current('cities.index')"
                        >
                            Villes
                        </BreezeNavLink>
                        <BreezeNavLink
                            :href="route('departments.index')"
                            :active="route().current('departments.index')"
                        >
                            Departements
                        </BreezeNavLink> -->
                        <!-- <BreezeNavLink
                            :href="route('clubs.index')"
                            :active="route().current('clubs.index')"
                        >
                            Clubs
                        </BreezeNavLink> -->
                    </div>
                </div>

                <div class="hidden h-full lg:ml-6 lg:flex lg:items-center">
                    <!-- Settings Dropdown -->
                    <div class="relative ml-3" v-if="$page.props.auth.user">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
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
                                    :href="route('structure.create')"
                                >
                                    Inscrire une structure
                                </BreezeDropdownLink>
                                <!-- <BreezeDropdownLink
                                    :href="route('clubs.create')"
                                >
                                    Créer un club
                                </BreezeDropdownLink> -->
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
                <div class="flex items-center -mr-2 lg:hidden">
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                    >
                        <svg
                            class="w-6 h-6"
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
            <div class="pt-2 pb-3 space-y-1">
                <BreezeResponsiveNavLink
                    :href="route('category.index')"
                    :active="route().current('category.index')"
                >
                    Categories
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('discipline.index')"
                    :active="route().current('discipline.index')"
                >
                    Disciplines
                </BreezeResponsiveNavLink>
                <!-- <BreezeResponsiveNavLink
                    :href="route('cities.index')"
                    :active="route().current('cities.index')"
                >
                    Villes
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('departments.index')"
                    :active="route().current('departments.index')"
                >
                    Departements
                </BreezeResponsiveNavLink> -->
                <!-- <BreezeResponsiveNavLink
                    :href="route('clubs.index')"
                    :active="route().current('clubs.index')"
                >
                    Clubs
                </BreezeResponsiveNavLink> -->
                <BreezeResponsiveNavLink
                    v-if="$page.props.auth.user"
                    :href="route('structure.create')"
                    :active="route().current('structure.create')"
                >
                    Inscrire votre structure
                </BreezeResponsiveNavLink>
                <!-- <BreezeResponsiveNavLink
                    v-if="$page.props.auth.user"
                    :href="route('clubs.create')"
                    :active="route().current('clubs.create')"
                >
                    Créer un club
                </BreezeResponsiveNavLink> -->
            </div>

            <!-- Responsive Settings Options -->
            <div class="py-1 border-t border-gray-200">
                <div class="px-4" v-if="$page.props.auth.user">
                    <div class="text-base font-medium text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                        {{ $page.props.auth.user.email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1" v-if="$page.props.auth.user">
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
