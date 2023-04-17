<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
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
    activite: Object,
    can: Object,
});

const form = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(props.activite.discipline_id),
    activitetype_id: ref(props.activite.activitetype_id),
    nivel_id: ref(props.activite.nivel_id),
    name: ref(props.activite.name),
    address: ref(props.activite.address),
    city: ref(props.activite.city),
    zip_code: ref(props.activite.zip_code),
    country: ref(props.activite.country),
    address_lat: ref(props.activite.address_lat),
    address_lng: ref(props.activite.address_lng),
    description: ref(props.activite.description),
    publictype_id: ref(props.activite.publictype_id),
});

function submit() {
    // const structureValue = props.structure.value;
    const url = `/structures/${props.structure.slug}/activites/${props.activite.slug}`;
    form.patch(
        url,
        {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        },
        { structure: props.structure, activite: props.activite }
    );
}
</script>

<template>
    <Head title="Modifier une activité" />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Modifier l'activité
                        <span class="text-blue-700">{{ activite.name }}</span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                    </div>
                </div>
            </div>
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
                                    Modifier votre activité
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
                                        class="space-y-6 bg-white px-4 py-5 sm:p-6"
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
                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
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
                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
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
                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
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
                                                <span class="text-xs italic"
                                                    >(Attention, en changeant le
                                                    nom, vous changez aussi le
                                                    lien d'accès à la page de
                                                    votre activité)</span
                                                >
                                            </label>
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    ref="name"
                                                    v-model="form.name"
                                                    type="text"
                                                    name="name"
                                                    id="name"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                                    class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                        class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                    >
                                        <button
                                            :disabled="form.processing"
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Mettre à jour
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
