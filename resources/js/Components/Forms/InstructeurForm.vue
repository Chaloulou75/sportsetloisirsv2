<script setup>
import { onMounted, ref } from "vue";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import { TransitionRoot } from "@headlessui/vue";

const instructeur_email = defineModel("instructeur_email");
const instructeur_contact = defineModel("instructeur_contact");
const instructeur_phone = defineModel("instructeur_phone");

const props = defineProps({
    errors: Object,
});

const input = ref(null);
const results = ref();
const isShowing = ref(true);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
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
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
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
                        ref="input"
                        v-model="instructeur_email"
                        type="email"
                        name="email"
                        id="email"
                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder=""
                        autocomplete="none"
                    />
                </div>
                <div
                    v-if="errors.instructeur_email"
                    class="mt-2 text-xs text-red-500"
                >
                    {{ errors.instructeur_email }}
                </div>
            </div>
            <!-- contact -->
            <div>
                <label
                    for="contact"
                    class="block text-sm font-medium text-gray-700"
                >
                    Nom de l'instructeur*
                </label>
                <div class="mt-1 flex rounded-md">
                    <input
                        v-model="instructeur_contact"
                        type="text"
                        name="contact"
                        id="contact"
                        :class="{
                            'border-red-400': errors.instructeur_contact,
                        }"
                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                        placeholder=""
                        autocomplete="none"
                    />
                </div>
                <div
                    v-if="errors.instructeur_contact"
                    class="mt-2 text-sm text-red-500"
                >
                    {{ errors.instructeur_contact }}
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
                        v-model="instructeur_phone"
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
                    v-if="errors.instructeur_phone"
                    class="mt-2 text-xs text-red-500"
                >
                    {{ errors.instructeur_phone }}
                </div>
            </div>
        </div>
    </TransitionRoot>
</template>
