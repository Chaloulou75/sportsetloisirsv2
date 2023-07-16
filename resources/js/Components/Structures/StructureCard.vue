<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { BookmarkIcon, MapPinIcon } from "@heroicons/vue/24/outline";

let props = defineProps({
    structure: Object,
});

const getUniqueActivitesDiscipline = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.discipline.name)) {
          uniqueNames.add(activite.discipline.name);
          return true;
        }
        return false;
    });
};

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

</script>

<template>
    <Link :href="route('structures.show', structure.slug)"
        :active="
            route().current(
                'structures.show',
                structure.slug
            )
        "
        class="block rounded-lg p-4 shadow-sm shadow-indigo-200 hover:shadow-xl"
        >
        <img
            alt="Home"
            src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
            class="h-56 w-full rounded-md object-cover"
        />

        <div class="mt-2">
            <dl>
                <p
                    class="text-sm font-medium uppercase tracking-widest text-pink-500"
                >
                    {{ structure.structuretype.name }}
                </p>
                <p
                    class="text-sm font-medium tracking-widest text-gray-600"
                >
                    {{ structure.name }}
                </p>
                <span
                    class="font-semibold text-sm"
                    v-for="(activite, index) in getUniqueActivitesTitre(structure.activites)"
                    :key="activite.id"
                >
                    {{ activite.titre }}
                    <span v-if="index < getUniqueActivitesTitre(structure.activites).length - 1"> - </span>
                </span>
                <div class="py-1.5">
                    <dt class="sr-only">tarif</dt>

                    <span
                        v-for="(tarif, index) in structure.tarifs"
                        :key="tarif.id"
                    >
                        <dd class="text-sm text-gray-500">{{ tarif.tarif_type.type }}: <span class="font-semibold">{{ formatCurrency( tarif.amount) }}</span> </dd>
                        <span v-if="index < structure.tarifs.length - 1"> | </span>

                    </span>
                </div>



                <div class="flex items-center">
                    <dt class="sr-only">Ville</dt>
                    <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                    <dd class="font-medium text-sm">{{ structure.city }} ({{ structure.zip_code }})</dd>
                </div>

                <div class="mt-6 flex items-center gap-1 text-xs">
                    <div class="inline-flex shrink-0 items-center">
                        <svg
                        class="h-4 w-4 text-indigo-700"
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
                        <p class="text-gray-600 font-medium">{{ structure.disciplines_count }}    <span v-if="structure.disciplines_count > 1" >Disciplines</span>
                        <span v-else>Discipline</span>
                        </p>
                    </div>

                    <div class="inline-flex shrink-0 items-center">
                        <svg
                        class="h-4 w-4 text-indigo-700"
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

                        <p class="text-gray-600 font-medium">{{ structure.activites_count }}
                            <span v-if="structure.activites_count > 1">Activités</span>
                            <span v-else>Activité</span>
                        </p>
                    </div>

                    <div class="inline-flex shrink-0 items-center">
                        <BookmarkIcon class="h-4 w-4 text-indigo-700" />
                        <p class="text-gray-600 font-medium">{{ structure.produits_count }}
                            <span v-if="structure.produits_count > 1">Produits</span>
                            <span v-else>Produit</span>
                        </p>
                    </div>
                </div>
            </dl>
        </div>
    </Link>
</template>


