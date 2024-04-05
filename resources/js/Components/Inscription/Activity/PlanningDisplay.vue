<script setup>
import { ref, computed, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
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
import { ChevronUpDownIcon, CheckCircleIcon } from "@heroicons/vue/20/solid";

const emit = defineEmits(["close"]);

const props = defineProps({
    errors: Object,
    show: Boolean,
    structure: Object,
    structureActivites: Object,
});

const selectedActivite = ref(props.structureActivites[0]);
const selectedProduct = ref(props.structureActivites[0]?.produits?.[0] ?? "");
const selectedTitle = ref(props.structureActivites[0]?.titre ?? "");
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
        for (const planning of structureActivite.plannings) {
            if (planning) {
                const event = {
                    start: planning.start,
                    end: planning.end,
                    title: `
                        <p class="text-sm text-gray-900 uppercase">
                            ${planning.title ?? structureActivite.titre}
                        </p>
                    `,
                    content: `
                        <ul class="text-xs font-semibold text-gray-800">
                            <li>${structureActivite.discipline.name}</li>
                            <li>Produit n°: ${planning.produit_id}</li>
                        </ul>
                    `,
                    contentFull: `
                        <p class="text-xs text-gray-600 truncate">
                            ${structureActivite.description}
                        </p>
                    `,
                    activiteId: structureActivite.id,
                    produitId: planning.produit_id,
                    planningId: planning.id,
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

const formPlanning = useForm({
    structure_id: props.structure.id,
    title: selectedTitle.value,
    activite: selectedActivite.value,
    produit: selectedProduct.value,
    events: events,
});

const filteredProducts = computed(() => {
    if (formPlanning.activite) {
        const selectedActiviteId = formPlanning.activite.id;
        const activite = props.structureActivites.find(
            (activite) => activite.id === selectedActiviteId
        );
        if (activite) {
            return activite.produits ?? [];
        }
    }
    return [];
});

watch(
    () => formPlanning.activite,
    (newActivite) => {
        if (newActivite) {
            formPlanning.produit = newActivite.produits[0] || null;
            formPlanning.title = newActivite.titre;
        } else {
            selectedProduct.value = null;
        }
    }
);

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
            title: formPlanning.title,
            activite_id: formPlanning.activite.id,
            produit_id: formPlanning.produit.id,
            event: selectedEvent.value,
        },
        {
            preserveScroll: true,
            only: ["structureActivites"],
            remember: true,
            onSuccess: () => {
                formPlanning.reset();
                closeModal();
            },
            structure: props.structure.slug,
        }
    );
};

const handleEventDeleted = (event) => {
    router.delete(
        route("structures.plannings.destroy", {
            structure: props.structure.slug,
            planning: event.planningId,
        }),
        {
            preserveScroll: true,
            only: ["structureActivites"],
            onSuccess: () => {
                closeModal();
            },
        }
    );
};

const handleEventChanged = (event) => {
    selectedEvent.value = event.event;
    router.put(
        route("structures.plannings.update", {
            structure: props.structure.slug,
            planning: event.event.planningId,
        }),
        {
            _method: "put",
            event: selectedEvent.value,
        },
        {
            preserveScroll: true,
            only: ["structureActivites"],
        }
    );
};
</script>
<template>
    <div v-if="structureActivites.length === 0">
        <p class="font-semibold italic text-gray-600">
            Pas d'activité liée. Créer d'abord une activité.
        </p>
    </div>
    <div v-else class="mb-4 flex w-full items-center justify-between">
        <h2 class="text-lg font-medium leading-6 text-gray-800">
            Planning de vos activités
            <span class="text-xs text-blue-700">
                Supprimer un événement (en cliquant et en maintenant un
                événement)
            </span>
        </h2>
    </div>
    <div
        v-show="structureActivites.length > 0"
        class="mt-6 min-h-full w-full overflow-x-auto rounded-sm shadow-lg"
    >
        <vue-cal
            small
            :time-from="6 * 60"
            :time-to="24 * 60"
            :time-step="30"
            locale="fr"
            :snap-to-time="15"
            show-time-in-cells
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
            @event-duration-change="handleEventChanged"
            @event-drop="handleEventChanged"
            @event-title-change="handleEventChanged"
        />
    </div>
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
                <div class="fixed inset-0 bg-black bg-opacity-50" />
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
                            class="w-full max-w-md transform rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
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
                                        v-model="formPlanning.title"
                                        :placeholder="
                                            formPlanning.title
                                                ? formPlanning.title
                                                : 'Titre'
                                        "
                                    />
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Exemple:
                                        <span class="font-semibold">
                                            {{ formPlanning.title }}</span
                                        >.
                                    </p>
                                </div>

                                <Listbox
                                    class="mt-4 w-full"
                                    v-model="formPlanning.activite"
                                >
                                    <div class="relative mt-1">
                                        <label
                                            for="activite"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Activité liée:
                                        </label>
                                        <ListboxButton
                                            class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                        >
                                            <span class="block truncate">{{
                                                formPlanning.activite.titre
                                            }}</span>
                                            <span
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                            >
                                                <ChevronUpDownIcon
                                                    class="h-5 w-5 text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </ListboxButton>

                                        <transition
                                            leave-active-class="transition duration-100 ease-in"
                                            leave-from-class="opacity-100"
                                            leave-to-class="opacity-0"
                                        >
                                            <ListboxOptions
                                                class="absolute z-20 mt-1 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                            >
                                                <ListboxOption
                                                    v-slot="{
                                                        active,
                                                        selected,
                                                    }"
                                                    v-for="activite in props.structureActivites"
                                                    :key="activite.id"
                                                    :value="activite"
                                                    as="template"
                                                >
                                                    <li
                                                        :class="[
                                                            active
                                                                ? 'bg-amber-100 text-amber-900'
                                                                : 'text-gray-900',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]"
                                                    >
                                                        <span
                                                            :class="[
                                                                selected
                                                                    ? 'font-medium'
                                                                    : 'font-normal',
                                                                'block truncate',
                                                            ]"
                                                            >{{
                                                                activite.titre
                                                            }}</span
                                                        >
                                                        <span
                                                            v-if="selected"
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                        >
                                                            <CheckCircleIcon
                                                                class="h-5 w-5"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                    </li>
                                                </ListboxOption>
                                            </ListboxOptions>
                                        </transition>
                                    </div>
                                </Listbox>

                                <Listbox
                                    v-if="
                                        selectedActivite &&
                                        filteredProducts.length > 0
                                    "
                                    class="mt-4 w-full"
                                    v-model="formPlanning.produit"
                                >
                                    <div class="relative mt-1">
                                        <label
                                            for="produit"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Produit lié:
                                        </label>
                                        <ListboxButton
                                            class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                        >
                                            <span class="block truncate">{{
                                                formPlanning.produit.id
                                            }}</span>
                                            <span
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                            >
                                                <ChevronUpDownIcon
                                                    class="h-5 w-5 text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </ListboxButton>

                                        <transition
                                            leave-active-class="transition duration-100 ease-in"
                                            leave-from-class="opacity-100"
                                            leave-to-class="opacity-0"
                                        >
                                            <ListboxOptions
                                                class="absolute mt-1 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                            >
                                                <ListboxOption
                                                    v-slot="{
                                                        active,
                                                        selected,
                                                    }"
                                                    v-for="produit in filteredProducts"
                                                    :key="produit.id"
                                                    :value="produit"
                                                    as="template"
                                                >
                                                    <li
                                                        :class="[
                                                            active
                                                                ? 'bg-amber-100 text-amber-900'
                                                                : 'text-gray-900',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]"
                                                    >
                                                        <span
                                                            :class="[
                                                                selected
                                                                    ? 'font-medium'
                                                                    : 'font-normal',
                                                                'block truncate',
                                                            ]"
                                                            >{{
                                                                produit.id
                                                            }}</span
                                                        >
                                                        <span
                                                            v-if="selected"
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                        >
                                                            <CheckCircleIcon
                                                                class="h-5 w-5"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                    </li>
                                                </ListboxOption>
                                            </ListboxOptions>
                                        </transition>
                                    </div>
                                </Listbox>

                                <p
                                    class="py-2 text-sm text-red-600"
                                    v-if="
                                        selectedActivite &&
                                        !filteredProducts.length > 0
                                    "
                                >
                                    Il faut au préalable ajouter au moins un
                                    produit à l'activité.
                                </p>

                                <div class="mt-4 flex justify-between">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                        @click="closeModal"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        v-if="
                                            selectedActivite &&
                                            filteredProducts.length > 0
                                        "
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
<style>
.course {
    @apply bg-green-300 text-blue-700;
}
</style>
