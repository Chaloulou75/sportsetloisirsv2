<script setup>
const props = defineProps({
    structure: Object,
    categorie: Object,
});
</script>

<template>
    <div
        aria-label="Breadcrumb"
        class="mx-auto flex w-full justify-center px-1 pb-6"
    >
        <ol
            class="flex cursor-default overflow-hidden rounded-sm border border-gray-200 text-gray-600 md:rounded-md"
        >
            <li class="flex items-center">
                <div
                    class="flex h-12 items-center gap-1.5 px-4 transition md:h-16"
                    :class="{
                        'bg-blue-600 text-white':
                            route().current('structures.create') ||
                            route().current('structures.edit'),
                        'bg-white': route().current(
                            'structures.activites.edit'
                        ),
                        'bg-gray-100': route().current(
                            'structures.activites.index'
                        ),
                    }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="hidden h-4 w-4 md:block"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                    </svg>

                    <span class="ms-1.5 text-xs font-medium md:text-base">
                        1. Information de votre structure
                    </span>
                </div>
            </li>

            <li class="relative flex items-center">
                <span
                    class="absolute inset-y-0 -start-px h-12 w-4 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180 md:h-16"
                    :class="{
                        'bg-blue-600 text-white':
                            route().current('structures.create') ||
                            route().current('structures.edit'),
                        'bg-white': route().current(
                            'structures.activites.edit'
                        ),
                        'bg-gray-100': route().current(
                            'structures.activites.index'
                        ),
                    }"
                >
                </span>

                <div
                    class="flex h-12 items-center pe-4 ps-8 text-xs font-medium transition md:h-16 md:text-base"
                    :class="{
                        'bg-blue-600 text-white': route().current(
                            'structures.activites.index'
                        ),
                        'bg-gray-100': !route().current(
                            'structures.activites.index'
                        ),
                    }"
                >
                    2. Ajouter des activités
                </div>
            </li>
            <li class="relative flex items-center">
                <span
                    class="absolute inset-y-0 -start-px h-12 w-4 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180 md:h-16"
                    :class="{
                        'bg-blue-600 text-white': route().current(
                            'structures.activites.index'
                        ),
                        'bg-gray-100': !route().current(
                            'structures.activites.index'
                        ),
                    }"
                >
                </span>

                <div
                    class="flex h-12 items-center pe-4 ps-8 text-xs font-medium transition md:h-16 md:text-base"
                    :class="{
                        'bg-blue-600 text-white': route().current(
                            'structures.activites.edit'
                        ),
                        'bg-white': !route().current(
                            'structures.activites.edit'
                        ),
                    }"
                >
                    3. Activités, tarifs et horaires
                </div>
            </li>
        </ol>
    </div>
</template>
