<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
defineProps({
    categories: Object,
    disciplinesCount: Number,
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
            disciplinesCount +
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
                <span class="font-semibold text-gray-800">
                    {{ disciplinesCount }}
                </span>
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
            <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('category.show', category.slug)"
                        :active="
                            route().current('category.show', category.slug)
                        "
                        v-for="category in categories"
                        :key="category.id"
                        class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
