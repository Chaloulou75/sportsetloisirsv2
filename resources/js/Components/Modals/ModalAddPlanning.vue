<script setup>
import { ref, watch, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import OpenDaysForm from "@/Components/Forms/DayTime/OpenDaysForm.vue";
import OpenTimesForm from "@/Components/Forms/DayTime/OpenTimesForm.vue";
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

const emit = defineEmits(["close", "showDisplay"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    structureActivites: Object,
    show: Boolean,
});

const addPlanningForm = useForm({
    title: null,
    activite: null,
    produit: null,
    dates: null,
    horaires: null,
});

const filteredProducts = ref([]);

watch(
    () => addPlanningForm.activite,
    (newVal) => {
        if (newVal) {
            const activite = props.structureActivites.find(
                (activite) => activite.id === newVal
            );
            filteredProducts.value = activite ? activite.produits ?? [] : [];
            addPlanningForm.title = activite ? activite.titre ?? null : null;
            addPlanningForm.produit = activite
                ? activite.produits[0].id ?? null
                : null;
        } else {
            filteredProducts.value = null;
        }
    }
);

const onSubmit = () => {
    addPlanningForm.post(
        route("structures.plannings.multiples.store", {
            structure: props.structure,
        }),
        {
            errorBag: "addPlanningForm",
            preserveScroll: true,
            onSuccess: () => {
                addPlanningForm.reset();
                emit("close");
                emit("showDisplay");
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
                            class="w-full max-w-4xl p-6 space-y-10 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl minh-full rounded-2xl"
                        >
                            <DialogTitle
                                as="div"
                                class="flex items-center justify-between w-full"
                            >
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-800"
                                >
                                    Ajouter un créneau
                                </h3>
                                <button type="button">
                                    <XCircleIcon
                                        @click="emit('close')"
                                        class="w-6 h-6 text-gray-600 hover:text-red-600"
                                    />
                                </button>
                            </DialogTitle>

                            <form
                                @submit.prevent="onSubmit()"
                                autocomplete="off"
                            >
                                <div class="flex flex-col space-y-3">
                                    <div>
                                        <label
                                            for="titre"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                            >Titre</label
                                        >
                                        <TextInput
                                            class="max-w-sm"
                                            type="text"
                                            name="titre"
                                            id="titre"
                                            v-model="addPlanningForm.title"
                                            :placeholder="
                                                addPlanningForm.title
                                                    ? addPlanningForm.title
                                                    : 'Titre'
                                            "
                                        />

                                        <!-- <div class="my-2">
                                            <p class="text-sm text-gray-500">
                                                Exemple:
                                                <span class="font-semibold">
                                                    {{
                                                        addPlanningForm.title
                                                    }}</span
                                                >.
                                            </p>
                                        </div> -->
                                    </div>
                                    <div>
                                        <label
                                            for="hs-select-label"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                            >Activité liée</label
                                        >
                                        <select
                                            v-model="addPlanningForm.activite"
                                            id="hs-select-label"
                                            class="block w-full max-w-sm px-4 py-3 text-sm border-gray-200 rounded-lg pe-9 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                                        >
                                            <option disabled>
                                                Sélectionner une activité
                                            </option>
                                            <option
                                                v-for="activite in structureActivites"
                                                :key="activite.id"
                                                :value="activite.id"
                                            >
                                                {{ activite.titre }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            v-if="
                                                filteredProducts &&
                                                filteredProducts.length > 0
                                            "
                                            for="hs-select-label"
                                            class="block mb-1 text-sm font-medium text-gray-700"
                                            >Produit lié</label
                                        >
                                        <select
                                            v-if="
                                                filteredProducts &&
                                                filteredProducts.length > 0
                                            "
                                            v-model="addPlanningForm.produit"
                                            id="hs-select-label"
                                            class="block w-full max-w-sm px-4 py-3 text-sm border-gray-200 rounded-lg pe-9 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                                        >
                                            <option disabled>
                                                Sélectionner un produit
                                            </option>
                                            <option
                                                v-for="produit in filteredProducts"
                                                :key="produit.id"
                                                :value="produit.id"
                                            >
                                                {{ produit.id }}
                                            </option>
                                        </select>

                                        <p
                                            class="py-2 text-sm text-red-600"
                                            v-if="
                                                filteredProducts &&
                                                !filteredProducts.length > 0
                                            "
                                        >
                                            Il faut au préalable ajouter au
                                            moins un produit à l'activité.
                                        </p>
                                    </div>

                                    <!-- Heures x2 ouverture / fermeture -->
                                    <div
                                        class="flex flex-col items-start max-w-sm space-y-3"
                                    >
                                        <OpenTimesForm
                                            class="w-full"
                                            v-model="addPlanningForm.horaires"
                                            :name="`Horaires`"
                                        />
                                    </div>
                                    <!-- Dates x 2 -->
                                    <div
                                        class="flex flex-col items-start max-w-sm space-y-3"
                                    >
                                        <OpenDaysForm
                                            class="w-full"
                                            v-model="addPlanningForm.dates"
                                            :name="`Dates`"
                                        />
                                    </div>
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
                                        :disabled="addPlanningForm.processing"
                                        :class="{
                                            'opacity-25':
                                                addPlanningForm.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="addPlanningForm.processing"
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
