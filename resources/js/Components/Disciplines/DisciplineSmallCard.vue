<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";

const props = defineProps({
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
</script>

<template>
    <Link
        :href="link"
        class="group relative flex h-20 w-full items-center justify-center rounded bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-overlay shadow-sm transition duration-150 ease-in-out hover:shadow-lg md:w-auto"
        :class="headerClass"
    >
        <div
            class="inline-flex w-full flex-col items-center justify-center rounded px-12 py-3 text-center text-base font-semibold text-gray-900 shadow-sm transition duration-150 group-hover:text-white md:text-lg"
        >
            {{ discipline.name }}

            <div
                v-if="
                    discipline.structure_produits_count &&
                    discipline.structure_produits_count > 0
                "
                class="text-xs italic"
            >
                ({{ discipline.structure_produits_count }}
                <span v-if="discipline.structure_produits_count > 1"
                    >activités</span
                >
                <span v-else>activité</span>)
            </div>
            <div
                v-else-if="
                    discipline.structure_produits_count &&
                    discipline.structure_produits_count === 0
                "
                class="text-xs italic"
            >
                (Pas encore d'activité inscrite)
            </div>
        </div>
    </Link>
</template>
