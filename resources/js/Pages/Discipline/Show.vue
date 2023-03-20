<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { defineAsyncComponent } from "vue";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
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
            ' en France ? ' +
            discipline.structures_count +
            ' structures sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ discipline.name }} ({{ discipline.view_count }} vues)
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ discipline.structures_count }}
                </span>
                structures ou activités en lien avec la catégorie
                <span class="font-semibold text-gray-800">
                    {{ discipline.name }}
                </span>
                en France. <br />
                Consultez la liste des
                <span class="font-semibold text-gray-800">
                    {{ discipline.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    discipline.name
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>

        <template v-if="discipline.structures_count > 0">
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8"
                >
                    <div
                        class="mx-auto flex min-h-screen max-w-7xl flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                    >
                        <!-- <Link
                            :href="route('structure.show', structure.slug)"
                            :active="
                                route().current(
                                    'structure.show',
                                    structure.slug
                                )
                            "
                            v-for="structure in discipline.structures"
                            :key="structure.id"
                            class="flex flex-col items-center justify-center h-24 overflow-hidden text-lg text-center text-gray-700 transition duration-100 bg-white rounded shadow-lg hover:bg-gray-200 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            {{ structure.name }}
                            <div class="mb-1 text-xs">
                                {{ structure.city }} ({{ structure.zip_code }})
                            </div>
                        </Link> -->

                        <div class="md:w-1/2">
                            <Link
                                v-for="(
                                    structure, index
                                ) in discipline.structures"
                                :key="structure.id"
                                :index="index"
                                :href="route('structure.show', structure.slug)"
                                :active="
                                    route().current(
                                        'structure.show',
                                        structure.slug
                                    )
                                "
                                class="group relative block bg-black"
                            >
                                <img
                                    alt="Developer"
                                    src="https://images.unsplash.com/photo-1603871165848-0aa92c869fa1?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=772&q=80"
                                    class="absolute inset-0 h-full w-full object-cover opacity-75 transition-opacity group-hover:opacity-50"
                                />

                                <div class="relative p-4 sm:p-6 lg:p-8">
                                    <p
                                        class="text-sm font-medium uppercase tracking-widest text-pink-500"
                                    >
                                        {{ structure.category.name }}
                                    </p>

                                    <p
                                        class="text-xl font-bold text-white sm:text-2xl"
                                    >
                                        {{ structure.name }}
                                    </p>

                                    <div class="mt-32 sm:mt-48 lg:mt-64">
                                        <div
                                            class="translate-y-8 transform opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100"
                                        >
                                            <p
                                                class="text-sm text-white line-clamp-3"
                                            >
                                                {{ structure.description }}
                                            </p>
                                            <p class="text-sm text-white">
                                                {{ structure.city }} ({{
                                                    structure.zip_code
                                                }})
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                            <!-- <Link
                                :href="route('structure.show', structure.slug)"
                                :active="
                                    route().current(
                                        'structure.show',
                                        structure.slug
                                    )
                                "
                                class="block p-4 bg-white rounded-xl sm:p-6 lg:p-8"
                            >
                                <div class="mt-16">
                                    <h3
                                        class="text-lg font-bold text-gray-900 sm:text-xl"
                                    >
                                        {{ structure.name }}
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500">
                                        {{ structure.category.name }}
                                    </p>
                                    <p class="mt-2 text-xs text-gray-500">
                                        {{ structure.city }} ({{
                                            structure.zip_code
                                        }})
                                    </p>
                                </div>
                            </Link> -->

                            <div class="flex justify-end p-10">
                                <Pagination
                                    :links="discipline.structures.links"
                                />
                            </div>
                        </div>
                        <LeafletMapMultiple
                            class="md:w-1/2"
                            :structures="discipline.structures"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div
                    class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Dommage, il n'y a pas encore de club inscrit en
                        <span class="font-semibold text-gray-800">{{
                            discipline.name
                        }}</span>
                    </p>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
