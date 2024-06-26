<script setup>
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
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

const selectedEvent = ref({});

const isOpen = ref(false);

function closeModal() {
    isOpen.value = false;
    selectedEvent.value = {};
}

function openModal() {
    isOpen.value = true;
}

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
                    class: "course",
                    deletable: true,
                    resizable: true,
                    draggable: true,
                };

                events.push(event);
            }
        }
    }

    return events;
};

const events = getEvents();

const handleEventDeleted = (event) => {
    const url = `/structures/${props.structure.slug}/plannings/${event.produitId}`;
    router.delete(url, {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
        structure: props.structure.slug,
        planning: event.produitId,
    });
};

const onEventCreate = (event, deleteEventFunction) => {
    selectedEvent.value = event;
    deleteEventFunction.value = deleteEventFunction;

    return event;
};

const onSubmitEventForm = () => {
    router.post(
        route("structures.plannings.store", {
            structure: props.structure.slug,
        }),
        {
            structure_id: formPlanning.structure_id,
            discipline_id: formPlanning.discipline_id,
            event: selectedEvent.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                emit("close");
            },
        }
    );
};

const formPlanning = useForm({
    structure_id: props.structure.id,
    discipline_id: props.structureActivites[0].discipline_id,
    events: events,
});

const onSubmitPlanningForm = () => {
    // formPlanning.events.forEach((event) => {
    //     handleEventCreate(event);
    // });
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
                    <div
                        class="fixed inset-0 transition-opacity bg-black bg-opacity-50"
                    />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex items-center justify-center min-h-full p-4 text-center"
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
                                class="w-full max-w-6xl min-h-full p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                            >
                                <form
                                    @submit.prevent="onSubmitPlanningForm()"
                                    autocomplete="off"
                                >
                                    <DialogTitle
                                        as="div"
                                        class="flex items-center justify-between w-full mb-4"
                                    >
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-800"
                                        >
                                            Planning de vos activités
                                            <span class="text-xs text-blue-700">
                                                Supprimer un événement (en
                                                cliquant et en maintenant un
                                                événement)
                                            </span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon
                                                @click="emit('close')"
                                                class="w-6 h-6 text-gray-600 hover:text-red-600"
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
                                        :cell-click-hold="false"
                                        :editable-events="{
                                            title: true,
                                            drag: true,
                                            resize: true,
                                            delete: true,
                                            create: true,
                                        }"
                                        :drag-to-create-threshold="15"
                                        class="vuecal--full-height-delete"
                                        :events="getEvents()"
                                        :on-event-create="onEventCreate"
                                        @event-drag-create="isOpen = true"
                                        @event-delete="handleEventDeleted"
                                    />

                                    <div
                                        class="flex items-center justify-between w-full mt-4"
                                    >
                                        <button
                                            type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            :disabled="formPlanning.processing"
                                            :class="{
                                                'opacity-25':
                                                    formPlanning.processing,
                                            }"
                                            type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                        >
                                            <LoadingSVG
                                                v-if="formPlanning.processing"
                                            />
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

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>
                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex items-center justify-center min-h-full p-4 text-center"
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
                                class="w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                            >
                                <form
                                    @submit.prevent="onSubmitEventForm()"
                                    autocomplete="off"
                                >
                                    <DialogTitle
                                        as="div"
                                        class="text-lg font-medium leading-6 text-gray-900"
                                    >
                                        <input
                                            type="text"
                                            name="title"
                                            id="title"
                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                            v-model="selectedEvent.title"
                                            placeholder="Titre"
                                        />
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Ajoutez un produit
                                        </p>
                                    </div>

                                    <div class="flex justify-between mt-4">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-blue-900 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeModal"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-blue-900 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                        >
                                            Enregister
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
<style>
.course {
    @apply bg-green-300 text-blue-700;
}
</style>
