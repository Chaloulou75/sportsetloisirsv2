<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
defineProps({
    categories: Object,
    // sportsCount: Number,
    // categoriesCount: Number,
    // clubsCount: Number,
    // villesCount: Number,
});
</script>

<template>
    <Head
        title="Catégories d'activités"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            sportsCount +
            ' disciplines différentes en France. Choisissez parmi ' +
            clubsCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Les catégories d'activités
            </h2>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Trouvez un club de sport ou un cours collectif parmi plus de
                <!-- <span class="font-semibold text-gray-800">
                    {{ clubsCount }}
                </span> -->
                clubs, dans plus de
                <!-- <span class="font-semibold text-gray-800">
                    {{ sportsCount }}
                </span> -->
                disciplines différentes.
                <!-- , dans
                <span class="font-semibold text-gray-800">
                    {{ villesCount }}
                </span>
                villes de France.  -->
                <br />
                Il y en a pour tous les gouts : sports collectifs, sports de
                montagne, sport de combats, danse, musique, ...
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('category.show', category.slug)"
                        :active="
                            route().current('category.show', category.slug)
                        "
                        v-for="category in categories"
                        :key="category.id"
                        class="flex h-24 flex-col items-center justify-center overflow-hidden rounded bg-white text-center text-lg text-gray-700 shadow-lg transition duration-100 hover:bg-green-200 hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
