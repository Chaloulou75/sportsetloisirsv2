<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    famille: Object,
    // totalActivites: Number,
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

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ famille.name }}
                <span class="text-xs italic text-gray-600"
                    >({{ famille.view_count }} vues)
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
                Consultez la liste des
                <span class="font-semibold text-gray-800">
                    {{ totalActivites }}
                </span>
                activités disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    famille.name
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>

        <div class="py-12">
            <div class="min-h-screen px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('disciplines.show', discipline.slug)"
                        :active="
                            route().current('disciplines.show', discipline.slug)
                        "
                        v-for="discipline in famille.disciplines"
                        :key="discipline.id"
                        class="flex flex-col items-center justify-center px-4 py-3 text-lg text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ discipline.name }}
                        <div
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
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
