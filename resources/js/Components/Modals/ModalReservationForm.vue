<script setup>
import { ref, computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Forms/TextInput.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    produit: Object,
    catTarifId: String,
    show: Boolean,
});

const filteredCatTarif = computed(() => {
    if (!props.catTarifId || !props.produit || !props.produit.catTarifs) {
        return [];
    }
    return props.produit.catTarifs.filter(
        (catTarif) => catTarif.id === props.catTarifId
    );
});

const onSubmit = () => {};
</script>
<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="open = false" class="relative z-[1099]">
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
            <div
                class="fixed inset-0 flex items-center justify-center w-screen p-4 text-center"
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
                        class="w-full max-w-5xl p-6 space-y-5 overflow-visible text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                    >
                        <DialogTitle
                            as="div"
                            class="flex items-center justify-between w-full"
                        >
                            <h3
                                class="text-lg font-medium leading-6 text-gray-800"
                            >
                                Formulaire de réservation
                            </h3>
                            <button type="button">
                                <XCircleIcon
                                    @click="emit('close')"
                                    class="w-6 h-6 text-gray-600 hover:text-red-600"
                                />
                            </button>
                        </DialogTitle>
                        <div>
                            <form
                                @submit.prevent="onSubmit()"
                                autocomplete="off"
                            >
                                <div class="flex flex-col space-y-3">
                                    Hello
                                    <!-- <div
                                        v-for="field in filteredCatTarif
                                            .cat_tarif_type
                                            .tarif_booking_fields"
                                        :key="field.id"
                                    >

                                    </div> -->
                                </div>
                                <div
                                    class="flex items-center justify-between w-full mt-4"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click.prevent="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG />
                                        Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
