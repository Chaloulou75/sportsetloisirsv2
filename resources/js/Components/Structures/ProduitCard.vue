<script setup>
import { MapPinIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["mouseover", "mouseout"]);

const props = defineProps({
    produit: Object,
});
</script>

<template>
    <template>
        <div
            class="block rounded-lg shadow-sm shadow-indigo-200 transition duration-300 ease-in-out hover:shadow-2xl md:px-0 md:hover:scale-105"
        >
            <div class="relative">
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
                        v-if="produit.activite"
                        class="px-2 py-1.5 text-center text-sm font-semibold tracking-wide text-gray-600"
                    >
                        {{ produit.activite.titre }}
                    </p>

                    <div class="w-full divide-y divide-slate-200">
                        <dt class="sr-only">Produit</dt>
                        <p class="text-xs">Produit n° {{ produit.id }}:</p>
                        <div class="flex items-center py-1.5 text-xs">
                            <dt class="sr-only">Ville</dt>
                            <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                            <p v-if="produit.adresse" class="font-semibold">
                                {{ produit.adresse.city }} ({{
                                    produit.adresse.zip_code
                                }})
                            </p>
                        </div>
                        <div v-if="produit.criteres">
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
                        </div>
                        <div v-if="produit.tarifs">
                            <p class="mt-2 text-sm">Tarifs:</p>
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
            </div>
        </div>
    </template>
</template>
