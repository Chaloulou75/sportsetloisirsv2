<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const props = defineProps({});

const showingInsNavigationDropdown = ref(false);

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
<template>
    <nav
        class="min-h-full w-full flex-col justify-between border-r border-gray-100 bg-gray-50 md:flex md:w-1/6"
    >
        <div class="w-full space-y-6 md:sticky md:inset-x-0 md:top-0">
            <!-- Hamburger logo-->
            <div class="flex h-16 justify-between bg-blue-100 pl-3">
                <div class="flex shrink-0 items-center">
                    <Link :href="route('welcome')">
                        <BreezeApplicationLogo class="block h-9 w-auto" />
                    </Link>
                </div>
                <div class="-ml-2 flex items-center md:hidden">
                    <button
                        @click="
                            showingInsNavigationDropdown =
                                !showingInsNavigationDropdown
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
                                    hidden: showingInsNavigationDropdown,
                                    'inline-flex':
                                        !showingInsNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingInsNavigationDropdown,
                                    'inline-flex': showingInsNavigationDropdown,
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

            <ul
                class="hidden h-full w-full space-y-3 md:flex md:flex-col md:items-start"
            >
                <li class="w-full">
                    <Link
                        :href="route('admin.index')"
                        :active="route().current('admin.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.index'),
                        }"
                    >
                        Accueil Admin
                    </Link>
                </li>

                <li class="w-full">
                    <Link
                        :href="route('admin.users.index')"
                        :active="route().current('admin.users.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.users.index'),
                        }"
                    >
                        Gestion des utilisateurs
                    </Link>
                </li>

                <li class="w-full">
                    <Link
                        :href="route('admin.categories.index')"
                        :active="route().current('admin.categories.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'admin.categories.index'
                            ),
                        }"
                    >
                        Gestion des catégories
                    </Link>
                </li>

                <li class="w-full">
                    <Link
                        :href="route('admin.criteres.index')"
                        :active="route().current('admin.criteres.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'admin.criteres.index'
                            ),
                        }"
                    >
                        Gestion des criteres
                    </Link>
                </li>

                <li class="w-full">
                    <Link
                        :href="route('admin.structures.index')"
                        :active="route().current('admin.criteres.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'admin.structures.index'
                            ),
                        }"
                    >
                        Gestion des structures et activités
                    </Link>
                </li>

                <li class="w-full">
                    <Link
                        :href="route('admin.disciplines.index')"
                        :active="route().current('admin.criteres.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'admin.disciplines.index'
                            ),
                        }"
                    >
                        Gestion du contenu (disciplines, catégories, critères)
                    </Link>
                </li>
                <li class="w-full">
                    <Link
                        :href="route('admin.index')"
                        :active="route().current('admin.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.index'),
                        }"
                    >
                        Statistiques
                    </Link>
                </li>
                <li class="w-full">
                    <Link
                        :href="route('admin.index')"
                        :active="route().current('admin.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.index'),
                        }"
                    >
                        Communication
                    </Link>
                </li>
                <li class="w-full">
                    <Link
                        :href="route('admin.index')"
                        :active="route().current('admin.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.index'),
                        }"
                    >
                        Gestion du Blog
                    </Link>
                </li>

                <!-- <li class="w-full">
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                        open
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-sm bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            <span class="text-sm font-medium"> </span>

                            <span
                                class="shrink-0 transition duration-300 group-open:-rotate-180"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </summary>
                        <ul class="space-y-2">
                            <li class="mt-2">
                                 <Link
                                    class="flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                </Link>
                            </li>
                            <li>
                                 <Link
                                    class="flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                </Link>
                            </li>
                            <li>
                                 <Link
                                    class="flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                </Link>
                            </li>
                            <li>
                                <Link
                                    class="flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                </Link>
                            </li>
                        </ul>
                    </details>
                </li>

                <li class="w-full">
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                        open
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-sm bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            <span class="text-sm font-medium"> </span>

                            <span
                                class="shrink-0 transition duration-300 group-open:-rotate-180"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </summary>
                        <ul class="space-y-2">
                            <li class="mt-2">
                                 <Link
                                    class="flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                </Link>
                            </li>

                            <li>
                                 <Link
                                    class="group relative flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                    <div
                                        class="absolute inset-y-2 -right-2.5 h-5 w-5 rotate-45 bg-green-500"
                                    ></div>
                                </Link>
                            </li>

                            <li>
                                 <Link
                                    class="relative flex items-center justify-between rounded-sm bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-500 hover:text-white"
                                >
                                    <div
                                        class="absolute inset-y-2 -right-2.5 h-5 w-5 rotate-45 bg-green-500"
                                    ></div>
                                </Link>
                            </li>
                        </ul>
                    </details>
                </li>

                <li class="w-full">
                     <Link
                        class="flex items-center justify-between rounded-sm border-2 border-dotted border-sky-500 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                    >

                    </Link>
                </li> -->
            </ul>
        </div>
        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingInsNavigationDropdown,
                hidden: !showingInsNavigationDropdown,
            }"
            class="md:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <BreezeResponsiveNavLink :href="route('welcome')">
                    Accueil Site
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.index')">
                    Accueil Admin
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.users.index')">
                    Gestion utilisateurs
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('admin.structures.index')"
                >
                    Gestion des structures
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('admin.categories.index')"
                >
                    Gestion des catégories
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.criteres.index')">
                    Gestion des critères
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('admin.disciplines.index')"
                >
                    Gestion du contenu
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.index')">
                    Statistiques
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.index')">
                    Communication
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink :href="route('admin.index')">
                    Gestion du blog
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
            </div>
        </div>
    </nav>
</template>
