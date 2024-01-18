<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, nextTick, watch, onMounted } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { HeartIcon } from "@heroicons/vue/24/solid";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    activite: Object,
    link: {
        type: String,
        default: null,
    },
});

const isShowing = ref(true);
const cookies = useCookies(["favoriteActivities"]);
const favoriteActivities = ref(cookies.get("favoriteActivities") || []);
const isFavorite = ref(false);

const toggleFavorite = (activityId) => {
    if (!props.activite) {
        return;
    }
    const cookieValue = cookies.get("favoriteActivities");
    // Convert the cookie value to an array
    const favoriteActivitiesArray = cookieValue
        ? String(cookieValue).split(",")
        : [];
    // Ensure that activityId is always an array
    if (!Array.isArray(activityId)) {
        activityId = [activityId];
    }
    // Toggle each activityId
    activityId.forEach((id) => {
        const index = favoriteActivitiesArray.indexOf(String(id));
        if (index !== -1) {
            favoriteActivitiesArray.splice(index, 1); // Remove the activity ID from favorites
        } else {
            favoriteActivitiesArray.push(String(id)); // Add the activity ID to favorites
        }
    });

    const expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + 2);

    const updatedCookieValue = JSON.stringify(favoriteActivitiesArray);
    cookies.set("favoriteActivities", updatedCookieValue, {
        expires: expirationDate,
    });
    favoriteActivities.value = favoriteActivitiesArray;
    updateIsFavorite();
};

watch(
    () => props.activite,
    async (newActivite) => {
        if (newActivite) {
            await nextTick();
            updateIsFavorite();
        }
    }
);

const updateIsFavorite = async () => {
    await nextTick();
    if (!props.activite) {
        return;
    }
    const activityId = String(props.activite.id);
    const favoriteActivityIds = Object.values(favoriteActivities.value);
    isFavorite.value = favoriteActivityIds.includes(activityId);
};

onMounted(() => {
    updateIsFavorite();
});

const formatCurrency = (value) => {
    // Remove the non-numeric characters from the currency value
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    // Check if the numeric value is a valid number
    if (!isNaN(numericValue)) {
        // Check if the numeric value has decimal places
        if (numericValue % 1 === 0) {
            // No decimal places, return as integer
            return numericValue.toLocaleString() + " €";
        } else {
            // Decimal places present, format with two decimal places
            return numericValue.toFixed(2) + " €";
        }
    }
    // Return the original value if conversion failed
    return value;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>

<template v-if="link">
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
            :href="link"
            class="block rounded-lg shadow-sm shadow-indigo-200 transition duration-300 ease-in-out hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div class="relative">
                <!-- Button (positioned on top right) -->
                <button
                    class="absolute right-2 top-2 z-30 bg-transparent"
                    type="button"
                    @click.prevent="() => toggleFavorite(activite.id)"
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
                    class="h-56 w-full rounded-md bg-opacity-75 object-cover"
                />
            </div>

            <div class="mt-2">
                <dl class="flex flex-col">
                    <p
                        class="px-2 py-1.5 text-center text-sm font-semibold tracking-wide text-gray-600"
                    >
                        {{ activite.titre }}
                    </p>

                    <div class="w-full divide-y divide-slate-200">
                        <dt class="sr-only">Produits</dt>
                        <div
                            class="px-2 py-3 odd:bg-white even:bg-slate-50"
                            v-for="produit in activite.produits"
                            :key="produit.id"
                        >
                            <p class="text-xs">Produit n° {{ produit.id }}:</p>
                            <div class="flex items-center py-1.5 text-xs">
                                <dt class="sr-only">Ville</dt>
                                <MapPinIcon
                                    class="mr-1 h-4 w-4 text-indigo-700"
                                />
                                <p class="font-semibold">
                                    {{ produit.adresse.city }} ({{
                                        produit.adresse.zip_code
                                    }})
                                </p>
                            </div>
                            <p
                                class="text-sm"
                                v-for="critere in produit.criteres"
                                :key="critere.id"
                            >
                                {{ critere.critere.nom }}:
                                <span class="font-semibold"
                                    >{{ critere.valeur
                                    }}<span
                                        v-if="critere.sous_criteres.length > 0"
                                        class="text-xs font-medium text-gray-600"
                                    >
                                        <span
                                            v-for="sousCriteres in critere.sous_criteres"
                                            :key="sousCriteres.id"
                                            class="text-xs font-semibold text-gray-600"
                                        >
                                            ({{ sousCriteres.valeur }})
                                        </span>
                                    </span></span
                                >
                            </p>
                        </div>
                    </div>
                </dl>
            </div>
        </Link>
    </TransitionRoot>
</template>
