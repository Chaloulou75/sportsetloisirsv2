<script setup>
import NavLink from "@/Components/NavLink.vue";
const props = defineProps({
    familles: Object,
});
</script>
<template>
    <div class="hidden bg-transparent sm:block">
        <div class="mx-auto max-w-full px-2 py-1 sm:px-3 lg:px-4">
            <div
                class="grid grid-cols-1 place-items-start gap-2 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-10 lg:place-items-center"
            >
                <NavLink
                    :href="route('familles.show', famille.slug)"
                    :active="route().current('familles.show', famille.slug)"
                    v-for="(famille, index) in familles"
                    :key="index"
                    >{{ famille.name }}</NavLink
                >
            </div>
        </div>
    </div>
</template>
