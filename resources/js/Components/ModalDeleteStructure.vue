<script setup>
import { router } from "@inertiajs/vue3";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    structure: Object,
    show: Boolean,
});

function destroy(id) {
    router.delete(`/structures/${id}`, {
        method: "DELETE",
        preserveScroll: true,
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
                        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    />
                </TransitionChild>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div
                        class="flex items-end justify-center min-h-full p-4 text-center sm:items-center sm:p-0"
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
                                class="relative overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:w-full sm:max-w-lg"
                            >
                                <div
                                    class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4"
                                >
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                        >
                                            <ExclamationTriangleIcon
                                                class="w-6 h-6 text-red-600"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div
                                            class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                                        >
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                                >Suppression de la structure
                                                <span class="text-blue-600">
                                                    {{ structure.name }}</span
                                                ></DialogTitle
                                            >
                                            <div class="mt-2">
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    Etes vous sûr de vouloir
                                                    supprimer cette structure?
                                                    Attention, cette action
                                                    supprimera aussi toutes les
                                                    activités liées.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 sm:flex sm:flex-row-reverse sm:px-6"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="destroy(structure.id)"
                                    >
                                        Supprimer
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="$emit('close')"
                                        ref="cancelButtonRef"
                                    >
                                        Annuler
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
