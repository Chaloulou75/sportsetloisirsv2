<script setup>
import { Link } from "@inertiajs/vue3";
let props = defineProps({
    citiesAround: Object,
});
</script>
<template>
    <div class="text-gray-600 w-full px-4">
        <h3 class="font-semibold mb-2">Les villes à proximités</h3>
        <ul class="list-disc space-y-1.5">
            <li v-for="city in citiesAround" :key="city.id">
                <Link :href="route('villes.show', city.id)"
                        :active="
                            route().current(
                                'villes.show',
                                city.id
                            )"
                        class="hover:text-gray-800"
                    >
                    {{ city.ville }} ({{ city.code_postal }})
                </Link>
            </li>
        </ul>
    </div>
</template>


