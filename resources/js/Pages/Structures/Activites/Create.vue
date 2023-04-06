<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const AutocompleteActiviteForm = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteForm.vue")
);

const props = defineProps({
    disciplines: Object,
    niveaux: Object,
    publictypes: Object,
    activitestypes: Object,
    structure: Object,
});

const form = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(null),
    activitetype_id: ref(null),
    nivel_id: ref(null),
    name: ref(null),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    description: ref(null),
    publictype_id: ref(null),
});

function submit() {
    // const structureValue = props.structure.value;
    const url = `/structures/${props.structure.slug}/activites`;
    console.log(url);
    form.post(
        url,
        {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        },
        props.structure
    );
}
</script>

<template>
    <Head title="Ajouter une activité" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ajouter une activité à {{ props.structure.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-700"
                                >
                                    Ajouter des activités
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Ces informations apparaitront publiquement
                                    sur ce site.
                                </p>
                            </div>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <form
                                @submit.prevent="submit"
                                enctype="multipart/form-data"
                                autocomplete="off"
                            >
                                <div
                                    class="shadow-lg shadow-sky-700 sm:overflow-hidden sm:rounded-md"
                                >
                                    <div
                                        class="px-4 py-5 space-y-6 bg-white sm:p-6"
                                    >
                                        <!-- <button
                                                type="button"
                                                @click="addActivite"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            >
                                                Ajouter une activité
                                            </button> -->
                                        <!-- <div
                                            v-for="(
                                                activite, index
                                            ) in form.activites"
                                            :key="index"
                                            class="grid grid-cols-3 gap-6"
                                        > -->
                                        <!-- discipline -->
                                        <div class="col-span-3 sm:col-span-2">
                                            <AutocompleteActiviteForm
                                                :disciplines="disciplines"
                                                :errors="form.errors"
                                                v-model:discipline="
                                                    form.discipline_id
                                                "
                                                @update:modelValue="
                                                    (discipline) =>
                                                        (form.discipline_id =
                                                            discipline)
                                                "
                                            />
                                        </div>

                                        <!-- activitetypes_id -->
                                        <div class="col-span-3 sm:col-span-2">
                                            <label
                                                for="activitetype_id"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Type d'activité
                                            </label>
                                            <div class="mt-1">
                                                <select
                                                    name="activitetype_id"
                                                    id="activitetype_id"
                                                    v-model="
                                                        form.activitetype_id
                                                    "
                                                    class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
                                                >
                                                    <option
                                                        v-for="activitetype in activitestypes"
                                                        :key="activitetype.id"
                                                        :value="activitetype.id"
                                                    >
                                                        {{ activitetype.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div
                                                v-if="
                                                    form.errors.activitetype_id
                                                "
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    form.errors.activitetype_id
                                                }}
                                            </div>
                                        </div>

                                        <!-- nivel_id -->
                                        <div class="col-span-3 sm:col-span-2">
                                            <label
                                                for="nivel_id"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Niveaux
                                            </label>
                                            <div class="mt-1">
                                                <select
                                                    name="nivel_id"
                                                    id="nivel_id"
                                                    v-model="form.nivel_id"
                                                    class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
                                                >
                                                    <option
                                                        v-for="nivel in niveaux"
                                                        :key="nivel.id"
                                                        :value="nivel.id"
                                                    >
                                                        {{ nivel.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div
                                                v-if="form.errors.nivel_id"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ form.errors.nivel_id }}
                                            </div>
                                        </div>

                                        <!-- publictype_id -->
                                        <div class="col-span-3 sm:col-span-2">
                                            <label
                                                for="publictype_id"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Public
                                            </label>
                                            <div class="mt-1">
                                                <select
                                                    name="publictype_id"
                                                    id="publictype_id"
                                                    v-model="form.publictype_id"
                                                    class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
                                                >
                                                    <option
                                                        v-for="publictype in publictypes"
                                                        :key="publictype.id"
                                                        :value="publictype.id"
                                                    >
                                                        {{ publictype.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div
                                                v-if="form.errors.publictype_id"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ form.errors.publictype_id }}
                                            </div>
                                        </div>

                                        <!-- name  -->
                                        <div class="col-span-3 sm:col-span-2">
                                            <label
                                                for="name"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Nom de l'activité
                                            </label>
                                            <div class="flex mt-1 rounded-md">
                                                <input
                                                    ref="name"
                                                    v-model="form.name"
                                                    type="text"
                                                    name="name"
                                                    id="name"
                                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                    placeholder=""
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="form.errors.name"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ form.errors.name }}
                                            </div>
                                        </div>
                                        <!-- Adresse -->
                                        <AddressForm
                                            label="adresse de l'activité"
                                            :errors="form.errors"
                                            v-model:address="form.address"
                                            v-model:city="form.city"
                                            v-model:zip_code="form.zip_code"
                                            v-model:country="form.country"
                                            v-model:address_lat="
                                                form.address_lat
                                            "
                                            v-model:address_lng="
                                                form.address_lng
                                            "
                                        />
                                        <!-- Description -->
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="form.description"
                                                    id="description"
                                                    name="description"
                                                    rows="3"
                                                    class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    :class="{
                                                        errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                    }"
                                                    placeholder="Mettez en valeur votre activité"
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <p
                                                class="mt-2 text-sm text-gray-500"
                                            >
                                                Description de votre activité en
                                                quelques lignes.
                                            </p>
                                            <div
                                                v-if="form.errors.description"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ form.errors.description }}
                                            </div>
                                        </div>
                                    </div>

                                    <!--buttons -->
                                    <div
                                        class="px-4 py-3 text-right bg-gray-50 sm:px-6"
                                    >
                                        <button
                                            :disabled="form.processing"
                                            type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
