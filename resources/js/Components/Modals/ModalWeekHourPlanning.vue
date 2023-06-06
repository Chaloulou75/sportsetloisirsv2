<script setup>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";

import { XCircleIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    show: Boolean,
    structure: Object,
    structureActivites: Object,
});

const getEvents = () => {
    const events = [];

    for (const structureActivite of props.structureActivites) {
        for (const produit of structureActivite.produits) {
            if (produit.horaire) {
                const event = {
                    start: `${produit.horaire.dayopen} ${produit.horaire.houropen}`,
                    end: `${produit.horaire.dayclose} ${produit.horaire.hourclose}`,
                    title: structureActivite.titre,
                    content: structureActivite.description,
                    produitId: produit.id,
                };

                events.push(event);
            }
        }
    }

    return events;
};

const events = getEvents();

const formPlanning = reactive({
    structure_id: ref(props.structure.id),
    events: ref(events),
});

const onSubmitPlanningForm = () => {
    router.post(
        `/structures/${props.structure.slug}/plannings`,
        {
            structure_id: formPlanning.structure_id,
            events: formPlanning.events,
        },
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                // formAddTarif.reset();
                emit("close");
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
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="min-h-full w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <form
                                    @submit.prevent="onSubmitPlanningForm()"
                                    autocomplete="off"
                                >
                                    <DialogTitle
                                        as="div"
                                        class="mb-4 flex w-full items-center justify-between"
                                    >
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-800"
                                        >
                                            Planning de vos activités
                                            <span class="text-blue-700"> </span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon
                                                @click="emit('close')"
                                                class="h-6 w-6 text-gray-600 hover:text-gray-800"
                                            />
                                        </button>
                                    </DialogTitle>

                                    <vue-cal
                                        small
                                        :time-from="6 * 60"
                                        :time-to="24 * 60"
                                        :time-step="30"
                                        locale="fr"
                                        :snap-to-time="15"
                                        :disable-views="['years', 'year']"
                                        :editable-events="{
                                            title: true,
                                            start: true,
                                            end: true,
                                            drag: true,
                                            resize: true,
                                            delete: true,
                                            create: true,
                                        }"
                                        :drag-to-create-threshold="15"
                                        :events="getEvents()"
                                        
                                    />

                                    <div
                                        class="mt-4 flex w-full items-center justify-between"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            :disabled="formPlanning.processing"
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                        >
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
