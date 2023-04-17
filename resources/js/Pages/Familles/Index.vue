<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
defineProps({
    familles: Object,
    disciplinesCount: Number,
    famillesCount: Number,
    structuresCount: Number,
    // villesCount: Number,
});
</script>

<template>
    <Head
        title="Familles d'activités"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            disciplinesCount +
            ' disciplines différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Les familles d'activités
            </h2>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Trouvez une discipline ou un cours collectif parmi plus de
                <span class="font-semibold text-gray-800">
                    {{ structuresCount }}
                </span>
                structures, dans plus de
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
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('familles.show', famille.slug)"
                        :active="route().current('familles.show', famille.slug)"
                        v-for="famille in familles"
                        :key="famille.id"
                        class="flex items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ famille.name }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
