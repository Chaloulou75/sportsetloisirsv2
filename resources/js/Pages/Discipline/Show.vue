<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    discipline: Object,
});
</script>

<template>
    <Head
        :title="discipline.name"
        :description="
            'Vous souhaitez pratiquer un sport de ' +
            discipline.name +
            ' en France ? '
            //  +
            // category.clubs_count +
            // ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ discipline.name }}({{ discipline.view_count }} vues)
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ discipline.structures_count }}
                </span>
                sports ou activités en lien avec la catégorie
                <span class="font-semibold text-gray-800">
                    {{ discipline.name }}
                </span>
                en France. <br />
                Consultez la liste des
                <!-- <span class="font-semibold text-gray-800">
                    {{ category.clubs_count }}
                </span> -->
                clubs disponibles, comparez services, tarifs et horaires en 2
                clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    discipline.name
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <!-- <Link
                        :href="route('structure.show', structure.slug)"
                        :active="
                            route().current('structure.show', structure.slug)
                        "
                        v-for="structure in discipline.structures"
                        :key="structure.id"
                        class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ structure.name }}
                    </Link> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
