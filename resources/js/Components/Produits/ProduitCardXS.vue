<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";

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
</script>

<template>
    <Link
        :href="link"
        :data="data"
        class="rounded-lg bg-gray-100 shadow-sm shadow-indigo-200"
    >
        <div
            class="relative h-24 w-auto max-w-xs rounded-md bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light"
            :class="headerClass"
        ></div>

        <div class="flex w-auto flex-col p-1 text-slate-700">
            <div
                v-if="produit.activite"
                class="inline-flex flex-col items-center justify-center px-2 py-1 text-center text-sm font-semibold text-gray-600"
            >
                {{ produit.activite.titre }}
                <span class="text-xs font-medium">{{
                    produit.structure.name
                }}</span>
            </div>

            <div v-if="produit.criteres.length > 0">
                <p class="text-sm font-medium">Critères:</p>
                <ul class="list-inside list-disc text-xs">
                    <li v-for="critere in produit.criteres" :key="critere.id">
                        {{ critere.critere.nom }}:
                        <span class="font-semibold">{{ critere.valeur }}</span>
                    </li>
                </ul>
            </div>
            <div v-if="produit.tarifs.length > 0">
                <p class="text-sm font-medium">Tarifs:</p>
                <ul class="list-inside list-disc text-xs">
                    <li v-for="tarif in produit.tarifs" :key="tarif.id">
                        {{ tarif.titre }}:
                        <span class="font-medium">
                            {{ tarif.amount }} € /
                            {{ tarif.tarif_type.type }}</span
                        >
                    </li>
                </ul>
            </div>
        </div>
    </Link>
</template>
