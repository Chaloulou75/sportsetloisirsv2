<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, nextTick, watch, onMounted, computed } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { classMapping } from "@/Utils/classMapping.js";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { HeartIcon } from "@heroicons/vue/24/solid";
import { TransitionRoot } from "@headlessui/vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import localeData from "dayjs/plugin/localeData";
dayjs.locale("fr");
dayjs.extend(localeData);

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

onMounted(() => {
    updateIsFavorite();
});
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
            class="flex h-full flex-col rounded-lg shadow-sm shadow-indigo-200 ring ring-gray-200 transition duration-300 ease-in-out hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div
                class="relative h-56 w-full rounded-md bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light"
                :class="headerClass"
            >
                <button
                    class="absolute right-2 top-2 z-30 rounded-full bg-white p-1.5"
                    type="button"
                    @click.prevent="() => toggleFavorite(produit.id)"
                >
                    <HeartIcon
                        class="h-6 w-6"
                        :class="
                            isFavorite
                                ? 'text-red-500'
                                : 'text-red-300 hover:text-red-500'
                        "
                    />
                </button>
            </div>

            <div class="mt-2 flex flex-1 flex-col">
                <div
                    v-if="produit.activite"
                    class="inline-flex flex-col items-center justify-center px-2 py-1.5 text-center text-lg font-semibold tracking-wide text-gray-600"
                >
                    {{ produit.activite.titre }}
                    <span class="mt-0.5 text-sm">{{
                        produit.structure.name
                    }}</span>
                </div>

                <div
                    class="flex flex-1 flex-col justify-between justify-items-end text-slate-700"
                >
                    <div
                        v-if="produit.adresse"
                        class="flex items-center px-4 py-2 text-base"
                    >
                        <dt class="sr-only">Ville</dt>
                        <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                        <p class="font-semibold">
                            {{ produit.adresse.city_name }} ({{
                                produit.adresse.zip_code
                            }})
                        </p>
                    </div>

                    <div
                        class="my-4 px-4"
                        v-if="
                            produit.cat_tarifs && produit.cat_tarifs.length > 0
                        "
                    >
                        <p class="text-right text-lg font-bold text-green-700">
                            <span class="text-sm font-medium text-gray-600"
                                >à partir de</span
                            >
                            {{ formatCurrency(produit.minimum_amount) }}
                        </p>
                    </div>

                    <template v-if="produit.criteres.length > 0">
                        <div
                            class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 text-xs text-gray-900"
                        >
                            <template
                                v-for="critere in produit.criteres"
                                :key="critere.id"
                            >
                                <div
                                    v-if="
                                        critere.valeur &&
                                        !!critere.critere.visible_block === true
                                    "
                                    class="flex w-full flex-col items-center justify-center px-1 py-3 font-medium"
                                    :class="
                                        [
                                            'date',
                                            'dates',
                                            'time',
                                            'mois',
                                            'times',
                                        ].includes(
                                            critere.critere.type_champ_form
                                        )
                                            ? 'bg-slate-50'
                                            : 'bg-slate-200'
                                    "
                                >
                                    <div
                                        class="text-center text-xs uppercase text-slate-500"
                                    >
                                        {{ critere.critere.nom }}
                                    </div>
                                    <div class="text-center text-xs">
                                        {{ critere.valeur }}
                                        <template
                                            v-if="
                                                critere.sous_criteres &&
                                                critere.sous_criteres.length > 0
                                            "
                                        >
                                            <ul
                                                v-for="sousCrit in critere.sous_criteres"
                                                :key="sousCrit.id"
                                                class="text-center text-xs"
                                            >
                                                <li
                                                    class="font-semibold text-gray-500"
                                                >
                                                    {{
                                                        sousCrit.sous_critere
                                                            .nom
                                                    }}:
                                                    <span
                                                        class="font-thin text-gray-700"
                                                        >{{
                                                            sousCrit.valeur
                                                        }}</span
                                                    >
                                                </li>
                                            </ul>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </Link>
    </TransitionRoot>
</template>
