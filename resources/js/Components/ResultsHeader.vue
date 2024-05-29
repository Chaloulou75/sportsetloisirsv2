<script setup>
const props = defineProps({
    discipline: Object,
});
</script>

<template>
    <div
        class="mx-auto bg-slate-100/20 bg-cover bg-center bg-no-repeat px-10 py-2 bg-blend-soft-light md:py-6"
    >
        <div
            class="mx-auto my-2 flex w-full max-w-max flex-col items-center justify-center px-2 py-2 md:w-auto md:px-6"
        >
            <h1
                class="w-auto text-center font-pt-sans text-3xl font-black leading-tight tracking-widest md:text-5xl"
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
                class="hidden w-auto text-center text-lg font-semibold leading-tight tracking-widest md:block md:border-t-2 md:border-slate-400 md:text-2xl"
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
