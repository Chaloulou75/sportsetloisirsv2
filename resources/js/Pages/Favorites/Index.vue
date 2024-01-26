<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import StructureCard from "@/Components/Structures/StructureCard.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import ProduitCard from "@/Components/Produits/ProduitCard.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    structures: Object,
    activites: Object,
    produits: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});
</script>
<template>
    <Head
        title="Mes Favoris"
        :description="'Vos structures et activités favorites.'"
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Vos Favoris </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
                                        Accueil
                                    </span>
                                </Link>
                            </li>

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('favoris.index')"
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    Favoris
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <div class="py-6">
            <template v-if="produits.length > 0">
                <h2
                    class="my-8 w-full text-center text-2xl font-semibold text-slate-800"
                >
                    Vos produits favoris:
                </h2>
                <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-3"
                    >
                        <ProduitCard
                            v-for="(produit, index) in produits"
                            :key="produit.id"
                            :index="index"
                            :discipline="produit.discipline"
                            :produit="produit"
                            :link="
                                route('structures.activites.show', {
                                    activite: produit.activite,
                                })
                            "
                            :data="{
                                produit: produit.id,
                            }"
                        />
                    </div>
                </div>
            </template>

            <template v-if="activites.length > 0">
                <h2
                    class="my-8 w-full text-center text-2xl font-semibold text-slate-800"
                >
                    Vos activités favorites:
                </h2>
                <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-3"
                    >
                        <ActiviteCard
                            v-for="(activite, index) in activites"
                            :key="activite.id"
                            :index="index"
                            :activite="activite"
                            :link="
                                route('structures.activites.show', {
                                    activite: activite,
                                })
                            "
                        />
                    </div>
                </div>
            </template>

            <template v-if="structures.length > 0">
                <h2
                    class="my-8 w-full text-center text-2xl font-semibold text-slate-800"
                >
                    Vos structures favorites:
                </h2>
                <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-3"
                    >
                        <StructureCard
                            v-for="(structure, index) in structures"
                            :key="structure.id"
                            :index="index"
                            :structure="structure"
                            :link="
                                route('structures.show', {
                                    structure: structure,
                                })
                            "
                        />
                    </div>
                </div>
            </template>
            <template
                v-if="
                    structures.length === 0 &&
                    activites.length === 0 &&
                    produits.length === 0
                "
            >
                <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                    <p class="font-medium text-gray-700">
                        Il n'y a pas encore d'activités dans vos favoris.
                    </p>
                </div>
            </template>
            <template v-if="!$page.props.auth.user">
                <div
                    class="mx-auto my-6 w-full rounded-md border border-gray-200 bg-blue-200 px-2 py-4 md:w-4/5 md:px-6 lg:px-8"
                >
                    <p
                        class="py-2 text-base font-medium leading-relaxed text-gray-800"
                    >
                        Vous pouvez retrouver la liste de vos activités et
                        structures favorites grâce aux données enregistrées
                        anonymement dans votre navigateur. Vos favoris seront
                        conservés 48 heures. Pour les conserver sans limite de
                        temps, Il vous suffit de
                        <Link :href="route('register')" class="font-semibold"
                            >créer un compte</Link
                        >
                        sur sports-et-loisirs.fr.
                    </p>
                </div>
            </template>
        </div>
    </ResultLayout>
</template>
