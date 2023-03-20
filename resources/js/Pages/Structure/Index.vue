<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce, pickBy, throttle, mapValues } from "lodash";
import { defineAsyncComponent } from "vue";
import TextInput from "@/Components/TextInput.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";

let props = defineProps({
    structures: Object,
    filters: Object,
    structuresCount: Number,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

let search = ref(props.filters.search);

watch(
    search,
    debounce(function (value) {
        router.get(
            "/structure",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);
</script>

<template>
    <Head
        title="Structures"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            structures.total +
            ' structures différentes en France. Choisissez parmi ' +
            structuresCount +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Structures, clubs de sport et cours collectifs
            </h2>
            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Plus de
                <span class="font-semibold text-gray-800">{{
                    structures.total
                }}</span>
                structures référencées et prêtes à vous accueillir et vous
                accompagner dans la pratique de votre discipline favorite !
            </p>
        </template>

        <div class="py-12">
            <!-- search box -->
            <div
                class="mx-auto mt-4 mb-8 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row"
            >
                <label
                    for="search"
                    value="Rechercher une structure"
                    class="mb-1 pr-2 text-sm font-medium text-gray-800"
                    >Rechercher une structure:</label
                >

                <TextInput
                    id="search"
                    type="text"
                    class="focus:ring-midnight mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2"
                    v-model="search"
                    placeholder="structures, clubs..."
                />

                <!-- <button type="button" @click="reset">
                    <svg
                        class="w-6 h-6 my-2 text-gray-300 hover:text-gray-200 lg:my-0 lg:h-8 lg:w-8"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button> -->
            </div>
            <div
                class="mx-auto flex min-h-screen max-w-7xl flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div class="md:w-1/2">
                    <div
                        class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-2"
                    >
                        <Link
                            v-for="(structure, index) in structures.data"
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
                    </div>
                    <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div>
                </div>
                <LeafletMapMultiple
                    class="md:w-1/2"
                    :structures="structures.data"
                />
            </div>
        </div>
    </AppLayout>
</template>
