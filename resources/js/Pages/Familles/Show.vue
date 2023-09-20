<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon } from "@heroicons/vue/24/outline";

defineProps({
    familles: Object,
    famille: Object,
    listDisciplines: Object,
    allCities: Object,
});
</script>

<template>
    <Head
        :title="famille.name"
        :description="
            'Vous souhaitez pratiquer un sport de ' +
            famille.name +
            ' en France ? ' +
            famille.activites_count +
            ' activités sur notre site prêts à vous accueillir.'
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    <h1
                        class="border-b-2 border-slate-400 pb-2 text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                    >
                        {{ famille.name }}
                    </h1>
                </template>
                <template v-slot:ariane>
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
                                    :href="route('familles.show', famille.slug)"
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ famille.name }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <template v-if="famille.disciplines">
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8"
                >
                    <div
                        class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                    >
                        <Link
                            :href="route('disciplines.show', discipline.slug)"
                            :active="
                                route().current(
                                    'disciplines.show',
                                    discipline.slug
                                )
                            "
                            v-for="discipline in famille.disciplines"
                            :key="discipline.id"
                            class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        >
                            {{ discipline.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Pas encore de structures inscrites dans la rubrique
                        <span class="font-semibold text-gray-800">{{
                            famille.name
                        }}</span>
                    </p>
                </div>
            </div>
        </template>
    </ResultLayout>
</template>
