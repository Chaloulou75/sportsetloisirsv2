<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["close"]);

const props = defineProps({
    activite: Object,
    structure: Object,
    show: Boolean,
});

const formEdit = useForm({
    _method: "put",
    titre: props.activite.titre ?? null,
    description: props.activite.description ?? null,
    image: null,
});

const submitForm = () => {
    formEdit.post(
        route("structures.activites.update", {
            structure: props.structure.slug,
            activite: props.activite.id,
        }),
        {
            errorBag: "formEdit",
            only: ["structure"],
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                formEdit.reset();
                emit("close");
            },
        }
    );
};
</script>
<template>
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
                            class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <form
                                @submit.prevent="submitForm()"
                                enctype="multipart/form-data"
                                autocomplete="off"
                            >
                                <DialogTitle
                                    as="div"
                                    class="flex w-full items-center justify-between"
                                >
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-800"
                                    >
                                        Modifier une activité
                                    </h3>
                                    <button type="button">
                                        <XCircleIcon
                                            @click="emit('close')"
                                            class="h-6 w-6 text-gray-600 hover:text-gray-800"
                                        />
                                    </button>
                                </DialogTitle>
                                <div class="mt-2 w-full">
                                    <div class="flex flex-col space-y-3">
                                        <div>
                                            <label
                                                for="image"
                                                class="block text-sm font-medium normal-case text-gray-700"
                                                >Ajouter ou modifier la photo ou
                                                l'image:</label
                                            >
                                            <div class="flex">
                                                <input
                                                    class="mt-1 text-sm text-gray-700 focus:outline-none"
                                                    type="file"
                                                    id="image"
                                                    @input="
                                                        formEdit.image =
                                                            $event.target.files[0]
                                                    "
                                                />
                                                <img
                                                    v-if="activite.image"
                                                    alt="activite"
                                                    :src="activite.image_url"
                                                    class="h-auto max-h-12 w-auto max-w-xs object-cover"
                                                />
                                            </div>

                                            <span
                                                v-if="formEdit.errors.image"
                                                class="mt-2 text-xs text-red-500"
                                                >{{
                                                    formEdit.errors.image
                                                }}</span
                                            >
                                        </div>
                                        <div>
                                            <label
                                                for="titre"
                                                class="block text-sm font-medium normal-case text-gray-700"
                                            >
                                                Titre de l'activité
                                            </label>
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    v-model="formEdit.titre"
                                                    type="text"
                                                    name="titre"
                                                    id="titre"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    :placeholder="
                                                        activite.titre
                                                    "
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="formEdit.errors.titre"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ formEdit.errors.titre }}
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium normal-case text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="
                                                        formEdit.description
                                                    "
                                                    id="description"
                                                    rows="2"
                                                    class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    :class="{
                                                        errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                    }"
                                                    :placeholder="
                                                        formEdit.description
                                                    "
                                                ></textarea>
                                            </div>
                                            <div
                                                v-if="
                                                    formEdit.errors.description
                                                "
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    formEdit.errors.description
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                        :disabled="formEdit.processing"
                                        :class="{
                                            'opacity-25': formEdit.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="formEdit.processing"
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
</template>
