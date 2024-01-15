<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({});
</script>
<template>
    <div
        class="flex w-full flex-col items-center border border-b border-gray-200 md:flex-row md:justify-start"
    >
        <div
            class="flex w-full flex-col items-center divide-x-2 divide-gray-200 md:w-auto md:flex-row"
        >
            <Link
                :href="route('admin.blog.index')"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                :class="{
                    'bg-indigo-500 text-white':
                        route().current('admin.blog.index'),
                }"
            >
                Gestion des articles
                <div
                    v-if="route().current('admin.blog.index')"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>
            <Link
                :href="route('admin.blog.tags.index')"
                :class="{
                    'bg-indigo-500 text-white': route().current(
                        'admin.blog.tags.index'
                    ),
                }"
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
            >
                Gestion des tags
                <div
                    v-if="route().current('admin.blog.tags.index')"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </Link>
        </div>
    </div>
</template>
