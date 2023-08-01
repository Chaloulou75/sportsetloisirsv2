<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import TabsComponent from "@/Components/TabsComponent.vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import ModalDeleteStructure from "@/Components/Modals/ModalDeleteStructure.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    CheckCircleIcon,
    ChevronUpDownIcon,
} from "@heroicons/vue/24/outline";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";

const props = defineProps({
    structure: Object,
    logoUrl: String,
    familles: Object,
    criteres: Object,
    can: Object,
});

const showModal = ref(false);

const uniqueDisciplines = computed(() => {
    const disciplinesMap = new Map();
    props.structure.activites.forEach((activity) => {
        const discipline = activity.discipline;
        if (!disciplinesMap.has(discipline.id)) {
            disciplinesMap.set(discipline.id, discipline);
        }
    });
    return Array.from(disciplinesMap.values());
});

const selectedDiscipline = ref(null);
const selectedCategory = ref(null);
// const selectedCriteres = ref({});

const handleDisciplineClick = (discipline) => {
    selectedDiscipline.value = discipline;
    selectedCategory.value = null;
};

const filteredCategories = computed(() => {
    if (!selectedDiscipline) return [];

    return props.structure.activites
        .filter(
            (activity) =>
                activity.categorie.discipline_id === selectedDiscipline.value.id
        )
        .map((activity) => activity.categorie);
});

const handleCategoryClick = (category) => {
    selectedCategory.value = category;
};

const filteredActivites = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    return props.structure.activites.filter(
        (activity) =>
            activity.categorie.discipline_id === selectedDiscipline.value.id &&
            activity.categorie_id === selectedCategory.value.id
    );
});

const filteredCriteres = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    return props.criteres.filter(
        (critere) =>
            critere.discipline_id === selectedDiscipline.value.id &&
            critere.categorie_id === selectedCategory.value.id
    );
});

const selectedCriteres = computed(() => {
    if (!filteredCriteres.value || filteredCriteres.value.length === 0) {
        return {};
    }
    const initialSelectedCriteres = {};
    for (const critere of filteredCriteres.value) {
        if (critere.valeurs.length > 0) {
            initialSelectedCriteres[critere.id] = critere.valeurs[0];
        } else {
            initialSelectedCriteres[critere.id] = "";
        }
    }
    return initialSelectedCriteres;
});
</script>

<template>
    <Head
        :title="
            structure.name +
            ' - ' +
            structure.adresses[0].city +
            ' - ' +
            structure.structuretype.name
        "
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
    />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-center text-xl font-semibold uppercase leading-tight tracking-widest text-gray-800"
                >
                    {{ structure.name }}
                </h1>
                <nav aria-label="Breadcrumb" class="flex">
                    <ol
                        class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                    >
                        <li class="flex items-center">
                            <Link
                                preserve-scroll
                                :href="route('welcome')"
                                class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
                                    Accueil
                                </span>
                            </Link>
                        </li>

                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                            >
                            </span>

                            <Link
                                preserve-scroll
                                :href="route('structures.show', structure.slug)"
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ structure.name }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex flex-col items-start justify-between md:flex-row">
                <div></div>
                <div
                    v-if="$page.props.auth.user"
                    class="mt-4 w-full md:mt-0 md:w-1/4"
                >
                    <div
                        v-if="can.update"
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
                            "
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Ajouter des activités</Link
                        >
                        <Link
                            :href="route('structures.edit', structure.slug)"
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Editer la structure</Link
                        >
                        <button
                            v-if="can.delete"
                            @click="showModal = true"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-red-400 hover:text-white hover:ring-2 hover:ring-red-400 hover:ring-offset-2 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Supprimer cette structure
                        </button>
                        <ModalDeleteStructure
                            :structure="structure"
                            :show="showModal"
                            @close="showModal = false"
                        />
                    </div>
                </div>
            </div>
        </template>

        <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
            <div
                class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 shadow-lg shadow-sky-700 md:flex-row md:space-x-6"
            >
                <div class="w-full md:w-1/3">
                    <div class="my-4 flex items-center justify-center md:mb-8">
                        <h3 class="text-base font-semibold uppercase">
                            Coordonnées de la structure
                        </h3>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <h3 class="text-base">Localisation</h3>
                        <p class="py-4 font-semibold">
                            {{ structure.adresses[0].city }},
                            {{ structure.adresses[0].zip_code }}
                        </p>
                        <LeafletMap :item="structure" />
                    </div>
                    <div class="my-4 space-y-3">
                        <p class="text-base font-semibold text-gray-700">
                            <UserIcon class="inline-block h-4 w-4" />
                            {{ structure.creator.name }}
                        </p>
                        <p
                            v-if="structure.website"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Site web:
                            <a
                                :href="structure.website"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.website }}
                            </a>
                        </p>
                        <p
                            v-if="structure.facebook"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Facebook:
                            <a
                                :href="structure.facebook"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.facebook }}
                            </a>
                        </p>
                        <p
                            v-if="structure.instagram"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Instagram:
                            <a
                                :href="structure.instagram"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.instagram }}
                            </a>
                        </p>
                        <p
                            v-if="structure.youtube"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
                            Youtube:
                            <a
                                :href="structure.youtube"
                                target="_blank"
                                class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                            >
                                {{ structure.youtube }}
                            </a>
                        </p>

                        <p
                            v-if="structure.phone1"
                            class="text-base font-medium text-gray-700"
                        >
                            <PhoneIcon class="inline-block h-4 w-4" />
                            {{ structure.phone1 }}
                        </p>
                        <p
                            v-if="structure.email"
                            class="text-base font-medium text-gray-700"
                        >
                            <AtSymbolIcon class="inline-block h-4 w-4" />
                            {{ structure.email }}
                        </p>
                    </div>
                </div>
                <div class="w-full space-y-4 md:w-2/3 md:pr-10">
                    <div class="relative mb-4 md:mb-6">
                        <p
                            class="mb-2 text-lg font-medium uppercase tracking-wider text-gray-500"
                        >
                            {{ structure.structuretype.name }}
                        </p>
                        <div
                            class="my-4 flex items-center justify-start space-x-4"
                        >
                            <div v-if="structure.logo">
                                <img
                                    alt="img"
                                    :src="structure.logo"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                            </div>
                            <h2
                                class="inline-block text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ structure.name }}
                            </h2>
                        </div>
                    </div>
                    <div>
                        <p
                            v-if="structure.presentation_longue"
                            class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                        >
                            {{ structure.presentation_longue }}
                        </p>
                        <p
                            v-else
                            class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                        >
                            {{ structure.presentation_courte }}
                        </p>
                    </div>
                    <h3 class="my-4 text-lg font-semibold text-gray-700">
                        Les disciplines proposées:
                    </h3>
                    <div
                        class="grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3 lg:grid-cols-4 lg:gap-6"
                    >
                        <button
                            v-for="discipline in uniqueDisciplines"
                            :key="discipline.id"
                            @click="handleDisciplineClick(discipline)"
                            :class="{
                                'rounded border border-gray-600 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500': true,
                                'border-gray-100 bg-indigo-500 text-white':
                                    selectedDiscipline === discipline,
                            }"
                        >
                            {{ discipline.name }}
                        </button>
                    </div>
                    <h3
                        v-if="selectedDiscipline"
                        class="my-4 text-lg font-semibold text-gray-700"
                    >
                        Les catégories proposées pour
                        <span class="text-indigo-500">{{
                            selectedDiscipline.name
                        }}</span>
                        :
                    </h3>
                    <div
                        v-if="selectedDiscipline"
                        class="grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3 lg:grid-cols-4 lg:gap-6"
                    >
                        <button
                            v-for="categorie in filteredCategories"
                            :key="categorie.id"
                            @click="handleCategoryClick(categorie)"
                            :class="{
                                'rounded border border-gray-600 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500': true,
                                'border-gray-100 bg-indigo-500 text-white':
                                    selectedCategory === categorie,
                            }"
                        >
                            {{ categorie.nom_categorie_client }}
                        </button>
                    </div>
                    <h3
                        v-if="selectedCategory"
                        class="my-4 text-lg font-semibold text-gray-700"
                    >
                        Les activités proposées en
                        <span class="text-indigo-500">{{
                            selectedDiscipline.name
                        }}</span>
                        dans la catégorie
                        <span class="text-indigo-500">{{
                            selectedCategory.nom_categorie_client
                        }}</span
                        >:
                    </h3>

                    <div
                        v-if="selectedCategory"
                        class="my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3"
                    >
                        <Listbox
                            v-for="critere in filteredCriteres"
                            :key="critere.id"
                            class="w-full"
                            v-model="selectedCriteres[critere.id]"
                        >
                            <div class="relative mt-1">
                                <label
                                    :for="critere.nom"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ critere.nom }}:
                                </label>
                                <ListboxButton
                                    class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                >
                                    <span class="block truncate">
                                        {{
                                            selectedCriteres[critere.id].valeur
                                        }}
                                    </span>
                                    <span
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                        <ChevronUpDownIcon
                                            class="h-5 w-5 text-gray-400"
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
                                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <ListboxOption
                                            v-slot="{ active, selected }"
                                            v-for="value in critere.valeurs"
                                            :key="value.id"
                                            :value="value"
                                            as="template"
                                        >
                                            <li
                                                :class="[
                                                    active
                                                        ? 'bg-amber-100 text-amber-900'
                                                        : 'text-gray-900',
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
                                                    >{{ value.valeur }}</span
                                                >
                                                <span
                                                    v-if="selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                >
                                                    <CheckCircleIcon
                                                        class="h-5 w-5"
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

                    <div
                        v-if="selectedCategory"
                        class="my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3"
                    >
                        <ActiviteCard
                            v-for="activite in filteredActivites"
                            :key="activite.id"
                            :activite="activite"
                            :link="route('welcome')"
                        />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
