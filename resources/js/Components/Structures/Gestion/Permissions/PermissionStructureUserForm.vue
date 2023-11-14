<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    structure: Object,
});
const isShowing = ref(true);
const niveaux = ref([
    { id: 1, fonction: "Super administrateur" },
    { id: 2, fonction: "Administrateur" },
    { id: 3, fonction: "Sans permission" },
]);

const addPermissionForm = useForm({
    structure_id: props.structure.id,
    email: null,
    contact: null,
    phone: null,
    niveau: niveaux.value[1].id,
    activites: {},
    remember: true,
});

const results = ref();

const onPermissionSubmit = () => {
    addPermissionForm.post(
        route("structures.partenaires.store", {
            structure: props.structure.slug,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                addPermissionForm.reset();
            },
        }
    );
};
</script>
<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <form
            @submit.prevent="onPermissionSubmit"
            autocomplete="off"
            class="space-y-4"
        >
            <p class="text-base font-semibold">
                Vous avez la possibilité d'ajouter un administrateur pour votre
                structure, celui-ci doit préalablement être
                <Link :href="route('register')" class="text-indigo-700"
                    >inscrit</Link
                >
                sur sports-et-loisirs.fr.
            </p>
            <p class="text-base font-semibold">
                <span class="font-semibold"
                    >Choisissez les droits pour chaque administrateur</span
                >: création, mise à jour de vos disciplines, activités et
                produits, ou seulement pour certains d'entre eux.
            </p>
            <ul class="list-inside list-disc text-base">
                <li>
                    <span class="font-semibold">Super Administrateur</span> a
                    accès à tous les droits d'administration et de gestion sur
                    la structure et ses activités. Il est unique par structure.
                </li>
                <li>
                    <span class="font-semibold">Administrateur</span> a accès à
                    certains droits d'administration et de gestion, notamment
                    sur les activités de la structure.
                </li>
                <li>
                    <span class="font-semibold">Sans permission</span> n'a accès
                    à aucun droit d'administration et de gestion sur la
                    structure ou ses activités, mais accès aux informations de
                    celle-ci.
                </li>
            </ul>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- email -->
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Email *
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input
                            v-model="addPermissionForm.email"
                            type="email"
                            name="email"
                            id="email"
                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder=""
                            autocomplete="none"
                        />
                    </div>
                    <div
                        v-if="addPermissionForm.errors.email"
                        class="mt-2 text-xs text-red-500"
                    >
                        {{ addPermissionForm.errors.email }}
                    </div>
                </div>
                <!-- contact -->
                <div>
                    <label
                        for="contact"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Nom du partenaire*
                    </label>
                    <div class="mt-1 flex rounded-md">
                        <input
                            v-model="addPermissionForm.contact"
                            type="text"
                            name="contact"
                            id="contact"
                            :class="{
                                'border-red-400':
                                    addPermissionForm.errors.contact,
                            }"
                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                            placeholder=""
                            autocomplete="none"
                        />
                    </div>
                    <div
                        v-if="addPermissionForm.errors.contact"
                        class="mt-2 text-sm text-red-500"
                    >
                        {{ addPermissionForm.errors.contact }}
                    </div>
                </div>
                <!-- niveau -->
                <div>
                    <label
                        for="niveau"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Fonction*
                    </label>
                    <div class="mt-1 flex rounded-md">
                        <select
                            name="niveau"
                            id="niveau"
                            v-model="addPermissionForm.niveau"
                            class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                        >
                            <option
                                v-for="niveau in niveaux"
                                :key="niveau.id"
                                :value="niveau.id"
                            >
                                {{ niveau.fonction }}
                            </option>
                        </select>
                    </div>
                </div>
                <!-- phone -->
                <div>
                    <label
                        for="phone"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Numéro de téléphone *
                    </label>
                    <div class="mt-1 flex w-full">
                        <MazPhoneNumberInput
                            class="w-full"
                            v-model="addPermissionForm.phone"
                            color="primary"
                            size="sm"
                            :only-countries="['FR']"
                            @update="results = $event"
                            :success="results?.isValid"
                            :noSearch="true"
                            :noFlags="true"
                            :noCountrySelector="true"
                            :noExample="true"
                            :translations="{
                                countrySelector: {
                                    placeholder: '',
                                    error: 'Choisir un pays',
                                },
                                phoneInput: {
                                    placeholder: '',
                                    example: '',
                                },
                            }"
                        />
                    </div>
                    <div
                        v-if="addPermissionForm.errors.phone"
                        class="mt-2 text-xs text-red-500"
                    >
                        {{ addPermissionForm.errors.phone }}
                    </div>
                </div>

                <template v-if="addPermissionForm.niveau === 2">
                    <div
                        v-for="activite in structure.activites"
                        :key="activite.id"
                    >
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                :id="activite.id"
                                :value="activite.id"
                                :name="activite.id"
                                v-model="
                                    addPermissionForm.activites[activite.id]
                                "
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />

                            <span class="ml-2 text-sm text-gray-600">{{
                                activite.titre
                            }}</span>
                        </label>
                    </div>
                </template>
                <button
                    type="submit"
                    :disabled="addPermissionForm.processing"
                    class="w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                >
                    <LoadingSVG v-if="addPermissionForm.processing" />
                    Enregistrer
                </button>
            </div>
        </form>
    </TransitionRoot>
</template>
