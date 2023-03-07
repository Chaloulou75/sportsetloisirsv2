<script setup>
const props = defineProps({
    formStep: Number,
});
</script>

<template>
    <ol class="mt-10 flex">
        <li
            class="relative w-[150px] text-center text-sm font-light italic text-gray-300 after:absolute after:left-[50%] after:top-[200%] after:z-50 after:h-5 after:w-5 after:rounded-full after:bg-indigo-500 after:content-['']"
        >
            Etape 1
        </li>
        <li
            class="relative w-[150px] text-center text-sm font-light italic text-gray-300 before:absolute before:left-[-50%] before:top-[calc(200%+0.5rem)] before:h-1 before:w-full before:bg-gray-300 before:content-[''] after:absolute after:left-[50%] after:top-[200%] after:z-50 after:h-5 after:w-5 after:rounded-full after:bg-gray-300 after:content-['']"
            :class="{
                '!important before:bg-indigo-500 after:bg-indigo-500':
                    formStep > 1 && formStep < 3,
            }"
        >
            Etape 2
        </li>

        <li
            class="relative w-[150px] text-center text-sm font-light italic text-gray-300 before:absolute before:left-[-50%] before:top-[calc(200%+0.5rem)] before:h-1 before:w-full before:bg-gray-300 before:content-[''] after:absolute after:left-[50%] after:top-[200%] after:z-50 after:h-5 after:w-5 after:rounded-full after:bg-gray-300 after:content-['']"
            :class="{
                '!important before:bg-indigo-500 after:bg-indigo-500':
                    formStep > 2 && formStep < 3,
            }"
        >
            Etape 3
        </li>
    </ol>
</template>
