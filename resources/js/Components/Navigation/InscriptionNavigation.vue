<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import ModalDeleteStructure from "@/Components/Modals/ModalDeleteStructure.vue";

const emit = defineEmits(["eventFromChild"]);

const emitEvent = (message) => {
    emit("eventFromChild", message);
};

const props = defineProps({
    structure: Object,
    categorie: Object,
    displayActivity: Boolean,
    displayTarif: Boolean,
    displayPlanning: Boolean,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    can: Object,
});

const showingInsNavigationDropdown = ref(false);

const showModal = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
<template>
    <nav
        class="min-h-full w-full flex-col justify-between bg-white md:flex md:w-1/5"
    >
        <div class="w-full space-y-8 px-4 py-2 md:sticky md:inset-x-0 md:top-0">
            <!-- Hamburger -->
            <div class="flex h-16 justify-between">
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
                <div class="flex shrink-0 items-center">
                    <Link :href="route('welcome')">
                        <BreezeApplicationLogo class="block h-9 w-auto" />
                    </Link>
                </div>
            </div>

            <ul
                class="hidden h-full w-full space-y-3 md:flex md:flex-col md:items-start"
            >
                <!-- Accueil -->
                <li v-if="structure" class="w-full">
                    <Link
                        :href="route('structures.gestion.index', structure)"
                        :active="route().current('structures.gestion.index')"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'structures.gestion.index',
                                structure
                            ),
                        }"
                    >
                        Accueil
                    </Link>
                </li>
                <!-- Réservations -->
                <li class="w-full">
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-blue-500 hover:text-white"
                        >
                            <span class="text-sm font-medium">
                                Réservations
                            </span>

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
                        <ul class="space-y-1">
                            <li>
                                <Link
                                    v-if="user"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            structure.slug
                                        )
                                    "
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    Solde
                                </Link>
                            </li>
                            <li>
                                <Link
                                    v-if="user"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            structure.slug
                                        )
                                    "
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    <div>A valider</div>
                                    <div class="p-1">
                                        {{ pendingReservationsCount }}
                                    </div>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    v-if="user"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            structure.slug
                                        )
                                    "
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    <div>En cours</div>
                                    <div class="p-1">
                                        {{ confirmedReservationsCount }}
                                    </div>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    v-if="user"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            structure.slug
                                        )
                                    "
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    <div>Messages</div>
                                    <div class="p-1">4</div>
                                </Link>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- Publications -->
                <li class="w-full">
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-blue-500 hover:text-white"
                        >
                            <span class="text-sm font-medium">
                                Publications
                            </span>

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
                        <ul class="space-y-1">
                            <!-- ranking -->
                            <li v-if="structure">
                                <a
                                    href=""
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    Ranking Score
                                </a>
                            </li>

                            <!-- structure -->
                            <li v-if="structure">
                                <Link
                                    v-if="
                                        user &&
                                        user.structures.length > 0 &&
                                        can.update
                                    "
                                    :href="route('structures.edit', structure)"
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'structures.edit',
                                                structure
                                            ),
                                    }"
                                >
                                    Ma structure
                                </Link>
                            </li>
                            <!-- activite -->
                            <li>
                                <Link
                                    :href="
                                        route(
                                            'structures.activites.index',
                                            structure
                                        )
                                    "
                                    v-if="can.update"
                                    class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'structures.activites.index',
                                                structure
                                            ),
                                    }"
                                >
                                    Mes activités
                                </Link>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- stats -->
                <li class="w-full">
                    <a
                        href="/"
                        class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                        >Statistiques
                    </a>
                </li>
                <!-- avis -->
                <li class="w-full">
                    <a
                        href="/"
                        class="flex items-center justify-between rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                        >Avis Clients
                    </a>
                </li>
                <!-- blog -->
                <li class="w-full">
                    <a
                        href="/"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                        >Articles de blog
                    </a>
                </li>
            </ul>
        </div>

        <!-- <div class="inset-x-0 bottom-0 border-t border-gray-100">
            <Link
                :href="route('profile.edit')"
                class="hidden items-center gap-2 bg-white p-4 hover:bg-gray-50 md:flex"
            >
                <UserCircleIcon class="mr-2 h-8 w-8" />

                <div>
                    <p class="text-xs">
                        <strong class="block font-medium">{{
                            user.name
                        }}</strong>

                        <span> {{ user.email }}</span>
                    </p>
                </div>
            </Link>
        </div> -->
        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingInsNavigationDropdown,
                hidden: !showingInsNavigationDropdown,
            }"
            class="md:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <BreezeResponsiveNavLink
                    :href="route('structures.gestion.index', structure.slug)"
                    :active="
                        route().current(
                            'structures.gestion.index',
                            structure.slug
                        )
                    "
                >
                    Accueil Gestion
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('structures.gestion.index', structure.slug)"
                    :active="route('structures.gestion.index', structure.slug)"
                >
                    Mes Reservations
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    v-if="user && user.structures.length > 0 && can.update"
                    :href="route('structures.edit', structure)"
                    :active="route().current('structures.edit', structure)"
                >
                    Ma structure
                </BreezeResponsiveNavLink>
                <BreezeResponsiveNavLink
                    :href="route('structures.activites.index', structure)"
                    v-if="can.update"
                    :active="
                        route().current('structures.activites.index', structure)
                    "
                >
                    Mes activités
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
            </div>
        </div>
    </nav>
</template>
