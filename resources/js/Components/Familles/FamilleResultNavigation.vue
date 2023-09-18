<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    familles: Object,
});
</script>
<template>
    <div class="hidden bg-slate-700/60 py-2 md:block">
        <div class="mx-auto max-w-full">
            <div
                class="flex flex-col flex-wrap items-center justify-center space-y-1.5 md:flex-row md:space-y-0 md:divide-x md:divide-white"
            >
                <Link
                    preserve-scroll
                    class="inline-flex items-center px-8 pt-1 text-sm font-semibold leading-5 text-white transition duration-150 ease-in-out hover:text-gray-50 focus:text-gray-50 focus:outline-none"
                    :href="route('familles.show', famille.slug)"
                    :active="route().current('familles.show', famille.slug)"
                    v-for="(famille, index) in familles"
                    :key="index"
                    >{{ famille.name }}</Link
                >
            </div>
        </div>
    </div>
</template>
