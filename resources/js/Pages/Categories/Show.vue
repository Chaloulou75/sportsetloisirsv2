<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    category: Object,
    totalStructures: Number,
});
</script>

<template>
    <Head
        :title="category.name"
        :description="
            'Vous souhaitez pratiquer un sport de ' +
            category.name +
            ' en France ? ' +
            category.structures_count +
            ' structures sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ category.name }}
                <span class="text-xs italic text-gray-600"
                    >({{ category.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ category.disciplines_count }}
                </span>
                sports ou activités en lien avec la catégorie
                <span class="font-semibold text-gray-800">
                    {{ category.name }}
                </span>
                en France. <br />
                Consultez la liste des
                <span class="font-semibold text-gray-800">
                    {{ totalStructures }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport de
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
                        :href="route('disciplines.show', discipline.slug)"
                        :active="
                            route().current('disciplines.show', discipline.slug)
                        "
                        v-for="discipline in category.disciplines"
                        :key="discipline.id"
                        class="flex flex-col items-center justify-center rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ discipline.name }}
                        <div
                            v-if="discipline.structures_count > 0"
                            class="text-xs"
                        >
                            ({{ discipline.structures_count }}
                            <span v-if="discipline.structures_count > 1"
                                >structures</span
                            >
                            <span v-else>structure</span>)
                        </div>
                        <div v-else class="text-xs">
                            (Pas encore de structure inscrite)
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
