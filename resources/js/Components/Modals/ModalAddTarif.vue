<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, reactive, watch } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import {
    XCircleIcon,
    ChevronUpDownIcon,
    CheckCircleIcon,
    CurrencyEuroIcon,
} from "@heroicons/vue/24/outline";
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

const emit = defineEmits("close");

const props = defineProps({
    errors: Object,
    structure: Object,
    show: Boolean,
    tarifTypes: Object,
    activiteForTarifs: Object
});

const uniteDurees = [
    { id: 1, name: "Heures" },
    { id: 2, name: "Jours" },
    { id: 3, name: "Mois" },
    { id: 4, name: "Années" },
];

const checkAll = ref(true);
const selectedUniteeDuree = ref(uniteDurees[0]);
const selectedTarifType = ref(props.tarifTypes[0]);

const formAddTarif = useForm({
    structure_id: ref(props.structure.id),
    titre: ref(null),
    description: ref(null),
    tarifType: ref(selectedTarifType.value),
    attributs: ref([]),
    amount: ref(null),
    disciplines: ref([]),
    categories: ref([]),
    activites: ref([]),
    produits: ref([]),
    uniteDuree: ref(selectedUniteeDuree.value),
});


watch(formAddTarif.disciplines, (newDisciplines) => {
    newDisciplines.forEach((disciplineId) => {
        // Set all related category checkboxes to true
        const categories = formAddTarif.categories.value[disciplineId];
        Object.keys(categories).forEach((categoryId) => {
            categories[categoryId] = true;
        });

        // Set all related activite checkboxes to true
        const activites = formAddTarif.activites.value[disciplineId];
        Object.keys(activites).forEach((activiteId) => {
            activites[activiteId] = true;
        });

        // Set all related produit checkboxes to true
        const produits = formAddTarif.produits.value[disciplineId];
        Object.keys(produits).forEach((produitId) => {
            produits[produitId] = true;
        });
    });
});

const onSubmitAddTarifForm = () => {
    router.post(
        `/structures/${props.structure.slug}/tarifs`,
        {
            structure_id: formAddTarif.structure_id,
            titre: formAddTarif.titre,
            description: formAddTarif.description,
            tarifType: selectedTarifType.value.id,
            attributs: formAddTarif.attributs,
            amount: formAddTarif.amount,
            disciplines: formAddTarif.disciplines,
            categories: formAddTarif.categories,
            activites: formAddTarif.activites,
            produits: formAddTarif.produits,
            uniteDuree: formAddTarif.uniteDuree
        },
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                formAddTarif.reset();
                emit("close");
            },
            structure: props.structure.slug,
        }
    );
};
</script>
<template>
    <div>
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="open = false" class="relative z-10">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                    leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-full p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="w-full max-w-6xl min-h-full p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                                <form @submit.prevent="onSubmitAddTarifForm()" autocomplete="off">
                                    <DialogTitle as="div" class="flex items-center justify-between w-full">
                                        <h3 class="text-lg font-medium leading-6 text-gray-800">
                                            Ajouter un tarif à
                                            <span class="text-blue-700">
                                                {{ structure.name }}</span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon @click="emit('close')"
                                                class="w-6 h-6 text-gray-600 hover:text-gray-800" />
                                        </button>
                                    </DialogTitle>
                                    <div class="w-full mt-2">
                                        <div class="flex flex-col space-y-3">
                                            <div
                                                class="flex flex-col items-center justify-start w-full space-x-0 space-y-2 md:flex-row md:space-y-0 md:space-x-2">
                                                <!-- titre -->
                                                <div class="w-full md:w-1/2">
                                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                                        Titre
                                                    </label>
                                                    <div class="flex mt-1 rounded-md">
                                                        <input v-model="formAddTarif.titre
                                                            " type="text" name="titre" id="titre"
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                            placeholder="" autocomplete="none" />
                                                    </div>
                                                    <div v-if="errors.titre" class="mt-2 text-xs text-red-500">
                                                        {{ errors.titre }}
                                                    </div>
                                                </div>
                                                <Listbox class="w-full md:w-1/2" v-model="selectedTarifType">
                                                    <div class="relative mt-1">
                                                        <label for="tarifType"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Type de tarif *
                                                        </label>
                                                        <ListboxButton
                                                            class="relative w-full py-2 pl-3 pr-10 mt-1 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                                                            <span class="block truncate">{{
                                                                selectedTarifType.type
                                                            }}</span>
                                                            <span
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                                <ChevronUpDownIcon class="w-5 h-5 text-gray-400"
                                                                    aria-hidden="true" />
                                                            </span>
                                                        </ListboxButton>

                                                        <transition leave-active-class="transition duration-100 ease-in"
                                                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                            <ListboxOptions
                                                                class="absolute w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                                                <ListboxOption v-slot="{ active, selected }"
                                                                    v-for="tarifType in props.tarifTypes"
                                                                    :key="tarifType.id" :value="tarifType" as="template">
                                                                    <li :class="[
                                                                        active
                                                                            ? 'bg-amber-100 text-amber-900'
                                                                            : 'text-gray-900',
                                                                        'relative cursor-default select-none py-2 pl-10 pr-4',
                                                                    ]">
                                                                        <span :class="[
                                                                            selected
                                                                                ? 'font-medium'
                                                                                : 'font-normal',
                                                                            'block truncate',
                                                                        ]">{{ tarifType.type }}</span>
                                                                        <span v-if="selected"
                                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">
                                                                            <CheckCircleIcon class="w-5 h-5"
                                                                                aria-hidden="true" />
                                                                        </span>
                                                                    </li>
                                                                </ListboxOption>
                                                            </ListboxOptions>
                                                        </transition>
                                                    </div>
                                                </Listbox>
                                            </div>
                                            <!-- description -->
                                            <div>
                                                <label for="description" class="block text-sm font-medium text-gray-700">
                                                    Description
                                                </label>
                                                <div class="mt-1">
                                                    <textarea v-model="formAddTarif.description
                                                        " id="description" name="description" rows="2"
                                                        class="block w-full h-32 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        :class="{
                                                            errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                        }" placeholder="" autocomplete="none" />
                                                </div>
                                                <div v-if="errors.description" class="mt-2 text-xs text-red-500">
                                                    {{ errors.description }}
                                                </div>
                                            </div>
                                            <div v-if="selectedTarifType"
                                                class="flex flex-col items-center justify-start w-full space-x-0 space-y-2 md:flex-row md:space-y-0 md:space-x-2">
                                                <div v-for="attribut in selectedTarifType.tariftypeattributs"
                                                    :key="attribut.id" class="flex items-center w-1/2 space-x-2 md:w-auto">
                                                    <div>
                                                        <label :for="attribut.attribut"
                                                            class="block text-sm font-medium text-gray-700">
                                                            {{ attribut.attribut }}
                                                        </label>
                                                        <div class="flex mt-1 rounded-md">
                                                            <input v-model="formAddTarif.attributs[attribut.id]" type="text"
                                                                :name="attribut.attribut" :id="attribut.attribut"
                                                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                                placeholder="" autocomplete="none" />
                                                        </div>
                                                    </div>


                                                    <Listbox v-if="attribut.attribut === 'Duree'"
                                                        v-model="selectedUniteeDuree" class="w-56">
                                                        <div class="relative">
                                                            <label for="unite de duree"
                                                                class="block text-sm font-medium text-gray-700">
                                                                Unité de durée
                                                            </label>
                                                            <ListboxButton
                                                                class="relative w-full py-2 pl-3 pr-10 mt-1 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                                                                <span class="block truncate">
                                                                    {{ selectedUniteeDuree }}
                                                                </span>
                                                                <span
                                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                                    <ChevronUpDownIcon class="w-5 h-5 text-gray-400"
                                                                        aria-hidden="true" />
                                                                </span>
                                                            </ListboxButton>

                                                            <transition leave-active-class="transition duration-100 ease-in"
                                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                                <ListboxOptions
                                                                    class="absolute w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                                                    <ListboxOption v-slot="{ active, selected }"
                                                                        v-for="unite in uniteDurees" :key="unite.id"
                                                                        :value="unite.name" as="template">
                                                                        <li :class="[
                                                                            active
                                                                                ? 'bg-amber-100 text-amber-900'
                                                                                : 'text-gray-900',
                                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                                        ]">
                                                                            <span :class="[
                                                                                selected
                                                                                    ? 'font-medium'
                                                                                    : 'font-normal',
                                                                                'block truncate',
                                                                            ]">{{ unite.name }}</span>
                                                                            <span v-if="selected"
                                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">
                                                                                <CheckCircleIcon class="w-5 h-5"
                                                                                    aria-hidden="true" />
                                                                            </span>
                                                                        </li>
                                                                    </ListboxOption>
                                                                </ListboxOptions>
                                                            </transition>
                                                        </div>
                                                    </Listbox>
                                                    <!-- <div
                                                        v-if="errors.titre"
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{ errors.titre }}
                                                    </div> -->
                                                </div>
                                            </div>
                                            <!-- Amount -->
                                            <div class="w-full md:w-1/2">
                                                <label for="amount" class="block text-sm font-medium text-gray-700">
                                                    Montant
                                                </label>
                                                <div class="flex items-center mt-1 rounded-md">
                                                    <input v-model="formAddTarif.amount
                                                        " type="text" name="amount" id="amount"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder="" autocomplete="none" />
                                                    <CurrencyEuroIcon class="w-6 h-6 ml-2" />
                                                </div>
                                                <div v-if="errors.amount" class="mt-2 text-xs text-red-500">
                                                    {{ errors.amount }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                <p class="text-sm font-medium text-gray-700">
                                                    Ce tarif est valable pour:
                                                </p>

                                                <label class="flex items-center">
                                                    <Checkbox name="Tout" v-model="checkAll" />
                                                    <span class="ml-2 text-sm text-gray-600">Tout sélectionner</span>
                                                </label>

                                                <div v-for="discipline in props.activiteForTarifs" :key="discipline.id"
                                                    class="ml-2 md:ml-4">
                                                    <label class="flex items-center">
                                                        <Checkbox :name="discipline.disciplineName"
                                                            v-model="formAddTarif.disciplines[discipline.id]" />
                                                        <span class="ml-2 text-sm text-gray-600">{{
                                                            discipline.disciplineName
                                                        }}</span>
                                                    </label>
                                                    <div v-for="category in discipline.categories" :key="category.id"
                                                        class="ml-4 md:ml-8">
                                                        <label class="flex items-center">
                                                            <Checkbox :name="category.name"
                                                                v-model="formAddTarif.categories[category.id]" />
                                                            <span class="ml-2 text-sm text-gray-600">{{
                                                                category.name
                                                            }}</span>
                                                        </label>

                                                        <div v-for="activite in category.activites" :key="activite.id"
                                                            class="ml-6 md:ml-12">
                                                            <label class="flex items-center">
                                                                <Checkbox :name="activite.titre"
                                                                    v-model="formAddTarif.activites[activite.id]" />
                                                                <span class="ml-2 text-sm text-gray-600">{{ activite.titre
                                                                }}</span>
                                                            </label>

                                                            <div
                                                                class="flex flex-col items-center ml-8 space-x-0 space-y-3 md:flex-row md:space-x-8 md:space-y-0 md:ml-16">
                                                                <label v-for="produit in activite.produits"
                                                                    :key="produit.id" class="flex items-center">
                                                                    <Checkbox :name="produit.id"
                                                                        v-model="formAddTarif.produits[produit.id]" />
                                                                    <span class="ml-2 text-sm text-gray-600">Produit n° {{
                                                                        produit.id }}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between w-full mt-4">
                                        <button type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')">
                                            Annuler
                                        </button>
                                        <button :disabled="formAddTarif.processing" type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
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
    </div>
</template>
