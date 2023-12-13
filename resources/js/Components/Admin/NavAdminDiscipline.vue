<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    discipline: Object,
});
</script>
<template>
    <div
        class="flex w-full flex-col items-center border border-b border-gray-200 md:flex-row md:justify-start"
    >
        <div
            class="flex w-full flex-col items-center divide-x-2 divide-gray-200 md:w-auto md:flex-row"
        >
            <Link
                :href="route('admin.disciplines.informations.edit', discipline)"
                :class="{
                    'bg-indigo-500 text-white': route().current(
                        'admin.disciplines.informations.edit',
                        discipline
                    ),
                }"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
            >
                Informations
                <div
                    v-if="
                        route().current(
                            'admin.disciplines.informations.edit',
                            discipline
                        )
                    "
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>
            <Link
                :href="route('admin.disciplines.familles.edit', discipline)"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                :class="{
                    'bg-indigo-500 text-white': route().current(
                        'admin.disciplines.familles.edit',
                        discipline
                    ),
                }"
            >
                Familles
                <div
                    v-if="
                        route().current(
                            'admin.disciplines.familles.edit',
                            discipline
                        )
                    "
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>

            <Link
                :href="route('admin.disciplines.similaires.edit', discipline)"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                :class="{
                    'bg-indigo-500 text-white': route().current(
                        'admin.disciplines.similaires.edit',
                        discipline
                    ),
                }"
            >
                Disciplines similaires
                <div
                    v-if="
                        route().current(
                            'admin.disciplines.similaires.edit',
                            discipline
                        )
                    "
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>
            <Link
                :href="route('admin.disciplines.categories.edit', discipline)"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                :class="{
                    'bg-indigo-500 text-white': route().current(
                        'admin.disciplines.categories.edit',
                        discipline
                    ),
                }"
            >
                Catégories
                <div
                    v-if="
                        route().current(
                            'admin.disciplines.categories.edit',
                            discipline
                        )
                    "
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>
        </div>
    </div>
</template>
