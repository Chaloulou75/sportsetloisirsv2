<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import {
    AdjustmentsHorizontalIcon,
    HomeIcon,
    ListBulletIcon,
    MapIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

const props = defineProps({
    familles: Object,
    category: Object,
    categories: Object,
    categoriesWithoutProduit: Object,
    allStructureTypes: Object,
    criteres: Object,
    departement: Object,
    citiesAround: Object,
    structures: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
});

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const mapStructure = ref(null);
const mapIsVisible = useElementVisibility(mapStructure);
const listeStructure = ref(null);
const listeIsVisible = useElementVisibility(listeStructure);

const goToMap = () => {
    mapStructure.value.scrollIntoView({ behavior: "smooth" });
};

const goToListe = () => {
    listeStructure.value.scrollIntoView({ behavior: "smooth" });
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const hoveredStructure = ref(null);

function showTooltip(structure) {
    hoveredStructure.value = structure.id;
}
function hideTooltip() {
    hoveredStructure.value = null;
}

const showCriteres = ref(false);

const toggleCriteres = () => {
    showCriteres.value = !showCriteres.value;
};

const formCriteres = useForm({
    criteres: ref([]),
});
</script>

<template>
    <Head
        :title="departement.departement"
        :description="`${category.nom_categorie_client} de ${discipline.name} à ${departement.departement}. Choisissez parmi plus de ${departement.structures_count} structures pour pratiquer une activité sportive ou de loisirs à ${departement.departement}`"
    />

    <ResultLayout
        :listDisciplines="listDisciplines"
        :allCities="allCities"
        :discipline="discipline"
        :categories="categories"
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    <h1
                        class="border-b-2 border-slate-400 text-center text-2xl font-bold leading-tight tracking-widest text-gray-800 md:text-4xl"
                    >
                        {{ discipline.name }}
                        <span class="lowercase">{{ departement.prefixe }}</span>
                        {{ departement.departement }}
                    </h1>
                    <h2
                        class="text-center text-lg font-semibold leading-tight tracking-widest text-gray-800 md:text-2xl"
                    >
                        {{ category.nom_categorie_client }}
                    </h2>
                </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
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
                                    :href="
                                        route(
                                            'departements.show',
                                            departement.id
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('departements.disciplines.show', {
                                            departement: departement.id,
                                            discipline: discipline.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ discipline.name }}
                                </Link>
                            </li>
                            <li class="relative hidden items-center md:flex">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'departements.disciplines.categories.show',
                                            {
                                                departement: departement.id,
                                                discipline: discipline.slug,
                                                category: category.id,
                                            }
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ category.nom_categorie_client }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
            <CategoriesResultNavigation
                :category="category"
                :departement="departement"
                :discipline="discipline"
                :allStructureTypes="allStructureTypes"
                :categories="categories"
                :categoriesWithoutProduit="categoriesWithoutProduit"
            />
        </template>
        <!-- Criteres -->
        <div
            class="mt-6 flex w-full items-center justify-between border-b border-gray-300 px-2 md:hidden"
        >
            <h3 class="font-semibold">
                {{ category.nom_categorie_client }}
            </h3>
            <button type="button" @click="toggleCriteres">
                <XMarkIcon v-if="showCriteres" class="h-6 w-6" />
                <AdjustmentsHorizontalIcon v-else class="h-6 w-6" />
            </button>
        </div>

        <div
            v-if="criteres"
            class="mx-auto w-full flex-col items-start justify-center space-x-0 space-y-2 rounded bg-gray-50 px-2 py-6 md:flex md:flex-row md:space-x-6 md:space-y-0 md:px-6"
            :class="{
                flex: showCriteres,
                hidden: !showCriteres,
            }"
        >
            <div v-for="critere in criteres" :key="critere.id" class="max-w-lg">
                <!-- select -->
                <div v-if="critere.type_champ_form === 'select'">
                    <div>
                        <label
                            :for="critere.nom"
                            class="block text-sm font-medium text-gray-700"
                        >
                            {{ critere.nom }}
                        </label>
                        <div class="mt-1 flex rounded-md">
                            <select
                                :name="critere.nom"
                                :id="critere.nom"
                                v-model="formCriteres.criteres[critere.id]"
                                class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                            >
                                <option disabled value="">
                                    Selectionner un
                                    {{ critere.nom }}
                                </option>
                                <option
                                    v-for="(option, index) in critere.valeurs"
                                    :key="option.id"
                                    :value="option.valeur"
                                >
                                    {{ option.valeur }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- checkbox -->
                <div v-if="critere.type_champ_form === 'checkbox'">
                    <div class="block">
                        <span class="text-sm font-medium text-gray-700">{{
                            critere.nom
                        }}</span>
                        <div class="mt-2">
                            <div
                                v-for="(option, index) in critere.valeurs"
                                :key="option.id"
                            >
                                <label
                                    class="inline-flex items-center"
                                    :for="option.valeur"
                                >
                                    <input
                                        v-model="
                                            formCriteres.criteres[critere.id]
                                        "
                                        :id="option.valeur"
                                        :value="option.valeur"
                                        :name="option.valeur"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                    />
                                    <span
                                        class="ml-2 text-sm font-medium text-gray-700"
                                        >{{ option.valeur }}</span
                                    >
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- radio -->
                <div v-if="critere.type_champ_form === 'radio'">
                    <label
                        :for="critere.nom"
                        class="block text-sm font-medium text-gray-700"
                    >
                        {{ critere.nom }}
                    </label>

                    <div class="mt-1 flex rounded-md">
                        <div>
                            <label
                                class="inline-flex items-center"
                                v-for="(option, index) in critere.valeurs"
                                :key="option.id"
                            >
                                <input
                                    v-model="formCriteres.criteres[critere.id]"
                                    type="radio"
                                    class="form-radio"
                                    :name="option.valeur"
                                    :value="option.valeur"
                                    checked
                                />
                                <span class="ml-2">{{ option.valeur }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- input text -->
                <div v-if="critere.type_champ_form === 'text'">
                    <label
                        :for="critere.nom"
                        class="block text-sm font-medium text-gray-700"
                    >
                        {{ critere.nom }}
                    </label>
                    <div class="mt-1 flex rounded-md">
                        <input
                            type="text"
                            v-model="formCriteres.criteres[critere.id]"
                            :name="critere.nom"
                            :id="critere.nom"
                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                            placeholder=""
                            autocomplete="none"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Structures -->
        <template v-if="structures.data.length > 0">
            <div
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div ref="listeStructure" class="md:w-1/2">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                    >
                        <StructureCard
                            v-for="(structure, index) in structures.data"
                            :key="structure.id"
                            :index="index"
                            :structure="structure"
                            @mouseover="showTooltip(structure)"
                            @mouseout="hideTooltip()"
                            :link="
                                route('structures.show', {
                                    structure: structure.slug,
                                })
                            "
                            :data="{
                                departement: departement.id,
                                discipline: discipline.slug,
                                category: category.id,
                            }"
                        />
                    </div>
                    <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div>
                    <button
                        v-if="!mapIsVisible && listeIsVisible"
                        type="button"
                        class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                        @click="goToMap"
                    >
                        <MapIcon class="mr-2 h-5 w-5" />
                        Carte
                    </button>
                </div>
                <div class="space-y-4 md:sticky md:w-1/2">
                    <div ref="mapStructure">
                        <LeafletMapMultiple
                            class="md:top-2"
                            :structures="structures.data"
                            :hovered-structure="hoveredStructure"
                            :zoom="12"
                        />
                        <button
                            v-if="mapIsVisible"
                            type="button"
                            class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                            @click="goToListe"
                        >
                            <ListBulletIcon class="mr-2 h-5 w-5" />
                            Liste
                        </button>
                    </div>
                    <CitiesAround :citiesAround="citiesAround" />
                    <DisciplinesSimilaires
                        :disciplinesSimilaires="disciplinesSimilaires"
                    />
                </div>
            </div>
        </template>
        <template v-else>
            <div
                class="mx-auto min-h-screen max-w-full px-2 py-12 sm:px-6 lg:px-8"
            >
                <p class="font-medium text-gray-700">
                    Dommage, il n'y a pas encore de structures inscrites dans la
                    catégorie
                    <span class="font-semibold text-gray-800">{{
                        category.nom_categorie_client
                    }}</span>
                    en
                    <span class="font-semibold text-gray-800">{{
                        discipline.name
                    }}</span>
                    {{ departement.prefixe }}
                    <span class="font-semibold text-gray-800">{{
                        departement.departement
                    }}</span>
                </p>
            </div>
        </template>
    </ResultLayout>
</template>
