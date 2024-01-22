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
        class="mx-auto bg-slate-100/20 bg-cover bg-center bg-no-repeat px-10 py-2 bg-blend-soft-light md:py-6"
        :class="headerClass"
    >
        <div
            class="mx-auto my-2 flex w-full max-w-max flex-col items-center justify-center bg-slate-100/10 px-2 py-2 backdrop-blur-sm backdrop-opacity-60 md:w-auto md:px-6"
        >
            <h1
                class="w-auto text-center text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                v-if="$slots.title"
            >
                <slot name="title"></slot>
            </h1>
            <h2
                class="hidden w-auto text-center text-lg font-semibold leading-tight tracking-widest text-gray-600 md:block md:border-t-2 md:border-slate-400 md:text-2xl"
                v-if="$slots.subtitle"
            >
                <slot name="subtitle"></slot>
            </h2>
        </div>
        <div
            class="mx-auto hidden w-auto max-w-max flex-col items-center justify-center space-y-2 bg-gray-100/50 px-2 py-2 backdrop-blur-sm backdrop-opacity-60 md:px-4"
            v-if="$slots.ariane"
        >
            <slot name="ariane"></slot>
        </div>
    </div>
</template>
