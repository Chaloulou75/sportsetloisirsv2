<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const show = ref(true);
watch(
    usePage().props.flash,
    () => {
        show.value = true;
    },
    { deep: true }
);
</script>

<template>
    <transition name="slide-fade">
        <div
            v-if="
                (($page.props.flash.error ||
                    Object.keys($page.props.errors).length > 0) &&
                    show) ||
                ($page.props.flash.success && show)
            "
            class="sticky bottom-0 right-0 px-4 py-6"
        >
            <div
                v-if="$page.props.flash.success && show"
                class="mb-2 flex max-w-lg items-center justify-between rounded-lg bg-green-600 text-white"
            >
                <div class="flex items-center">
                    <svg
                        class="ml-4 mr-2 h-4 w-4 shrink-0 fill-current text-white"
                        viewBox="0 0 20 20"
                    >
                        <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
                    </svg>
                    <div class="py-4 text-sm font-medium text-white">
                        {{ $page.props.flash.success }}
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="show = false"
                >
                    <!-- fill-green-800 -->
                    <svg
                        class="block h-2 w-2 fill-current text-white"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>

            <div
                v-if="
                    ($page.props.flash.error ||
                        Object.keys($page.props.errors).length > 0) &&
                    show
                "
                class="mb-2 flex max-w-lg items-center justify-between rounded-lg bg-red-500 text-white"
            >
                <div class="flex items-center">
                    <svg
                        class="ml-4 mr-2 h-4 w-4 shrink-0 fill-current text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"
                        />
                    </svg>
                    <div
                        v-if="$page.props.flash.error"
                        class="py-4 text-sm font-medium text-white"
                    >
                        {{ $page.props.flash.error }}
                    </div>
                    <div v-else class="py-4 text-sm font-medium text-white">
                        <span
                            v-if="Object.keys($page.props.errors).length === 1"
                            >Il y a une erreur dans le formulaire.</span
                        >
                        <span v-else
                            >Il y a
                            {{ Object.keys($page.props.errors).length }} erreurs
                            dans le formulaire.</span
                        >
                    </div>
                </div>
                <button
                    type="button"
                    class="group mr-2 p-2"
                    @click="show = false"
                >
                    <svg
                        class="block h-2 w-2 fill-current text-white"
                        width="235.908"
                        height="235.908"
                        viewBox="278.046 126.846 235.908 235.908"
                    >
                        <path
                            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>

<style>
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(-250px);
    opacity: 0;
}
</style>
