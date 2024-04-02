<script setup>
import { classMapping } from "@/Utils/classMapping.js";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { TransitionRoot } from "@headlessui/vue";

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
    } else if (props.discipline && props.discipline.discipline_similaire_id) {
        const disciplineId = props.discipline.discipline_similaire_id;
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
            class="group relative flex h-full w-full items-center justify-center rounded bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-overlay shadow transition duration-150 ease-in-out hover:bg-blend-multiply hover:shadow-md md:w-auto"
            :class="headerClass"
        >
            <div
                class="inline-flex w-full flex-col items-center justify-center rounded px-6 py-3 text-center text-base font-semibold transition duration-150 md:text-lg"
                :class="{
                    'text-gray-100 group-hover:text-white':
                        discipline?.theme === 'dark',
                    'text-gray-800 group-hover:text-black':
                        discipline?.theme === 'light' || !discipline,
                }"
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
    </TransitionRoot>
</template>
