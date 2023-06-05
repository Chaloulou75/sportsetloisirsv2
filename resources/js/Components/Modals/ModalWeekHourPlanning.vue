<script setup>
import { ref, reactive, computed, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';

import {
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const emit = defineEmits(['close']);

const props = defineProps({
    show: Boolean,
    structure: Object,
    structureActivites: Object,
});

const events = reactive([
    {
        start: '2023-06-06 14:00',
        end: '2023-06-06 15:15',
        title: 'Badminton',
        content: ''
    },
    {
        start: '2023-06-07 16:00',
        end: '2023-06-07 17:15',
        title: 'Golf',
        content: ''
    },
    {
        start: '2023-06-06 18:00',
        end: '2023-06-06 19:15',
        title: 'Produit 1',
        content: ''
    }
]);

const formPlanning = reactive({
    structure_id: ref(props.structure.id),
    titre: ref(null),
});

const onSubmitPlanningForm = () => {
    router.post(
        `/structures/${props.structure.slug}/plannings`,
        {
            structure_id: formPlanning.structure_id,
        },
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                // formAddTarif.reset();
                emit('close');
            },
            structure: props.structure.slug,
        }
    );
};
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
                                <form @submit.prevent="onSubmitPlanningForm()" autocomplete="off">
                                    <DialogTitle as="div" class="flex items-center justify-between w-full mb-4">
                                        <h3 class="text-lg font-medium leading-6 text-gray-800 ">
                                            Planning de vos activités
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
                                        :drag-to-create-threshold="15" :events="events" />

                                    <div class="flex items-center justify-between w-full mt-4">
                                        <button type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')">
                                            Annuler
                                        </button>
                                        <button :disabled="formPlanning.processing" type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
                                            Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
