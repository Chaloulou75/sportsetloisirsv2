<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { ArrowNarrowRightIcon, CheckIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    categories: Object,
    disciplines: Object,
    categoriesCount: Number,
    disciplinesCount: Number,
    structuresCount: Number,
    lastStructures: Object,
});
</script>

<template>
    <Head
        title="Accueil"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 structures référencées."
    />

    <AppLayout>
        <header>
            <div class="hidden bg-white shadow-lg shadow-sky-600/20 sm:block">
                <div class="px-2 py-6 mx-auto max-w-7xl sm:px-3 lg:px-6">
                    <div
                        class="grid grid-cols-1 gap-2 place-items-start sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-10 lg:place-items-center"
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
            </div>
        </header>
        <div>
            <section
                class="relative flex flex-col items-center justify-center min-h-full mx-auto my-16 bg-transparent font-Mochiy max-w-7xl md:my-0 md:min-h-screen"
            >
                <div
                    class="max-w-sm text-5xl font-black text-center text-white -rotate-3 md:max-w-3xl md:text-8xl"
                >
                    <h1
                        class="px-4 py-2 text-transparent bg-gradient-to-br from-green-500 via-blue-600 to-orange-400 bg-clip-text"
                    >
                        Trouvez votre Club
                        <span class="block">près de chez vous!</span>
                    </h1>
                </div>
                <div
                    class="absolute top-0 left-0 hidden mx-2 my-10 sm:top-10 md:block"
                >
                    <div
                        class="text-xl font-bold text-transparent -rotate-3 bg-gradient-to-br from-red-500 to-blue-500 bg-clip-text sm:text-3xl"
                    >
                        {{ structuresCount }} clubs référencés!
                    </div>
                </div>
                <div
                    class="absolute right-0 hidden py-10 mx-2 top-10 sm:top-0 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent rotate-3 bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text sm:text-3xl"
                    >
                        {{ disciplinesCount }} disciplines disponibles!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 right-0 hidden mx-2 my-24 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent -rotate-3 bg-gradient-to-br from-sky-600 to-blue-600 bg-clip-text sm:text-2xl"
                    >
                        + 80 villes!
                    </div>
                </div>
                <div
                    class="absolute bottom-0 left-0 hidden mx-2 my-20 md:block"
                >
                    <div
                        class="py-2 text-xl font-bold text-transparent rotate-3 bg-gradient-to-br from-orange-400 to-red-500 bg-clip-text sm:text-2xl"
                    >
                        +100 000 pratiquants!
                    </div>
                </div>
            </section>

            <section
                class="px-2 py-8 mx-auto bg-transparent max-w-7xl md:py-20"
            >
                <div
                    class="flex flex-col items-start justify-between space-y-12 md:flex-row md:space-y-0 md:space-x-20"
                >
                    <div
                        class="px-6 py-4 bg-white rounded-lg shadow-xl shadow-red-500"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-red-500 decoration-4 underline-offset-2"
                        >
                            Nombreuses disciplines
                        </h3>
                        <p class="text-gray-600">
                            Trouvez un club de sport ou un cours collectif dans
                            plus de
                            <span class="font-semibold">{{
                                disciplinesCount
                            }}</span>
                            <span class="italic"> disciplines différentes</span
                            >. Il y en a pour tous les gouts : sports
                            collectifs, sports de balle, sport de combats,
                            danse, musique, ...
                        </p>
                    </div>
                    <div
                        class="px-6 py-4 bg-white rounded-lg shadow-lg shadow-sky-600 md:translate-y-20"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-sky-600 decoration-4 underline-offset-2"
                        >
                            Plus de {{ structuresCount }} structures référencées
                        </h3>
                        <p class="text-gray-600">
                            De très nombreuses structures référencées, prêt à
                            vous accueillir et vous accompagner dans la pratique
                            de votre discipline favorite !
                        </p>
                    </div>
                    <div
                        class="px-6 py-4 bg-white rounded-lg shadow-lg shadow-yellow-500"
                    >
                        <h3
                            class="pb-4 text-lg font-semibold text-gray-600 underline decoration-yellow-500 decoration-4 underline-offset-2"
                        >
                            Des fiches détaillées
                        </h3>
                        <p class="text-gray-600">
                            Pour chaque structures, vous pourrez y trouver les
                            activités, les lieux de pratique, les horaires et
                            les tarifs. Un maximum de détails pour vous aider
                            dans votre choix !
                        </p>
                    </div>
                </div>
            </section>

            <section class="px-2 py-8 mx-auto max-w-7xl md:py-20">
                <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                    Besoin d'inspiration:
                </h3>
                <div
                    class="mb-8 grid h-auto grid-cols-1 place-items-stretch gap-4 px-1.5 sm:grid-cols-2 md:grid-cols-3 md:px-0 lg:grid-cols-4"
                >
                    <Link
                        :href="route('discipline.show', discipline.slug)"
                        :active="
                            route().current('discipline.show', discipline.slug)
                        "
                        v-for="discipline in disciplines"
                        :key="discipline.id"
                        class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >{{ discipline.name }}</Link
                    >
                </div>
                <div class="flex items-center justify-center mb-4">
                    <Link
                        :href="route('discipline.index')"
                        class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        Et beaucoup d'autres
                        <span>
                            <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                        </span>
                    </Link>
                </div>
            </section>

            <section
                class="flex flex-col justify-between px-2 py-8 mx-auto max-w-7xl md:flex-row md:py-20"
            >
                <div class="max-w-full mb-6 grow sm:mb-0">
                    <h3 class="pb-6 text-2xl font-semibold text-gray-700">
                        Les dernieres structures inscrites:
                    </h3>
                    <div
                        class="grid h-auto grid-cols-1 gap-4 mb-8 place-items-stretch sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <Link
                            :href="route('structure.show', structure.slug)"
                            :active="
                                route().current(
                                    'structure.show',
                                    structure.slug
                                )
                            "
                            v-for="structure in lastStructures"
                            :key="structure.id"
                            class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            <div class="mb-1">{{ structure.name }}</div>
                            <div class="mb-1 text-xs">
                                {{ structure.category.name }}
                            </div>
                            <div class="mb-1 text-xs">
                                {{ structure.city }} ({{ structure.zip_code }})
                            </div>
                        </Link>
                    </div>
                    <div class="flex items-center justify-center mb-4">
                        <Link
                            :href="route('structure.index')"
                            class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Et beaucoup d'autres
                            <span>
                                <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                            </span>
                        </Link>
                    </div>
                </div>
                <div class="max-w-sm mx-auto text-gray-700 md:px-8">
                    <h3
                        class="pb-6 text-2xl font-semibold text-center text-gray-700 sm:text-left"
                    >
                        Inscrivez votre structure:
                    </h3>
                    <p
                        class="pb-6 text-lg font-semibold text-center text-gray-700 sm:text-left"
                    >
                        C'est gratuit
                    </p>
                    <p
                        class="pb-6 text-base font-medium text-justify text-gray-700 sm:text-left"
                    >
                        Pour inscrire votre structure, il vous suffit de créer
                        un compte. Vous aurez accès à l'ensemble de nos services
                        gratuitement. Vous pourrez ajouter un grand nombre
                        d'informations, et diffuser vos évenements.
                    </p>
                    <p
                        class="pb-6 text-lg font-semibold text-center text-gray-700 sm:text-left"
                    >
                        Une fiche complète
                    </p>
                    <p class="flex items-center mb-2 font-medium">
                        <CheckIcon
                            class="w-5 h-5 mr-2 text-blue-800"
                        />Présentation de votre structure
                    </p>
                    <p class="flex items-center mb-2 font-medium">
                        <CheckIcon class="w-5 h-5 mr-2 text-blue-800" />Lieux de
                        pratique
                    </p>
                    <p class="flex items-center mb-2 font-medium">
                        <CheckIcon class="w-5 h-5 mr-2 text-blue-800" />Horaires
                        de pratique
                    </p>
                    <p class="flex items-center mb-2 font-medium">
                        <CheckIcon class="w-5 h-5 mr-2 text-blue-800" />Tarifs
                    </p>
                    <p class="flex items-center mb-8 font-medium">
                        <CheckIcon class="w-5 h-5 mr-2 text-blue-800" />Photos
                    </p>
                    <div class="flex items-center justify-center mb-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('structure.create')"
                            class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Créer votre structure
                            <span>
                                <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                            </span>
                        </Link>
                        <Link
                            v-else
                            :href="route('register')"
                            class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            S'inscrire
                            <span>
                                <ArrowNarrowRightIcon class="w-6 h-6 ml-2" />
                            </span>
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
