<script setup>
import { computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";

const props = defineProps({
    discipline: Object,
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
    <div
        class="px-10 py-2 mx-auto bg-center bg-no-repeat bg-cover bg-slate-100/20 bg-blend-soft-light md:py-6"
    >
        <div
            class="flex flex-col items-center justify-center w-full px-2 py-2 mx-auto my-2 max-w-max md:w-auto md:px-6"
        >
            <h1
                class="w-auto text-2xl font-black leading-tight tracking-widest text-center md:text-4xl"
                :class="{
                    'text-white': discipline?.theme === 'dark',
                    'text-gray-900':
                        discipline?.theme === 'light' || !discipline,
                }"
                v-if="$slots.title"
            >
                <slot name="title"></slot>
            </h1>
            <h2
                :class="{
                    'text-white': discipline?.theme === 'dark',
                    'text-gray-900':
                        discipline?.theme === 'light' || !discipline,
                }"
                class="hidden w-auto text-lg font-semibold leading-tight tracking-widest text-center md:block md:border-t-2 md:border-slate-400 md:text-2xl"
                v-if="$slots.subtitle"
            >
                <slot name="subtitle"></slot>
            </h2>
        </div>
        <div
            class="flex-col items-center justify-center hidden w-auto px-2 py-2 mx-auto space-y-2 max-w-max bg-gray-100/50 backdrop-blur-sm backdrop-opacity-60 md:px-4"
            v-if="$slots.ariane"
        >
            <slot name="ariane"></slot>
        </div>
    </div>
</template>
