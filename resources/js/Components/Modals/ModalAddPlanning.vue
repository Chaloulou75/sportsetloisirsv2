<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import Dropdown from "primevue/dropdown";
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
    produit: Object,
    show: Boolean,
});

const addPlanningForm = useForm({
    title: null,
    activite: props.produit ? props.produit.activite_id : null,
    produit: props.produit ? props.produit.id : null,
    dates: null,
    horaires: null,
});

const filteredProducts = ref([]);

watch(
    () => addPlanningForm.activite,
    (newVal) => {
        if (newVal) {
            const activite = props.structure.activites.find(
                (act) => act.id === newVal
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

watch(
    () => props.produit,
    (newProduit) => {
        if (newProduit) {
            const activite = props.structure.activites.find(
                (act) => act.id === newProduit.activite_id
            );
            addPlanningForm.title = activite ? activite.titre ?? null : null;
            addPlanningForm.activite = newProduit.activite_id;
            filteredProducts.value = activite ? activite.produits ?? [] : [];
            addPlanningForm.produit = newProduit.id;
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
                emit("showDisplay", "Planning");
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
                <div
                    class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                />
            </TransitionChild>
            <div
                class="fixed inset-0 flex w-screen items-center justify-center p-4 text-center"
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
                        class="w-full max-w-5xl transform space-y-5 overflow-visible rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                    >
                        <DialogTitle
                            as="div"
                            class="flex w-full items-center justify-between"
                        >
                            <h3
                                class="text-lg font-medium leading-6 text-gray-800"
                            >
                                Ajouter un créneau
                                <span
                                    class="text-indigo-700"
                                    v-if="addPlanningForm.title"
                                    >à {{ addPlanningForm.title }}</span
                                >
                            </h3>
                            <button type="button">
                                <XCircleIcon
                                    @click="emit('close')"
                                    class="h-6 w-6 text-gray-600 hover:text-red-600"
                                />
                            </button>
                        </DialogTitle>
                        <div>
                            <form
                                @submit.prevent="onSubmit()"
                                autocomplete="off"
                            >
                                <div class="flex flex-col space-y-3">
                                    <div>
                                        <label
                                            for="titre"
                                            class="mb-1 block text-sm font-medium normal-case text-gray-700"
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
                                    </div>
                                    <label
                                        for="activite"
                                        class="block text-sm font-medium normal-case text-gray-700"
                                    >
                                        Activité liée
                                    </label>
                                    <div
                                        class="mt-1 flex w-full rounded-md md:w-1/2"
                                    >
                                        <Dropdown
                                            v-model="addPlanningForm.activite"
                                            :options="structure.activites"
                                            optionLabel="titre"
                                            optionValue="id"
                                            :placeholder="`Selectionner une activité`"
                                            class="w-full text-sm md:w-[14rem]"
                                            :ptOptions="{
                                                mergeProps: true,
                                            }"
                                            :pt="{ item: 'text-sm' }"
                                            showClear
                                        />
                                    </div>
                                    <div
                                        v-if="
                                            filteredProducts &&
                                            filteredProducts.length > 0
                                        "
                                    >
                                        <label
                                            for="produit"
                                            class="block text-sm font-medium normal-case text-gray-700"
                                        >
                                            Produit lié
                                        </label>
                                        <div
                                            class="mt-1 flex w-full rounded-md md:w-1/2"
                                        >
                                            <Dropdown
                                                v-model="
                                                    addPlanningForm.produit
                                                "
                                                :options="filteredProducts"
                                                optionLabel="id"
                                                optionValue="id"
                                                :placeholder="`Selectionner une produit`"
                                                class="w-full text-sm md:w-[14rem]"
                                                :ptOptions="{
                                                    mergeProps: true,
                                                }"
                                                :pt="{ item: 'text-sm' }"
                                                showClear
                                            />
                                        </div>
                                    </div>
                                    <p v-else class="py-2 text-sm text-red-600">
                                        Il faut au préalable ajouter au moins un
                                        produit à l'activité.
                                    </p>

                                    <!-- Heures x2 ouverture / fermeture -->
                                    <div
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <OpenTimesForm
                                            class="w-full"
                                            v-model="addPlanningForm.horaires"
                                            :name="`Horaires`"
                                        />
                                    </div>
                                    <!-- Dates x 2 -->
                                    <div
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <OpenDaysForm
                                            class="w-full"
                                            v-model="addPlanningForm.dates"
                                            :name="`Dates`"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="mt-4 flex w-full items-center justify-between"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
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
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="addPlanningForm.processing"
                                        />
                                        Enregistrer
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
