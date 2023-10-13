<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, nextTick, watch, onMounted } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { TransitionRoot } from "@headlessui/vue";
import { HeartIcon } from "@heroicons/vue/24/solid";

const emit = defineEmits(["card-hover", "card-out"]);

const props = defineProps({
    structure: Object,
    link: {
        type: String,
        default: null,
    },
    data: {
        type: Object,
        default: null,
    },
});

const isShowing = ref(true);

const cookies = useCookies(["favoriteStructures"]);
const favoriteStructures = ref(cookies.get("favoriteStructures") || []);
const isFavorite = ref(false);

const toggleFavorite = (structureId) => {
    if (!props.structure) {
        return;
    }
    const cookieValue = cookies.get("favoriteStructures");
    // Convert the cookie value to an array
    const favoriteStructuresArray = cookieValue
        ? String(cookieValue).split(",")
        : [];
    // Ensure that structureId is always an array
    if (!Array.isArray(structureId)) {
        structureId = [structureId];
    }
    // Toggle each structureId
    structureId.forEach((id) => {
        const index = favoriteStructuresArray.indexOf(String(id));
        if (index !== -1) {
            favoriteStructuresArray.splice(index, 1); // Remove the activity ID from favorites
        } else {
            favoriteStructuresArray.push(String(id)); // Add the activity ID to favorites
        }
    });

    const expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + 2);

    const updatedCookieValue = JSON.stringify(favoriteStructuresArray);
    cookies.set("favoriteStructures", updatedCookieValue, {
        expires: expirationDate,
    });
    favoriteStructures.value = favoriteStructuresArray;
    updateIsFavorite();
};

watch(
    () => props.structure,
    async (newStructure) => {
        if (newStructure) {
            await nextTick();
            updateIsFavorite();
        }
    }
);

const updateIsFavorite = async () => {
    await nextTick();
    if (!props.structure) {
        return;
    }
    const structureId = String(props.structure.id);
    const favoriteStructureIds = Object.values(favoriteStructures.value);
    isFavorite.value = favoriteStructureIds.includes(structureId);
};

onMounted(() => {
    updateIsFavorite();
});

const getUniqueActivitesTitre = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.titre)) {
            uniqueNames.add(activite.titre);
            return true;
        }
        return false;
    });
};

const formatCityName = (ville) => {
    if (ville) {
        return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
    } else {
        return "";
    }
};
</script>

<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <Link
            v-if="link"
            :href="link"
            :data="data"
            @mouseover="emit('card-hover', structure)"
            @mouseout="emit('card-out')"
            class="block rounded-lg shadow-sm shadow-sky-700 transition duration-300 ease-in-out hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div class="relative">
                <!-- Button (positioned on top right) -->
                <button
                    class="absolute right-2 top-2 z-30 bg-transparent"
                    type="button"
                    @click.prevent="() => toggleFavorite(structure.id)"
                >
                    <HeartIcon
                        class="h-6 w-6"
                        :class="
                            isFavorite
                                ? 'text-red-500'
                                : 'text-white hover:text-red-500'
                        "
                    />
                </button>

                <!-- Image -->
                <img
                    alt="Home"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="h-56 w-full rounded-md object-cover"
                />
            </div>

            <div class="mt-2 flex flex-col px-3">
                <p
                    class="text-center text-sm font-medium uppercase text-pink-500"
                >
                    {{ structure.structuretype.name }}
                </p>
                <p
                    class="py-1.5 text-center text-base font-semibold tracking-widest text-gray-600"
                >
                    {{ structure.name }}
                </p>
                <ul>
                    <li
                        class="list-inside list-disc text-sm font-semibold"
                        v-for="(activite, index) in getUniqueActivitesTitre(
                            structure.activites
                        )"
                        :key="activite.id"
                    >
                        {{ activite.titre }}
                    </li>
                </ul>

                <div class="flex items-center py-1.5">
                    <dt class="sr-only">Ville</dt>
                    <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                    <div
                        v-if="structure.adresses.length > 0"
                        class="text-sm font-medium"
                    >
                        {{ structure.adresses[0].address }},
                        {{ formatCityName(structure.adresses[0].city) }}
                        <span class="text-xs"
                            >({{ structure.adresses[0].zip_code }})</span
                        >
                    </div>

                    <div v-else class="text-sm font-medium">
                        {{ structure.address }},
                        <span>{{ formatCityName(structure.city) }}</span>
                        <span class="text-xs">({{ structure.zip_code }})</span>
                    </div>
                </div>

                <!-- <div
                class="mt-auto flex items-center justify-around gap-1 py-1.5 text-xs"
            >
                <div
                    v-if="structure.disciplines_count"
                    class="inline-flex shrink-0 items-center"
                >
                    <svg
                        class="mr-1 h-4 w-4 text-indigo-700"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
                        />
                    </svg>
                    <p class="font-medium text-gray-600">
                        {{ structure.disciplines_count }}
                        <span v-if="structure.disciplines_count > 1"
                            >Disciplines</span
                        >
                        <span v-else>Discipline</span>
                    </p>
                </div>

                <div
                    v-if="structure.activites_count"
                    class="inline-flex shrink-0 items-center"
                >
                    <svg
                        class="mr-1 h-4 w-4 text-indigo-700"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                        />
                    </svg>

                    <p class="font-medium text-gray-600">
                        {{ structure.activites_count }}
                        <span v-if="structure.activites_count > 1"
                            >Activités</span
                        >
                        <span v-else>Activité</span>
                    </p>
                </div>

                <div
                    v-if="structure.produits_count"
                    class="inline-flex shrink-0 items-center"
                >
                    <BookmarkIcon class="mr-1 h-4 w-4 text-indigo-700" />
                    <p class="font-medium text-gray-600">
                        {{ structure.produits_count }}
                        <span v-if="structure.produits_count > 1"
                            >Produits</span
                        >
                        <span v-else>Produit</span>
                    </p>
                </div>
            </div> -->
            </div>
        </Link>

        <Link
            v-else
            :href="
                route('structures.show', {
                    structure: structure.slug,
                })
            "
            class="block rounded-lg shadow-sm shadow-sky-700 transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl md:px-0"
        >
            <div class="relative">
                <!-- Button (positioned on top right) -->
                <button
                    class="absolute right-2 top-2 bg-transparent"
                    type="button"
                    @click="() => toggleFavorite(structure.id)"
                >
                    <HeartIcon
                        class="h-6 w-6"
                        :class="
                            isFavorite
                                ? 'text-red-500'
                                : 'text-white hover:text-red-500'
                        "
                    />
                </button>

                <!-- Image -->
                <img
                    alt="Home"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="h-56 w-full rounded-md object-cover"
                />
            </div>

            <div>
                <dl class="mt-2 flex flex-col px-3">
                    <p
                        class="text-center text-sm font-medium uppercase tracking-widest text-pink-500"
                    >
                        {{ structure.structuretype.name }}
                    </p>
                    <p
                        class="py-1.5 text-sm font-medium tracking-widest text-gray-600"
                    >
                        {{ structure.name }}
                    </p>
                    <div class="py-1.5">
                        <span
                            class="text-sm font-semibold"
                            v-for="(activite, index) in getUniqueActivitesTitre(
                                structure.activites
                            )"
                            :key="activite.id"
                        >
                            {{ activite.titre }}
                            <span
                                v-if="
                                    index <
                                    getUniqueActivitesTitre(structure.activites)
                                        .length -
                                        1
                                "
                            >
                                -
                            </span>
                        </span>
                    </div>

                    <div class="flex items-center py-1.5">
                        <dt class="sr-only">Ville</dt>
                        <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                        <div class="text-sm font-medium">
                            {{ structure.address }},
                            {{ formatCityName(structure.city) }}
                            <span class="text-xs"
                                >({{ structure.zip_code }})</span
                            >
                        </div>
                    </div>

                    <!-- <div
                    class="mt-auto flex items-center justify-around gap-1 py-1.5 text-xs"
                >
                    <div
                        v-if="structure.disciplines_count"
                        class="inline-flex shrink-0 items-center"
                    >
                        <svg
                            class="mr-1 h-4 w-4 text-indigo-700"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
                            />
                        </svg>
                        <p class="font-medium text-gray-600">
                            {{ structure.disciplines_count }}
                            <span v-if="structure.disciplines_count > 1"
                                >Disciplines</span
                            >
                            <span v-else>Discipline</span>
                        </p>
                    </div>

                    <div
                        v-if="structure.activites_count"
                        class="inline-flex shrink-0 items-center"
                    >
                        <svg
                            class="mr-1 h-4 w-4 text-indigo-700"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            />
                        </svg>

                        <p class="font-medium text-gray-600">
                            {{ structure.activites_count }}
                            <span v-if="structure.activites_count > 1"
                                >Activités</span
                            >
                            <span v-else>Activité</span>
                        </p>
                    </div>

                    <div
                        v-if="structure.produits_count"
                        class="inline-flex shrink-0 items-center"
                    >
                        <BookmarkIcon class="mr-1 h-4 w-4 text-indigo-700" />
                        <p class="font-medium text-gray-600">
                            {{ structure.produits_count }}
                            <span v-if="structure.produits_count > 1"
                                >Produits</span
                            >
                            <span v-else>Produit</span>
                        </p>
                    </div>
                </div> -->
                </dl>
            </div>
        </Link>
    </TransitionRoot>
</template>
