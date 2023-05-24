<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
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
});

const uniteDurees = [
    { id: 1, name: "Heures" },
    { id: 2, name: "Jours" },
    { id: 3, name: "Mois" },
    { id: 4, name: "Années" },
];
const selectedUniteeDuree = ref(uniteDurees[0]);

const selectedTarifType = ref(props.tarifTypes[0]);

const formAddTarif = useForm({
    structure_id: ref(props.structure.id),
    titre: ref(null),
    description: ref(null),
    tarifType: ref(selectedTarifType.value),
    attributs: ref([]),
    amount: ref(null),
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
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="min-h-full w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <form @submit.prevent="onSubmitAddTarifForm()" autocomplete="off">
                                    <DialogTitle as="div" class="flex w-full items-center justify-between">
                                        <h3 class="text-lg font-medium leading-6 text-gray-800">
                                            Ajouter un tarif à
                                            <span class="text-blue-700">
                                                {{ structure.name }}</span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon @click="emit('close')"
                                                class="h-6 w-6 text-gray-600 hover:text-gray-800" />
                                        </button>
                                    </DialogTitle>
                                    <div class="mt-2 w-full">
                                        <div class="flex flex-col space-y-3">
                                            <div class="flex w-full items-center justify-start space-x-2">
                                                <!-- titre -->
                                                <div class="w-1/2">
                                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                                        Titre
                                                    </label>
                                                    <div class="mt-1 flex rounded-md">
                                                        <input v-model="formAddTarif.titre
                                                            " type="text" name="titre" id="titre"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder="" autocomplete="none" />
                                                    </div>
                                                    <div v-if="errors.titre" class="mt-2 text-xs text-red-500">
                                                        {{ errors.titre }}
                                                    </div>
                                                </div>
                                                <Listbox v-model="selectedTarifType">
                                                    <div class="relative mt-1 w-1/2">
                                                        <label for="tarifType"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Type de tarif *
                                                        </label>
                                                        <ListboxButton
                                                            class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                                                            <span class="block truncate">{{
                                                                selectedTarifType.type
                                                            }}</span>
                                                            <span
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true" />
                                                            </span>
                                                        </ListboxButton>

                                                        <transition leave-active-class="transition duration-100 ease-in"
                                                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                            <ListboxOptions
                                                                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                                                <ListboxOption v-slot="{
                                                                    active,
                                                                    selected,
                                                                }" v-for="tarifType in props.tarifTypes" :key="tarifType.id
    " :value="tarifType
        " as="template">
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
                                                                        ]">{{
    tarifType.type
}}</span>
                                                                        <span v-if="selected
                                                                                "
                                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">
                                                                            <CheckCircleIcon class="h-5 w-5"
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
                                                        class="mt-1 block h-32 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        :class="{
                                                            errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                        }" placeholder="" autocomplete="none" />
                                                </div>
                                                <div v-if="errors.description" class="mt-2 text-xs text-red-500">
                                                    {{ errors.description }}
                                                </div>
                                            </div>
                                            <div v-if="selectedTarifType"
                                                class="flex w-full items-center justify-start space-x-2">
                                                <div v-for="attribut in selectedTarifType.tariftypeattributs"
                                                    :key="attribut.id" class="w-1/2">
                                                    <label :for="attribut.attribut"
                                                        class="block text-sm font-medium text-gray-700">
                                                        {{ attribut.attribut }}
                                                    </label>
                                                    <div class="mt-1 flex rounded-md">
                                                        <input v-model="formAddTarif
                                                            .attributs[
                                                            attribut.id
                                                        ]
                                                            " type="text" :name="attribut.attribut
        " :id="attribut.attribut
        " class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder="" autocomplete="none" />
                                                    </div>
                                                    <!-- <div
                                                        v-if="errors.titre"
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{ errors.titre }}
                                                    </div> -->
                                                </div>
                                            </div>
                                            <!-- Amount -->
                                            <div class="w-1/2">
                                                <label for="amount" class="block text-sm font-medium text-gray-700">
                                                    Montant
                                                </label>
                                                <div class="mt-1 flex rounded-md items-center">
                                                    <input v-model="formAddTarif.amount
                                                        " type="text" name="amount" id="amount"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder="" autocomplete="none" />
                                                    <CurrencyEuroIcon class="h-6 w-6 ml-2" />
                                                </div>
                                                <div v-if="errors.amount" class="mt-2 text-xs text-red-500">
                                                    {{ errors.amount }}
                                                </div>
                                            </div>
                                            <div>
                                                <div>
                                                    Ce tarif est valable pour:
                                                </div>

                                                <div v-for="activite in props
                                                    .structure.activites" :key="activite.id">
                                                    {{ activite.discipline.name }}
                                                    {{ activite.categorie.nom_categorie }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex w-full items-center justify-between">
                                        <button type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')">
                                            Annuler
                                        </button>
                                        <button :disabled="formAddTarif.processing" type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
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
