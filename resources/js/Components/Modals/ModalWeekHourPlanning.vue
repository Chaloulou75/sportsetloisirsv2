<script setup>
import { ref, reactive, computed, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';

import {
    XCircleIcon,
    ChevronUpDownIcon,
    CheckCircleIcon,
    CurrencyEuroIcon,
} from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

const emit = defineEmits(['close']);

const props = defineProps({
    show: Boolean,
});

</script>
<template>
    <div>
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="open = false" class="relative z-10">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                    leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-full p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="w-full max-w-6xl min-h-full p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                                <form @submit.prevent="onSubmitAddTarifForm()" autocomplete="off">
                                    <DialogTitle as="div" class="flex items-center justify-between w-full">
                                        <h3 class="text-lg font-medium leading-6 text-gray-800">
                                            Planning
                                            <span class="text-blue-700">
                                            </span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon @click="emit('close')"
                                                class="w-6 h-6 text-gray-600 hover:text-gray-800" />
                                        </button>
                                    </DialogTitle>

                                    <vue-cal :time-from="6 * 60" :time-to="24 * 60" :time-step="30" locale="fr"
                                        :snap-to-time="15" :disable-views="['years', 'year']"
                                        :editable-events="{ title: true, drag: true, resize: true, delete: true, create: true }"
                                        :drag-to-create-threshold="15" />


                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
