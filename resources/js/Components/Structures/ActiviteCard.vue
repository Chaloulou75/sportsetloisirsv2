<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { HeartIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    activite: Object,
    link: {
        type: String,
        default: null,
    },
});

const cookies = useCookies(["favoriteActivities"]);

const favoriteActivities = ref([]);

const toggleFavorite = (activityId) => {
    if (!props.activite) {
        console.error("activite is undefined:", props.activite);
        return;
    }
    const index = favoriteActivities.value.indexOf(activityId);
    if (index === -1) {
        favoriteActivities.value.push(activityId);
    } else {
        favoriteActivities.value.splice(index, 1);
    }
    cookies.set(
        "favoriteActivities",
        JSON.stringify(favoriteActivities.value),
        {
            remove: "2d",
        }
    );
};

const isFavorite = () => {
    if (!props.activite.value) {
        return false;
    }
    return favoriteActivities.value.includes(props.activite.value.id);
};

watch(
    () => props.activite.value,
    (newActivite) => {
        if (newActivite) {
            updateIsFavorite(newActivite);
        }
    }
);

// Function to update isFavorite status
const updateIsFavorite = (newActivite) => {
    if (!newActivite) {
        return;
    }
    const activityId = newActivite.id;

    const cookieValue = cookies.get("favoriteActivities");
    if (cookieValue) {
        favoriteActivities.value = JSON.parse(cookieValue);
    }

    isFavorite.value = favoriteActivities.value.includes(activityId);
};

onMounted(() => {
    updateIsFavorite(props.activite.value);
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

<template>
    <template v-if="link">
        <div
            class="relative block rounded-lg shadow-sm shadow-indigo-200 hover:shadow-xl md:px-0"
        >
            <button
                class=""
                type="button"
                @click="() => toggleFavorite(activite.id)"
            >
                <HeartIcon
                    class="absolute right-2 top-2 z-20 h-6 w-6"
                    :class="
                        isFavorite
                            ? 'text-red-500 hover:text-white'
                            : 'text-white hover:text-red-500'
                    "
                />
            </button>

            <img
                alt="Home"
                src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                class="h-56 w-full rounded-md bg-opacity-75 object-cover"
            />

            <Link :href="link" class="mt-2">
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
                                <span class="font-semibold">{{
                                    critere.valeur
                                }}</span>
                            </p>
                            <p
                                v-if="produit.tarifs.length > 0"
                                class="mt-2 text-sm"
                            >
                                Tarifs:
                            </p>
                            <p
                                class="text-sm"
                                v-for="tarif in produit.tarifs"
                                :key="tarif.id"
                            >
                                <span class=""> {{ tarif.titre }}: </span>
                                <span class="font-semibold">
                                    {{ tarif.amount }} € /
                                    {{ tarif.tarif_type.type }}</span
                                >
                            </p>
                        </div>
                    </div>
                </dl>
            </Link>
        </div>
    </template>
</template>
