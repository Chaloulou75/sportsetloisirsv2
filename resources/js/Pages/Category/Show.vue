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
            category.structures_count +
            ' structures sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ category.name }} ({{ category.view_count }} vues)
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
                    {{ category.structures_count }}
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
            <div class="min-h-screen px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-3"
                >
                    <Link
                        :href="route('discipline.show', discipline.slug)"
                        :active="
                            route().current('discipline.show', discipline.slug)
                        "
                        v-for="discipline in category.disciplines"
                        :key="discipline.id"
                        class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        {{ discipline.name }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
