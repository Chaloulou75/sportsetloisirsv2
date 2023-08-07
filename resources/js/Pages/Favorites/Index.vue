<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import StructureCard from "@/Components/Structures/StructureCard.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";

const props = defineProps({
    structures: Object,
    activites: Object,
    familles: Object,
    filters: Object,
});
</script>
<template>
    <Head
        title="Mes Favoris"
        :description="'Vos structures et activités favorites.'"
    />
    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-xl font-semibold leading-tight tracking-widest text-gray-800"
                >
                    Vos Favoris
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol
                        class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                    >
                        <li class="flex items-center">
                            <Link
                                preserve-scroll
                                :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
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
            </div>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Vous pouvez désormais retrouver la liste de vos activités et
                structures favorites et grâce aux données enregistrées
                anonymement dans votre navigateur. Il vous suffit pour conserver
                ces informations de
                <Link :href="route('register')" class="font-semibold"
                    >créer un compte</Link
                >
                sur sports-et-loisirs.fr.
            </p>
        </template>

        <div class="py-6">
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
                                    structure: activite.structure.slug,
                                    activite: activite.id,
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
                        />
                    </div>
                </div>
            </template>
            <template v-else-if="structures.length > 0 && activites.length > 0">
                <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                    <p class="font-medium text-gray-700">
                        Il n'y a pas encore d' activites dans vos favoris.
                    </p>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
