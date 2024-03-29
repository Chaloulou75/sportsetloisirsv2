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
                        :href="route('admin.tarifs.index')"
                        :active="route().current('admin.tarifs.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.tarifs.index'),
                        }"
                    >
                        Gestion des types de tarifs
                    </Link>
                </li>
                <li class="w-full">
                    <Link
                        :href="route('admin.disciplines.index')"
                        :active="route().current('admin.disciplines.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'admin.disciplines.index'
                            ),
                        }"
                    >
                        Gestion du contenu (disciplines, catégories, critères,
                        tarifs)
                    </Link>
                </li>
                <li class="w-full">
                    <Link
                        :href="route('admin.structures.index')"
                        :active="route().current('admin.structures.index')"
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
                        :href="route('admin.blog.index')"
                        :active="route().current('admin.blog.index')"
                        class="block rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('admin.blog.index'),
                        }"
                    >
                        Gestion du Blog
                    </Link>
                </li>
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
                <BreezeResponsiveNavLink :href="route('admin.tarifs.index')">
                    Gestion des tarifs
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
