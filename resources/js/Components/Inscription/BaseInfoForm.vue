<script setup>
import { ref } from "vue";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import { GlobeAltIcon } from "@heroicons/vue/24/solid";
import Calendar from "primevue/calendar";

const website = defineModel("website");
const email = defineModel("email");
const date_creation = defineModel("date_creation");
const phone1 = defineModel("phone1");
const phone2 = defineModel("phone2");
const facebook = defineModel("facebook");
const instagram = defineModel("instagram");
const youtube = defineModel("youtube");
const tiktok = defineModel("tiktok");

const props = defineProps({
    errors: Object,
});

const results = ref();
</script>
<template>
    <div class="grid grid-cols-4 gap-6">
        <!-- website -->
        <div class="col-span-4 sm:col-span-3">
            <label
                for="website"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Site web
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-xs text-gray-500"
                >
                    <GlobeAltIcon class="h-6 w-6 text-blue-500" />
                </span>
                <input
                    v-model="website"
                    type="text"
                    name="website"
                    id="website"
                    class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="https://www.exemple.com"
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.website" class="mt-2 text-xs text-red-500">
                {{ errors.website }}
            </div>
        </div>

        <!-- Email -->
        <div class="col-span-4 sm:col-span-3">
            <label
                for="email"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Email *
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <input
                    v-model="email"
                    type="email"
                    name="email"
                    id="email"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="structure@mail.com"
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.email" class="mt-2 text-xs text-red-500">
                {{ errors.email }}
            </div>
        </div>

        <!-- date_creation -->
        <div class="col-span-4 sm:col-span-3">
            <label
                for="date_creation"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                En activité depuis:
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <Calendar
                    v-model="date_creation"
                    showIcon
                    iconDisplay="input"
                    dateFormat="dd/mm/yy"
                    name="date_creation"
                    id="date_creation"
                />
            </div>
            <div v-if="errors.date_creation" class="mt-2 text-xs text-red-500">
                {{ errors.date_creation }}
            </div>
        </div>

        <!-- Phone1 -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="phone1"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Numéro de téléphone *
            </label>
            <div class="mt-1 flex w-full">
                <!-- <input v-model="phone1" type="tel" name="phone1" id="phone1"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="02 10 ..." autocomplete="none" /> -->
                <MazPhoneNumberInput
                    class="w-full"
                    v-model="phone1"
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
                <!-- <code>
                        {{ results }}
                    </code> -->
            </div>
            <div v-if="errors.phone1" class="mt-2 text-xs text-red-500">
                {{ errors.phone1 }}
            </div>
        </div>

        <!-- Phone2 -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="phone2"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Numéro de téléphone de sauvegarde
            </label>
            <div class="mt-1 flex w-full">
                <!-- <input v-model="phone2" type="tel" name="phone2" id="phone2"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="02 10 ..." autocomplete="none" /> -->
                <MazPhoneNumberInput
                    class="w-full"
                    v-model="phone2"
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
            <div v-if="errors.phone2" class="mt-2 text-xs text-red-500">
                {{ errors.phone2 }}
            </div>
        </div>

        <!-- Facebook -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="facebook"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Facebook
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    v-model="facebook"
                    type="text"
                    name="facebook"
                    id="facebook"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.facebook" class="mt-2 text-xs text-red-500">
                {{ errors.facebook }}
            </div>
        </div>

        <!-- Instagram -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="instagram"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Instagram
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    v-model="instagram"
                    type="text"
                    name="instagram"
                    id="instagram"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.instagram" class="mt-2 text-xs text-red-500">
                {{ errors.instagram }}
            </div>
        </div>

        <!-- youtube -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="youtube"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Youtube
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    v-model="youtube"
                    type="text"
                    name="youtube"
                    id="youtube"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.youtube" class="mt-2 text-xs text-red-500">
                {{ errors.youtube }}
            </div>
        </div>

        <!-- tiktok -->
        <div class="col-span-4 sm:col-span-2">
            <label
                for="tiktok"
                class="block text-sm font-medium normal-case text-gray-700"
            >
                Tiktok
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="mt-1 flex rounded-md">
                <input
                    v-model="tiktok"
                    type="text"
                    name="tiktok"
                    id="tiktok"
                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                    placeholder=""
                    autocomplete="none"
                />
            </div>
            <div v-if="errors.tiktok" class="mt-2 text-xs text-red-500">
                {{ errors.tiktok }}
            </div>
        </div>
    </div>
</template>
