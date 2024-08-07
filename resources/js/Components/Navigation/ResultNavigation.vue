<script setup>
import { ref, computed } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import AutocompleteCityNav from "@/Components/Navigation/AutocompleteCityNav.vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import Badge from "primevue/badge";
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
    isCriteresVisible: Boolean,
});
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => page.props.user_can.view_admin);
const adminNotificationsCount = computed(
    () => page.props.admin_notifications_count
);
const structureNotifCount = computed(() => {
    if (
        page.props.auth.user.structures &&
        page.props.structures_notifications_count
    ) {
        return (
            page.props.structures_notifications_count[
                page.props.auth.user.structures[0].id
            ] || 0
        );
    }
    return 0;
});

const productCountInSession = computed(
    () => page.props.products_reservations_count
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
        class="fixed inset-x-0 top-0 z-[999] border-b border-gray-100 bg-slate-900/70 backdrop-blur-sm"
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

                <div
                    v-if="!route().current('welcome')"
                    class="hidden space-x-8 md:ml-10 md:flex md:w-full"
                >
                    <section
                        class="mx-auto flex w-full items-center justify-center px-2 md:flex-row md:space-y-0"
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
                                class="flex w-full items-center justify-center rounded border border-gray-300 px-2 py-2 text-sm font-medium shadow-sm focus:outline-none focus:ring focus:ring-gray-500 focus:ring-offset-2 md:w-auto"
                            >
                                <MagnifyingGlassIcon
                                    class="h-5 w-5 md:h-7 md:w-7"
                                />
                                <span class="sr-only">Rechercher</span>
                            </button>
                        </div>
                    </section>
                </div>

                <div class="hidden h-full md:flex md:items-center md:space-x-2">
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
                            class="h-8 w-8 text-white hover:text-indigo-500 focus:text-indigo-500"
                        />
                        <span
                            v-if="productCountInSession"
                            class="absolute right-0 top-0 -mr-1 -mt-1 rounded-full bg-blue-500 px-1 text-xs text-white"
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
                                        class="relative inline-flex items-center px-1 py-2 text-white hover:text-indigo-500 focus:text-indigo-500"
                                    >
                                        <UserIcon class="relative h-8 w-8" />
                                        <span
                                            v-if="
                                                user &&
                                                user.unread_notifications_count ===
                                                    0
                                            "
                                            class="absolute right-0 top-0 z-20 mr-2.5 mt-1 rounded-full bg-green-500 p-1"
                                        >
                                        </span>

                                        <span
                                            v-if="
                                                user &&
                                                user.unread_notifications_count >
                                                    0
                                            "
                                            class="absolute end-1 top-1.5 -me-2 -mt-2 flex"
                                        >
                                            <span
                                                class="absolute inline-flex size-full animate-ping rounded-full bg-red-400 opacity-75 dark:bg-red-600"
                                            ></span>
                                            <span
                                                class="relative inline-flex rounded-full bg-green-500 px-1.5 py-0.5 text-xs text-white"
                                            >
                                                {{
                                                    user.unread_notifications_count +
                                                    adminNotificationsCount +
                                                    structureNotifCount
                                                }}
                                            </span>
                                        </span>
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
                                    :href="route('posts.index')"
                                    preserve-scroll
                                >
                                    Blog
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    v-if="user && user.customer"
                                    :href="
                                        route('customers.show', {
                                            customer: user.customer,
                                        })
                                    "
                                    class="relative inline-flex w-full items-center justify-between"
                                >
                                    Mes réservations
                                    <Badge
                                        v-if="
                                            user &&
                                            user.unread_notifications_count > 0
                                        "
                                        severity="success"
                                        :value="user.unread_notifications_count"
                                    />
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    :href="route('profile.edit')"
                                    class="relative inline-flex w-full items-center justify-between"
                                >
                                    Mon profil
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    class="relative inline-flex w-full items-center justify-between"
                                    preserve-scroll
                                    v-if="user && user.structures.length > 0"
                                    :href="
                                        route(
                                            'structures.gestion.index',
                                            user.structures[0].slug
                                        )
                                    "
                                >
                                    Gestion de ma structure
                                    <Badge
                                        v-if="structureNotifCount > 0"
                                        severity="success"
                                        :value="structureNotifCount"
                                    />
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    preserve-scroll
                                    v-if="user && !user.structures.length > 0"
                                    :href="route('structures.create')"
                                >
                                    Inscrire une structure
                                </BreezeDropdownLink>
                                <BreezeDropdownLink
                                    class="relative inline-flex w-full items-center justify-between"
                                    preserve-scroll
                                    v-if="user && isAdmin"
                                    :href="route('admin.index')"
                                >
                                    Gestion du site
                                    <Badge
                                        v-if="adminNotificationsCount > 0"
                                        severity="success"
                                        :value="adminNotificationsCount"
                                    />
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
                <div class="-mr-2 flex items-center space-x-2 md:hidden">
                    <button
                        @click="showingSearchForm = !showingSearchForm"
                        type="button"
                        class="items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <MagnifyingGlassIcon class="h-6 w-6" />
                        <span class="sr-only">Rechercher</span>
                    </button>
                    <Link
                        preserve-scroll
                        :href="route('panier.index')"
                        :active="route().current('panier.index')"
                        class="relative items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <ShoppingCartIcon class="h-6 w-6" />
                        <Badge
                            v-if="productCountInSession > 0"
                            severity="success"
                            :value="productCountInSession"
                        />
                    </Link>
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="relative items-center justify-center rounded bg-transparent px-2 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <UserIcon class="h-6 w-6" />
                        <Badge
                            v-if="
                                user &&
                                user.unread_notifications_count +
                                    adminNotificationsCount +
                                    structureNotifCount ===
                                    0
                            "
                            severity="success"
                            class="absolute -right-1.5 -top-1.5"
                        />

                        <Badge
                            v-if="
                                user &&
                                user.unread_notifications_count +
                                    adminNotificationsCount +
                                    structureNotifCount >
                                    0
                            "
                            severity="success"
                            class="absolute -right-1.5 -top-1.5"
                            :value="
                                user.unread_notifications_count +
                                adminNotificationsCount +
                                structureNotifCount
                            "
                        />
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
            class="md:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <Link
                    preserve-scroll
                    class="block w-full border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    :href="route('posts.index')"
                    :active="route().current('posts.index')"
                >
                    Blog
                </Link>
                <Link
                    v-if="user && user.customer"
                    :href="route('customers.show', { customer: user.customer })"
                    preserve-scroll
                    class="inline-flex w-full items-center justify-between border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                >
                    Mes réservations
                    <Badge
                        v-if="user && user.unread_notifications_count > 0"
                        severity="success"
                        :value="user.unread_notifications_count"
                    />
                </Link>
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
                    class="inline-flex w-full items-center justify-between border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
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
                    <Badge
                        v-if="structureNotifCount > 0"
                        severity="success"
                        :value="structureNotifCount"
                    />
                </Link>
                <Link
                    preserve-scroll
                    class="inline-flex w-full items-center justify-between border-l-4 border-transparent py-2 pl-3 pr-4 text-left text-base font-medium text-white transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-300 hover:text-gray-50 focus:border-gray-600 focus:bg-gray-600 focus:text-gray-50 focus:outline-none"
                    v-if="user && isAdmin"
                    :href="route('admin.index')"
                >
                    Gestion du site
                    <Badge
                        v-if="adminNotificationsCount > 0"
                        severity="success"
                        :value="adminNotificationsCount"
                    />
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
            class="pb-3 pt-2 md:hidden"
        >
            <section
                class="mx-auto flex w-full flex-col items-center justify-center space-y-2 px-2 md:hidden md:flex-row md:space-y-0"
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
                        class="flex w-full items-center justify-center rounded-md border border-gray-500 px-2 py-2 text-base font-semibold shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                    >
                        <MagnifyingGlassIcon class="mr-2 h-6 w-6" />
                        <span class="">Rechercher</span>
                    </button>
                </div>
            </section>
        </div>
    </nav>
</template>
