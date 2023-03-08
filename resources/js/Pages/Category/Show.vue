<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
defineProps({
    category: Object,
});
</script>

<template>
    <Head
        :title="category.name"
        :description="
            'Vous souhaitez pratiquer un sport de ' +
            category.name +
            ' en France ? ' +
            category.clubs_count +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ category.name }}
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ category.activite_count }}
                </span>
                sports ou activités en lien avec la catégorie
                <span class="font-semibold text-gray-800">
                    {{ category.name }}
                </span>
                en France. <br />
                Consultez la liste des
                <span class="font-semibold text-gray-800">
                    {{ category.clubs_count }}
                </span>
                clubs disponibles, comparez services, tarifs et horaires en 2
                clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    category.name
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('activite.show', activite.slug)"
                        :active="
                            route().current('activite.show', activite.slug)
                        "
                        v-for="activite in category.activites"
                        :key="activite.id"
                        class="flex h-24 flex-col items-center justify-center overflow-hidden rounded bg-white text-center text-lg text-gray-700 shadow-lg transition duration-100 hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ activite.name }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
