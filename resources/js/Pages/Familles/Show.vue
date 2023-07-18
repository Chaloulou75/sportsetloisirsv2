<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    famille: Object,
});
</script>

<template>
    <Head :title="famille.name" :description="
            'Vous souhaitez pratiquer un sport de ' +
            famille.name +
            ' en France ? ' +
            famille.activites_count +
            ' activités sur notre site prêts à vous accueillir.'
        " />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ famille.name }}
                <span class="text-xs italic text-gray-600">({{ famille.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ famille.disciplines_count }}
                </span>
                sports ou activités en lien avec la catégorie
                <span class="font-semibold text-gray-800">
                    {{ famille.name }}
                </span>
                en France. <br />
                Consultez la liste des activités disponibles, comparez services,
                tarifs et horaires en 2 clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    famille.name
                    }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8">
                <div class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <Link :href="route('disciplines.show', discipline.slug)" :active="
                            route().current('disciplines.show', discipline.slug)
                        " v-for="discipline in famille.disciplines" :key="discipline.id"
                        class="flex flex-col items-center justify-center rounded border border-gray-600 px-12 py-3 text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500">
                    {{ discipline.name }}
                    <!-- <div
                            v-if="discipline.activites_count > 0"
                            class="text-xs"
                        >
                            ({{ discipline.activites_count }}
                            <span v-if="discipline.activites_count > 1"
                                >activites</span
                            >
                            <span v-else>activité</span>)
                        </div>
                        <div v-else class="text-xs">
                            (Pas encore d'activité inscrite)
                        </div> -->
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
