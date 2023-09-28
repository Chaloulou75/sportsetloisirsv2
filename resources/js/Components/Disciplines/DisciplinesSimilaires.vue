<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    disciplinesSimilaires: Object,
});
</script>
<template>
    <div
        class="mx-auto w-full rounded bg-gray-50 px-4 py-4 text-gray-600 shadow-lg"
    >
        <h3 class="mb-2 text-center font-semibold">
            Les disciplines similaires
        </h3>
        <ul class="list-inside list-disc space-y-1.5">
            <li
                v-for="discipline in disciplinesSimilaires"
                :key="discipline.id"
            >
                <Link
                    :href="route('disciplines.show', discipline.slug)"
                    :active="
                        route().current('disciplines.show', discipline.slug)
                    "
                    class="hover:text-gray-800 hover:underline"
                >
                    {{ discipline.name }}
                </Link>
            </li>
        </ul>
    </div>
</template>
