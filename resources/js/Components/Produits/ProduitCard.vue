<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, nextTick, watch, onMounted, computed } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { classMapping } from "@/Utils/classMapping.js";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { HeartIcon } from "@heroicons/vue/24/solid";
import { TransitionRoot } from "@headlessui/vue";

const emit = defineEmits(["card-hover", "card-out"]);

const props = defineProps({
    produit: Object,
    discipline: Object,
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

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    if (props.discipline && props.discipline.id) {
        const disciplineId = props.discipline.id;
        if (classMapping[disciplineId]) {
            return classMapping[disciplineId];
        } else {
            return defaultClass;
        }
    } else {
        return defaultClass;
    }
});

const cookies = useCookies(["favoriteProduits"]);
const favoriteProduits = ref(cookies.get("favoriteProduits") || []);
const isFavorite = ref(false);

const toggleFavorite = (produitId) => {
    if (!props.produit) {
        return;
    }
    const cookieValue = cookies.get("favoriteProduits");
    // Convert the cookie value to an array
    const favoriteProduitsArray = cookieValue
        ? String(cookieValue).split(",")
        : [];
    // Ensure that produitId is always an array
    if (!Array.isArray(produitId)) {
        produitId = [produitId];
    }
    // Toggle each produitId
    produitId.forEach((id) => {
        const index = favoriteProduitsArray.indexOf(String(id));
        if (index !== -1) {
            favoriteProduitsArray.splice(index, 1); // Remove the activity ID from favorites
        } else {
            favoriteProduitsArray.push(String(id)); // Add the activity ID to favorites
        }
    });

    const expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + 2);

    const updatedCookieValue = JSON.stringify(favoriteProduitsArray);
    cookies.set("favoriteProduits", updatedCookieValue, {
        expires: expirationDate,
    });
    favoriteProduits.value = favoriteProduitsArray;
    updateIsFavorite();
};

watch(
    () => props.produit,
    async (newProduit) => {
        if (newProduit) {
            await nextTick();
            updateIsFavorite();
        }
    }
);

const updateIsFavorite = async () => {
    await nextTick();
    if (!props.produit) {
        return;
    }
    const produitId = String(props.produit.id);
    const favoriteProduitIds = Object.values(favoriteProduits.value);
    isFavorite.value = favoriteProduitIds.includes(produitId);
};

onMounted(() => {
    updateIsFavorite();
});

const formatCurrency = (value) => {
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    if (!isNaN(numericValue)) {
        if (numericValue % 1 === 0) {
            return numericValue.toLocaleString() + " €";
        } else {
            return numericValue.toFixed(2) + " €";
        }
    }
    return value;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
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
            :href="link"
            :data="data"
            @mouseover="emit('card-hover', produit)"
            @mouseout="emit('card-out')"
            class="block rounded-lg shadow-sm shadow-indigo-200 transition duration-300 ease-in-out hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div
                class="relative h-56 w-full rounded-md bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light"
                :class="headerClass"
            >
                <button
                    class="absolute right-2 top-2 z-30 bg-transparent"
                    type="button"
                    @click.prevent="() => toggleFavorite(produit.id)"
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
            </div>

            <div class="mt-2 flex flex-col">
                <div
                    v-if="produit.activite"
                    class="inline-flex flex-col items-center justify-center px-2 py-1.5 text-center text-lg font-semibold tracking-wide text-gray-600"
                >
                    {{ produit.activite.titre }}
                    <span class="mt-0.5 text-sm">{{
                        produit.structure.name
                    }}</span>
                </div>

                <div class="w-full px-4 py-2 text-slate-700">
                    <div
                        v-if="produit.adresse"
                        class="flex items-center py-1.5 text-base"
                    >
                        <dt class="sr-only">Ville</dt>
                        <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                        <p class="font-semibold">
                            {{ produit.adresse.address }},
                            {{ produit.adresse.city }} ({{
                                produit.adresse.zip_code
                            }})
                        </p>
                    </div>
                    <div v-if="produit.criteres.length > 0">
                        <p class="mt-2 text-base font-semibold">Critères:</p>
                        <ul class="list-inside list-disc text-base">
                            <template
                                v-for="critere in produit.criteres"
                                :key="critere.id"
                            >
                                <li v-if="critere.valeur.length > 0">
                                    {{ critere.critere.nom }}:

                                    <span class="font-semibold"
                                        >{{ critere.valeur }}
                                        <span
                                            v-if="
                                                critere.critere_valeur &&
                                                critere.critere_valeur
                                                    .sous_criteres &&
                                                critere.critere_valeur
                                                    .sous_criteres.length > 0
                                            "
                                            class="text-xs font-medium text-gray-600"
                                        >
                                            <span
                                                v-for="sousCriteres in critere
                                                    .critere_valeur
                                                    .sous_criteres"
                                                :key="sousCriteres.id"
                                            >
                                                <span
                                                    v-for="sousCritValeur in sousCriteres.prod_sous_crit_valeurs"
                                                    :key="sousCritValeur.id"
                                                    class="text-xs font-semibold text-gray-600"
                                                >
                                                    <span
                                                        v-if="
                                                            sousCritValeur.produit_id ===
                                                            produit.id
                                                        "
                                                        >({{
                                                            sousCritValeur.valeur
                                                        }})</span
                                                    ></span
                                                >
                                            </span>
                                        </span>
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div v-if="produit.tarifs.length > 0">
                        <p class="mt-2 text-base font-semibold">Tarifs:</p>
                        <ul class="list-inside list-disc text-base">
                            <li v-for="tarif in produit.tarifs" :key="tarif.id">
                                {{ tarif.titre }}:
                                <span class="font-semibold">
                                    {{ tarif.amount }} € /
                                    {{ tarif.tarif_type.type }}</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </Link>
    </TransitionRoot>
</template>
