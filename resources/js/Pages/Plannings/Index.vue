<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import ButtonsActiviteEdit from "@/Components/Inscription/Activity/ButtonsActiviteEdit.vue";
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
    errors: Object,
    structure: Object,
});

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

    // for (const structureActivite of props.structureActivites) {
    //     for (const produit of structureActivite.produits) {
    //         if (produit.horaire) {
    //             const event = {
    //                 start: `${produit.horaire.dayopen} ${produit.horaire.houropen}`,
    //                 end: `${produit.horaire.dayclose} ${produit.horaire.hourclose}`,
    //                 title: structureActivite.titre,
    //                 content: structureActivite.description,
    //                 produitId: produit.id,
    //                 class: "course",
    //                 deletable: true,
    //                 resizable: true,
    //                 draggable: true,
    //             };

    //             events.push(event);
    //         }
    //     }
    // }

    return events;
};

const events = getEvents();

const onEventCreate = (event, deleteEventFunction) => {
    selectedEvent.value = event;
    deleteEventFunction.value = deleteEventFunction;

    return event;
};

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

const onSubmitEventForm = () => {
    const url = `/structures/${props.structure.slug}/plannings`;
    router.post(
        url,
        {
            structure_id: formPlanning.structure_id,
            event: selectedEvent.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                emit("close");
            },
            structure: props.structure.slug,
        }
    );
};

const formPlanning = reactive({
    structure_id: ref(props.structure.id),
    events: ref(events),
});

</script>

<template>
    <Head
        title="Le Planning"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
             +
            ' disciplines différentes en France. Choisissez parmi ' +
             +
            ' clubs sur notre site prêts à vous accueillir.'
        "
    />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Planning
                        <span class="text-blue-700"></span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
                            "
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Mes activités</Link
                        >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-6">
                <ButtonsActiviteEdit :structure="structure"/>
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
            </div>
        </div>
    </AppLayout>
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
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
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
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            v-model="selectedEvent.title"
                                            placeholder="Titre"
                                        />
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Ajoutez un produit
                                        </p>
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeModal"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
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
</template>
