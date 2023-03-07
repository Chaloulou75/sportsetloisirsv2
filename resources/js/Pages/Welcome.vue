<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { ArrowNarrowRightIcon, CheckIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    categories: Object,
    sports: Object,
    categoriesCount: Number,
    sportsCount: Number,
    clubsCount: Number,
    lastClubs: Object,
});
</script>

<template>
    <Head
        title="Accueil"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 clubs référencés."
    />

    <AppLayout>
        <template
            #header
            class="hidden bg-white shadow-lg shadow-sky-600/20 sm:block"
        >
            <div class="mx-auto max-w-7xl px-2 py-6 sm:px-3 lg:px-6">
                <div
                    class="grid grid-cols-1 place-items-start gap-2 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-10 lg:place-items-center"
                >
                    <BreezeNavLink
                        :href="route('category.show', category.slug)"
                        :active="
                            route().current('category.show', category.slug)
                        "
                        v-for="(category, index) in categories"
                        :key="index"
                        >{{ category.name }}</BreezeNavLink
                    >
                </div>
            </div>
        </template>
        <div>
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
                        {{ clubsCount }} clubs référencés!
                    </div>
                </div>
                <div
                    class="absolute right-0 top-10 mx-2 hidden py-10 sm:top-0 md:block"
                >
                    <div
                        class="rotate-3 bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text py-2 text-xl font-bold text-transparent sm:text-3xl"
                    >
                        {{ sportsCount }} activités disponibles!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 right-0 mx-2 my-24 hidden md:block"
                >
                    <div
                        class="-rotate-3 bg-gradient-to-br from-sky-600 to-blue-600 bg-clip-text py-2 text-xl font-bold text-transparent sm:text-2xl"
                    >
                        + 80 villes!
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
                            <span class="font-semibold">{{ sportsCount }}</span>
                            <span class="italic"> disciplines différentes</span
                            >. Il y en a pour tous les gouts : sports de
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
                            Plus de {{ clubsCount }} clubs référencés
                        </h3>
                        <p class="text-gray-600">
                            De très nombreux clubs référencés, prêt à vous
                            accueillir et vous accompagner dans la pratique de
                            votre discipline favorite !
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
                            Pour chaque clubs, vous pourrez y trouver les lieux
                            de pratique, les horaires et les tarifs. Un maximum
                            de détails pour vous aider dans votre choix !
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
                    <!-- <Link
                :href="route('sports.show', sport.slug)"
                :active="route().current('sports.show', sport.slug)"
                v-for="sport in sports"
                :key="sport.id"
                class="btn-component"
                >{{ sport.name }}</Link
            > -->
                </div>
                <div class="mb-4 flex items-center justify-center">
                    <!-- <Link
                :href="route('sports.index')"
                class="flex items-center justify-center px-4 py-3 text-lg text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
            >
                Et beaucoup d'autres
                <span>
                    <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                </span>
            </Link> -->
                </div>
            </section>

            <section
                class="mx-auto flex max-w-7xl flex-col justify-between px-2 py-8 md:flex-row md:py-20"
            >
                <div class="mb-6 max-w-full grow sm:mb-0">
                    <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                        Les derniers clubs inscrits:
                    </h3>
                    <div
                        class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <!-- <Link
                    :href="route('clubs.show', club.slug)"
                    :active="route().current('clubs.show', club.slug)"
                    v-for="club in lastClubs"
                    :key="club.id"
                    class="flex flex-col items-center justify-center h-24 px-2 py-1 text-lg text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg md:h-28"
                >
                    <div class="mb-1">{{ club.name }}</div>
                    <div class="mb-1 text-xs">{{ club.category.name }}</div>
                    <div class="mb-1 text-xs uppercase">
                        {{ club.city }} ({{ club.zip_code }})
                    </div>
                </Link> -->
                    </div>
                    <div class="mb-4 flex items-center justify-center">
                        <!-- <Link
                    :href="route('clubs.index')"
                    class="flex items-center justify-center px-4 py-3 text-lg text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                >
                    Et beaucoup d'autres
                    <span>
                        <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                    </span>
                </Link> -->
                    </div>
                </div>
                <div class="mx-auto max-w-sm text-gray-700 md:px-8">
                    <h3
                        class="pb-6 text-center text-2xl font-semibold text-gray-700 sm:text-left"
                    >
                        Inscrivez votre club:
                    </h3>
                    <p
                        class="pb-6 text-center text-lg font-semibold text-gray-700 sm:text-left"
                    >
                        C'est gratuit
                    </p>
                    <p
                        class="pb-6 text-justify text-base font-medium text-gray-700 sm:text-left"
                    >
                        Pour inscrire votre club, il vous suffit de créer un
                        compte. Vous aurez accès à l'ensemble de nos services
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
                        />Présentation de votre club
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
                            :href="route('inscriptions.create')"
                            class="hover:bg-darkblue flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-700 shadow-lg transition duration-150 hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Créer votre structure
                            <span>
                                <ArrowNarrowRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                        <Link
                            v-else
                            :href="route('register')"
                            class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-700 shadow-lg transition duration-150 hover:bg-green-200 hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            S'inscrire
                            <span>
                                <ArrowNarrowRightIcon class="ml-2 h-6 w-6" />
                            </span>
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
