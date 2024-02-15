<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import NavAdminDisCatParametres from "@/Components/Admin/NavAdminDisCatParametres.vue";
import NavAdminDisCatTarifsForm from "@/Components/Admin/NavAdminDisCatTarifsForm.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import autoAnimate from "@formkit/auto-animate";
import {
    XCircleIcon,
    PlusCircleIcon,
    ArrowPathIcon,
    TrashIcon,
    ChevronUpDownIcon,
    ChevronLeftIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/outline";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categorie: Object,
    categories: Object,
    tarifType: Object,
    user_can: Object,
});

const addBookingFieldFormVisibility = ref([]);
const toggleAddBookingFieldForm = (tarifType) => {
    addBookingFieldFormVisibility.value[tarifType.id] =
        !addBookingFieldFormVisibility.value[tarifType.id];
};
const showAddBookingFieldForm = (tarifType) => {
    return addBookingFieldFormVisibility.value[tarifType.id] || false;
};

const type_champs = [
    { type: "select" },
    { type: "checkbox" },
    { type: "text" },
    { type: "number" },
];

const addBookingFieldForm = useForm({
    nom: null,
    type_champ: type_champs[0],
    remember: true,
});

const addTarifBookingField = (tarifType) => {
    addBookingFieldForm.post(
        route("admin.disciplines.categories.tarifs.bookingfields.store", {
            discipline: props.discipline,
            categorie: props.categorie,
            tarifType: tarifType,
        }),
        {
            errorBag: "addBookingFieldForm",
            preserveScroll: true,
            onSuccess: () => {
                // initializeBookingFieldForm();
                addBookingFieldForm.reset();
                toggleAddBookingFieldForm(tarifType);
            },
        }
    );
};

// const updateBookingFieldForm = ref({});
// const initializeBookingFieldForm = () => {
//     for (const fieldId in props.tarifType.tarif_booking_fields) {
//         const field = props.tarifType.tarif_booking_fields[fieldId];
//         updateBookingFieldForm.value[field.id] = useForm({
//             nom: ref(field.nom),
//             remember: true,
//         });

//         // for (const valeurId in attribut.valeurs) {
//         //     const valeur = attribut.valeurs[valeurId];
//         //     updateValeurForm.value[valeur.id] = useForm({
//         //         valeur: ref(valeur.valeur),
//         //         remember: true,
//         //     });
//         // }
//     }
// };

// const deleteTarifTypeBookingField = (field) => {
//     router.delete(
//         route("admin.disciplines.categories.tarifs.bookingfields.destroy", {
//             discipline: props.discipline,
//             categorie: props.categorie,
//             tarifType: props.tarifType,
//             bookingfield: field,
//         }),
//         {
//             preserveScroll: true,
//             onSuccess: () => {
//                 // initializeBookingFieldForm();
//             },
//         }
//     );
// };

// const updateBookingField = (field) => {
//     router.patch(
//         route("admin.disciplines.categories.tarifs.bookingfields.update", {
//             discipline: props.discipline,
//             categorie: props.categorie,
//             tarifType: props.tarifType,
//             bookingfield: field,
//         }),
//         {
//             nom: updateBookingFieldForm.value[field.id].nom,
//         },
//         {
//             errorBag: "updateBookingFieldForm",
//             preserveScroll: true,
//             onSuccess: () => {
//                 // initializeBookingFieldForm();
//             },
//         }
//     );
// };
</script>
<template>
    <Head
        :title="`Administration de la discipline ${discipline.name}`"
        :description="`Administration de la discipline ${discipline.name}`"
    />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="
                        route('admin.disciplines.categories.edit', discipline)
                    "
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Formulaire de réservation pour
                    <span class="text-indigo-600">{{ tarifType.nom }}</span>
                    de
                    <span class="text-indigo-600"
                        >{{ categorie.nom_categorie_client }} de
                        {{ discipline.name }}</span
                    >
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />
        <NavAdminDisciplineCategorie
            :discipline="discipline"
            :categories="categories"
        />
        <NavAdminDisCatParametres
            :discipline="discipline"
            :categorie="categorie"
        />
        <NavAdminDisCatTarifsForm
            :discipline="discipline"
            :categorie="categorie"
            :tarif-types="categorie.tarif_types"
        />

        <div class="px-2 py-6 space-y-16 md:px-6">
            <div
                class="px-1 py-6 border border-indigo-300 rounded-md shadow-lg bg-gray-50 md:px-3"
            >
                <p
                    class="px-2 text-lg text-center underline text-slate-600 decoration-yellow-400 decoration-4 underline-offset-4"
                >
                    <span class="font-semibold">{{ tarifType.nom }} </span>
                </p>
                <!-- liste BookingFields -->
                <ul
                    v-if="
                        tarifType.tarif_booking_fields &&
                        tarifType.tarif_booking_fields.length > 0
                    "
                    class="py-4 ml-6 space-y-4 text-sm list-disc list-inside text-slate-600 marker:text-indigo-600"
                >
                    <li
                        v-for="field in tarifType.tarif_booking_fields"
                        :key="field.id"
                        class="space-y-3 text-sm text-slate-600"
                    >
                        <!-- <form
                            v-if="field"
                            class="inline-flex flex-col items-start space-y-2 md:flex-row md:items-center md:space-x-3 md:space-y-0"
                            @submit.prevent="updateBookingField(field)"
                        >
                            <div class="flex flex-col">
                                <input
                                    v-if="updateBookingFieldForm[field.id]"
                                    v-model="
                                        updateBookingFieldForm[field.id].nom
                                    "
                                    type="text"
                                    :name="field.nom"
                                    :id="field.nom"
                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <div
                                    v-if="errors.updateBookingFieldForm"
                                    class="mt-1 text-xs text-red-500"
                                >
                                    {{ errors.updateBookingFieldForm.nom }}
                                </div>
                            </div>
                            <div class="text-base">
                                Type de champ:
                                <span class="font-semibold">{{
                                    field.type_champ_form
                                }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="submit">
                                    <ArrowPathIcon
                                        class="w-6 h-6 mr-1 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                    />
                                    <span class="sr-only"
                                        >Mettre à jour le champ</span
                                    >
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex items-center"
                                    @click="deleteTarifTypeBookingField(field)"
                                >
                                    <TrashIcon
                                        class="w-6 h-6 text-red-500 hover:text-red-700"
                                    />
                                </button>
                            </div>
                        </form> -->
                    </li>
                </ul>
                <div>
                    <button
                        v-if="!showAddBookingFieldForm(tarifType)"
                        @click="toggleAddBookingFieldForm(tarifType)"
                        class="inline-flex items-center justify-center px-4 py-3 text-sm font-medium text-center text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                        type="button"
                    >
                        <div>
                            Ajouter un champ au formulaire de réservation de
                            <span class="font-semibold">{{
                                tarifType.nom
                            }}</span>
                        </div>
                    </button>
                    <form
                        v-if="showAddBookingFieldForm(tarifType)"
                        class="inline-flex items-end justify-between flex-grow ml-6 text-xs font-medium text-center text-gray-600"
                        @submit.prevent="addTarifBookingField(tarifType)"
                    >
                        <div class="flex flex-col items-start">
                            <label for="newBookingField"
                                >Ajouter un champ au formulaire de réservation
                                de
                                <span class="font-semibold">{{
                                    tarifType.nom
                                }}</span
                                >:</label
                            >
                            <div class="flex mt-1 rounded-md">
                                <input
                                    v-model="addBookingFieldForm.nom"
                                    type="text"
                                    name="newBookingField"
                                    id="newBookingField"
                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                            </div>
                            <div
                                v-if="errors.addBookingFieldForm"
                                class="text-xs text-red-500"
                            >
                                {{ errors.addBookingFieldForm.nom }}
                            </div>
                            <Listbox
                                class="flex-grow w-full"
                                v-model="addBookingFieldForm.type_champ"
                            >
                                <div class="relative mt-1">
                                    <ListboxButton
                                        class="relative w-full py-2 pl-3 pr-10 mt-1 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                    >
                                        <span class="block truncate">{{
                                            addBookingFieldForm.type_champ.type
                                        }}</span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                                        >
                                            <ChevronUpDownIcon
                                                class="w-5 h-5 text-gray-400"
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
                                            class="absolute z-40 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                        >
                                            <ListboxOption
                                                v-slot="{ active, selected }"
                                                v-for="(
                                                    type_champ, index
                                                ) in type_champs"
                                                :key="index"
                                                :value="type_champ"
                                                as="template"
                                            >
                                                <li
                                                    :class="[
                                                        active
                                                            ? 'bg-amber-100 text-amber-900'
                                                            : 'text-gray-700',
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
                                                            type_champ.type
                                                        }}</span
                                                    >
                                                    <span
                                                        v-if="selected"
                                                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                    >
                                                        <CheckCircleIcon
                                                            class="w-5 h-5"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                </li>
                                            </ListboxOption>
                                        </ListboxOptions>
                                    </transition>
                                </div>
                            </Listbox>
                        </div>
                        <button
                            type="submit"
                            class="inline-flex items-end ml-4"
                        >
                            <PlusCircleIcon
                                class="w-6 h-6 text-indigo-500 hover:text-indigo-700"
                            />
                        </button>
                        <button
                            @click="toggleAddBookingFieldForm(tarifType)"
                            type="button"
                            class="inline-flex items-center ml-4"
                        >
                            <XCircleIcon
                                class="w-6 h-6 text-red-500 hover:text-red-700"
                            />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
