<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { BookmarkIcon, MapPinIcon } from "@heroicons/vue/24/outline";

let props = defineProps({
    activite: Object,
    link: {
        type: String,
        default: null,
    },
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
        <Link
            :href="link"
            class="block rounded-lg shadow-sm shadow-indigo-200 hover:shadow-xl md:px-0"
        >
            <img
                alt="Home"
                src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                class="h-56 w-full rounded-md object-cover"
            />

            <div class="mt-2 p-3">
                <dl class="flex flex-col">
                    <p
                        class="py-1.5 text-sm font-medium tracking-widest text-gray-600"
                    >
                        {{ activite.titre }}
                    </p>
                    <div class="py-1.5"></div>

                    <div class="py-1.5">
                        <dt class="sr-only">Produits</dt>
                        <div
                            v-for="produit in activite.produits"
                            :key="produit.id"
                        >
                            <p>{{ produit }}</p>
                        </div>
                    </div>

                    <div class="flex items-center py-1.5">
                        <dt class="sr-only">Ville</dt>
                        <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                    </div>

                    <div
                        class="mt-auto flex items-center justify-around gap-1 py-1.5 text-xs"
                    >
                        <div class="inline-flex shrink-0 items-center">
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
                        </div>

                        <div class="inline-flex shrink-0 items-center">
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
                        </div>

                        <div class="inline-flex shrink-0 items-center">
                            <BookmarkIcon
                                class="mr-1 h-4 w-4 text-indigo-700"
                            />
                        </div>
                    </div>
                </dl>
            </div>
        </Link>
    </template>
</template>
