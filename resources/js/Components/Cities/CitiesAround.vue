<script setup>
import { Link } from "@inertiajs/vue3";
let props = defineProps({
    citiesAround: Object,
});
</script>
<template>
    <div class="w-full px-4 text-gray-600">
        <h3 class="mb-2 font-semibold">Les villes à proximités</h3>
        <ul class="list-disc space-y-1.5">
            <li v-for="city in citiesAround" :key="city.id">
                <Link
                    :href="route('villes.show', city.id)"
                    :active="route().current('villes.show', city.id)"
                    class="hover:text-gray-800"
                >
                    {{ city.ville }} ({{ city.code_postal }})
                </Link>
            </li>
        </ul>
    </div>
</template>
