<script setup></script>

<template>
    <div class="mx-auto max-w-full px-10 py-2 md:py-6">
        <div
            class="mx-auto my-2 flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/70 px-2 py-2 md:w-2/3 lg:w-1/2"
        >
            <slot name="title"></slot>
        </div>
        <div
            class="mx-auto flex w-auto flex-col items-center justify-center space-y-2 bg-gray-100/50 px-2 py-2 md:w-2/3 lg:w-1/2"
        >
            <slot name="ariane"></slot>
        </div>
    </div>
</template>
