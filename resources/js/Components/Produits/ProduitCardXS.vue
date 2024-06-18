<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";
import { TransitionRoot } from "@headlessui/vue";

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
        <div
            class="max-w-xs rounded-lg bg-gray-100 shadow-sm shadow-indigo-200"
        >
            <div
                class="relative h-24 w-auto max-w-xs rounded-md bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light"
                :class="headerClass"
            ></div>

            <div class="flex w-auto flex-col p-1 text-slate-700">
                <Link
                    :href="link"
                    :data="data"
                    v-if="produit.activite"
                    class="inline-flex flex-col items-center justify-center px-2 py-1 text-center text-sm font-semibold text-gray-600 transition duration-150 hover:text-indigo-600 focus:text-indigo-600 focus:outline-none"
                >
                    {{ produit.activite.titre }}
                    <span class="text-xs font-medium">{{
                        produit.structure.name
                    }}</span>
                </Link>
                <ul v-if="produit.adresse" class="text-xs">
                    <li class="list-inside list-disc font-semibold">
                        {{ produit.adresse.city_name }} ({{
                            produit.adresse.zip_code
                        }})
                    </li>
                </ul>
            </div>
        </div>
    </TransitionRoot>
</template>
