<script setup>
import { router } from "@inertiajs/vue3";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    ExclamationTriangleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

const emit = defineEmits(["close"]);

const props = defineProps({
    structure: Object,
    activite: Object,
    show: Boolean,
});

function destroyDiscipline(discipline) {
    const url = `/structures/${props.structure.slug}/disciplines/${discipline}`;
    router.delete(url, {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
        structure: props.structure.slug,
        discipline: discipline,
    });
}
</script>

<template>
    <Teleport to="body">
        <TransitionRoot as="template" :show="show">
            <Dialog as="div" class="relative z-10" @close="open = false">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-50"
                    />
                </TransitionChild>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div
                        class="flex items-end justify-center min-h-full p-4 text-center md:items-center md:p-0"
                    >
                        <TransitionChild
                            as="template"
                            enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <DialogPanel
                                class="relative overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl md:my-8 md:w-full md:max-w-xl"
                            >
                                <div
                                    class="px-4 pt-5 pb-4 bg-white md:p-6 md:pb-4"
                                >
                                    <div class="md:flex md:items-start">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full md:mx-0 md:h-10 md:w-10"
                                        >
                                            <ExclamationTriangleIcon
                                                class="w-6 h-6 text-red-600"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div
                                            class="mt-3 text-center md:mt-0 md:ml-4 md:text-left"
                                        >
                                            <DialogTitle
                                                as="div"
                                                class="flex items-start justify-between w-full"
                                            >
                                                <h3
                                                    class="w-full text-lg font-medium leading-6 text-center text-gray-800 md:text-left"
                                                >
                                                    Suppression de la discipline
                                                    <span class="text-blue-600">
                                                        {{
                                                            activite.disciplineName
                                                        }}</span
                                                    >
                                                </h3>

                                                <button
                                                    type="button"
                                                    class="hidden md:block"
                                                >
                                                    <XCircleIcon
                                                        @click="$emit('close')"
                                                        class="w-6 h-6 text-gray-600 hover:text-gray-800"
                                                    />
                                                </button>
                                            </DialogTitle>

                                            <div class="mt-2">
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    Etes vous sûr de vouloir
                                                    supprimer cette discipline?
                                                    Attention, cette action
                                                    supprimera toutes les
                                                    activités associées.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="justify-between px-4 py-3 space-y-2 bg-gray-50 md:flex md:space-y-0 md:px-6"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="$emit('close')"
                                        ref="cancelButtonRef"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="
                                            destroyDiscipline(
                                                activite.discipline_id
                                            )
                                        "
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </Teleport>
</template>
