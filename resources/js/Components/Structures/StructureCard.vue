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
            class="block h-full transition duration-300 ease-in-out rounded-lg shadow-sm shadow-sky-700 hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div class="relative flex items-center justify-center">
                <!-- Button (positioned on top right) -->
                <button
                    class="absolute right-2 top-2 z-30 rounded-full bg-white p-1.5"
                    type="button"
                    @click.prevent="() => toggleFavorite(structure.id)"
                >
                    <HeartIcon
                        class="w-6 h-6"
                        :class="
                            isFavorite
                                ? 'text-red-500'
                                : 'text-red-300 hover:text-red-500'
                        "
                    />
                </button>

                <!-- Image -->
                <img
                    v-if="structure.logo"
                    alt="Home"
                    :src="structure.image_url"
                    class="object-contain object-center rounded-full h-14 w-14 shrink-0 md:h-20 md:w-20"
                />
                <img
                    v-else
                    alt="Home"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="object-cover w-full h-56 rounded-md"
                />
            </div>

            <div class="flex flex-col px-3 mt-2">
                <p
                    class="text-sm font-medium text-center text-pink-500 uppercase"
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
                        class="text-sm font-semibold list-disc list-inside"
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
                    <MapPinIcon class="w-4 h-4 mr-1 text-indigo-700" />
                    <div
                        v-if="structure.adresses.length > 0"
                        class="text-sm font-medium"
                    >
                        {{ formatCityName(structure.adresses[0].city) }}
                        <span class="text-xs"
                            >({{ structure.adresses[0].zip_code }})</span
                        >
                    </div>

                    <div v-else class="text-sm font-medium">
                        <span>{{ formatCityName(structure.city) }}</span>
                        <span class="text-xs">({{ structure.zip_code }})</span>
                    </div>
                </div>
            </div>
        </Link>

        <Link
            v-else
            :href="
                route('structures.show', {
                    structure: structure.slug,
                })
            "
            class="block h-full transition duration-300 ease-in-out rounded-lg shadow-sm shadow-sky-700 hover:scale-105 hover:shadow-2xl md:px-0"
        >
            <div class="relative flex items-center justify-center">
                <!-- Button (positioned on top right) -->
                <button
                    class="absolute right-2 top-2 z-30 rounded-full bg-white p-1.5"
                    type="button"
                    @click.prevent="() => toggleFavorite(structure.id)"
                >
                    <HeartIcon
                        class="w-6 h-6"
                        :class="
                            isFavorite
                                ? 'text-red-500'
                                : 'text-red-300 hover:text-red-500'
                        "
                    />
                </button>

                <!-- Image -->
                <img
                    v-if="structure.logo"
                    alt="Home"
                    :src="structure.image_url"
                    class="object-contain object-center rounded-full h-14 w-14 shrink-0 md:h-20 md:w-20"
                />
                <img
                    v-else
                    alt="Home"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="object-cover w-full h-56 rounded-md"
                />
            </div>

            <div class="flex flex-col px-3 mt-2">
                <p
                    class="text-sm font-medium text-center text-pink-500 uppercase"
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
                        class="text-sm font-semibold list-disc list-inside"
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
                    <MapPinIcon class="w-4 h-4 mr-1 text-indigo-700" />
                    <div
                        v-if="structure.adresses.length > 0"
                        class="text-sm font-medium"
                    >
                        {{ formatCityName(structure.adresses[0].city) }}
                        <span class="text-xs"
                            >({{ structure.adresses[0].zip_code }})</span
                        >
                    </div>

                    <div v-else class="text-sm font-medium">
                        <span>{{ formatCityName(structure.city) }}</span>
                        <span class="text-xs">({{ structure.zip_code }})</span>
                    </div>
                </div>
            </div>
        </Link>
    </TransitionRoot>
</template>
