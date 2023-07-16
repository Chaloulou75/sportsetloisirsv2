<script setup>
import { Link } from "@inertiajs/vue3";
let props = defineProps({
    disciplinesSimilaires: Object,
});
</script>
<template>
    <div class="w-full px-4 text-gray-600">
        <h3 class="mb-2 font-semibold">Les disciplines similaires</h3>
        <ul class="list-disc space-y-1.5">
            <li
                v-for="discipline in disciplinesSimilaires"
                :key="discipline.id"
            >
                <Link
                    :href="route('disciplines.show', discipline.slug)"
                    :active="
                        route().current('disciplines.show', discipline.slug)
                    "
                    class="hover:text-gray-800"
                >
                    {{ discipline.name }}
                </Link>
            </li>
        </ul>
    </div>
</template>
