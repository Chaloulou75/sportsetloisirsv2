<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import TextInput from "@/Components/TextInput.vue";
// import { defineAsyncComponent } from "vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";

let props = defineProps({
    city: Object,
    filters: Object,
});

let discipline = ref("");
function resetSearch() {
    discipline.value = "";
}

watch(
    discipline,
    debounce(function (value) {
        router.get(
            `/villes/${city.ville_formatee}`,
            { discipline: value },
            { preserveState: true, replace: true }
        );
    }, 500)
);

// const Pagination = defineAsyncComponent(() =>
//     import("@/Components/Pagination.vue")
// );
</script>

<template>
    <Head
        :title="city.ville"
        :description="
            'Envie de faire du sport à ' +
            city.ville +
            '? Choisissez parmi plus de ' +
            city.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            city.ville
        "
    />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Les structures disponibles à {{ city.ville }}
                <span class="text-xs italic text-gray-600"
                    >({{ city.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                Trouvez un club de sport à
                <span class="font-semibold text-gray-800"
                    >{{ city.ville }}
                </span>
                en France. <br />
                Consultez la liste des
                <span
                    v-if="city.structures_count > 1"
                    class="font-semibold text-gray-800"
                    >{{ city.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport à {{ city.ville }} n'a jamais été
                aussi simple!
            </p>
        </template>

        <template v-if="city.structures_count > 0">
            <div class="py-12">
                <!-- discipline box -->
                <div
                    class="flex flex-col items-center justify-center w-full max-w-3xl px-2 mx-auto mt-4 mb-8 md:flex-row"
                >
                    <label
                        for="discipline"
                        value="Rechercher dans votre discipline:"
                        class="pr-2 mb-1 text-sm font-medium text-gray-800"
                        >Rechercher une discipline:</label
                    >

                    <TextInput
                        id="discipline"
                        type="text"
                        class="flex-1 block w-full px-2 mt-1 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                        v-model="discipline"
                        placeholder="discipline..."
                    />

                    <button type="button" @click="resetSearch">
                        <svg
                            class="w-6 h-6 my-3 ml-2 text-gray-400 hover:text-gray-700 lg:my-0 lg:h-8 lg:w-8"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    class="min-h-screen px-2 mx-auto max-w-7xl sm:px-6 lg:px-8"
                >
                    <div
                        class="flex flex-col min-h-screen px-2 mx-auto max-w-7xl sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                    >
                        <div class="md:w-1/2">
                            <div
                                class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-2"
                            >
                                <Link
                                    v-for="(
                                        structure, index
                                    ) in city.structures"
                                    :key="structure.id"
                                    :index="index"
                                    :href="
                                        route('structures.show', structure.slug)
                                    "
                                    :active="
                                        route().current(
                                            'structures.show',
                                            structure.slug
                                        )
                                    "
                                    class="relative block bg-black group"
                                >
                                    <img
                                        alt="a guy"
                                        src="https://images.unsplash.com/photo-1603871165848-0aa92c869fa1?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=772&q=80"
                                        class="absolute inset-0 object-cover w-full h-full transition-opacity opacity-75 group-hover:opacity-50"
                                    />

                                    <div class="relative p-4 sm:p-6 lg:p-8">
                                        <p
                                            class="text-sm font-medium tracking-widest text-pink-500 uppercase"
                                        >
                                            {{ structure.structuretype.name }}
                                        </p>

                                        <p
                                            class="text-xl font-bold text-white sm:text-2xl"
                                        >
                                            {{ structure.name }}
                                        </p>

                                        <div class="mt-32 sm:mt-48 lg:mt-64">
                                            <div
                                                class="transition-all transform translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100"
                                            >
                                                <p
                                                    class="mb-2 text-sm text-white line-clamp-3"
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
                            </div>
                            <!-- <div class="flex justify-end p-10">
                                <Pagination :links="city.structures.links" />
                            </div> -->
                        </div>
                        <LeafletMapMultiple
                            class="md:w-1/2"
                            :structures="city.structures"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="py-12">
                <div
                    class="min-h-screen px-2 mx-auto max-w-7xl sm:px-6 lg:px-8"
                >
                    <p class="font-medium text-gray-700">
                        Dommage, il n'y a pas encore de structures inscrites à
                        <span class="font-semibold text-gray-800">{{
                            city.ville
                        }}</span>
                    </p>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
