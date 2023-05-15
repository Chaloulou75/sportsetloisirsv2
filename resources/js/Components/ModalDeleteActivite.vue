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

const props = defineProps({
    structure: Object,
    structureActivite: Object,
    show: Boolean,
});

// function destroy(id) {
//     router.delete(
//         `/structures/${props.structure.slug}/activite/${props.activite.id}`,
//         {
//             method: "DELETE",
//             preserveScroll: true,
//         }
//     );
// }

function destroyActivite(structureActivite) {
    const url = `/structures/${props.structure.slug}/activites/${structureActivite.id}`;
    router.delete(url, {
        preserveScroll: true,
        onSuccess: () => (open = false),
        structure: props.structure,
        activite: structureActivite,
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
                        class="fixed inset-0 bg-gray-800 bg-opacity-50 transition-opacity"
                    />
                </TransitionChild>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div
                        class="flex min-h-full items-end justify-center p-4 text-center md:items-center md:p-0"
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
                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all md:my-8 md:w-full md:max-w-xl"
                            >
                                <div
                                    class="bg-white px-4 pt-5 pb-4 md:p-6 md:pb-4"
                                >
                                    <div class="md:flex md:items-start">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 md:mx-0 md:h-10 md:w-10"
                                        >
                                            <ExclamationTriangleIcon
                                                class="h-6 w-6 text-red-600"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div
                                            class="mt-3 text-center md:mt-0 md:ml-4 md:text-left"
                                        >
                                            <DialogTitle
                                                as="div"
                                                class="flex w-full items-start justify-between"
                                            >
                                                <h3
                                                    class="w-full text-center text-lg font-medium leading-6 text-gray-800 md:text-left"
                                                >
                                                    Suppression de l'activité
                                                    <span class="text-blue-600">
                                                        {{
                                                            structureActivite.titre
                                                        }}</span
                                                    >
                                                </h3>

                                                <button
                                                    type="button"
                                                    class="hidden md:block"
                                                >
                                                    <XCircleIcon
                                                        @click="$emit('close')"
                                                        class="h-6 w-6 text-gray-600 hover:text-gray-800"
                                                    />
                                                </button>
                                            </DialogTitle>

                                            <div class="mt-2">
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    Etes vous sûr de vouloir
                                                    supprimer cette activité?
                                                    Attention, cette action
                                                    supprimera tous les produits
                                                    associés.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="justify-between space-y-2 bg-gray-50 px-4 py-3 md:flex md:space-y-0 md:px-6"
                                >
                                    <button
                                        type="button"
                                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="$emit('close')"
                                        ref="cancelButtonRef"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="button"
                                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="
                                            destroyActivite(structureActivite)
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
