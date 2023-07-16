<script setup>
import { ref, watch } from "vue";
import MazPhoneNumberInput from 'maz-ui/components/MazPhoneNumberInput'
import { GlobeAltIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    errors: Object,
    website: String,
    email: String,
    date_creation: String,
    phone1: String,
    phone2: String,
    facebook: String,
    instagram: String,
    youtube: String,
    tiktok: String,
});

const emit = defineEmits([
    'update:website',
    'update:email',
    'update:date_creation',
    'update:phone1',
    'update:phone2',
    'update:facebook',
    'update:instagram',
    'update:youtube',
    'update:tiktok',
]);

const results = ref();

const website = ref(props.website);
const email = ref(props.email);
const date_creation = ref(props.date_creation);
const phone1 = ref(props.phone1);
const phone2 = ref(props.phone2);
const facebook = ref(props.facebook);
const instagram = ref(props.instagram);
const youtube = ref(props.youtube);
const tiktok = ref(props.tiktok);

watch(website, (value) => {
    emit('update:website', value);
});

watch(email, (value) => {
    emit('update:email', value);
});

watch(date_creation, (value) => {
    emit('update:date_creation', value);
});

watch(phone1, (value) => {
    emit('update:phone1', value);
});

watch(phone2, (value) => {
    emit('update:phone2', value);
});

watch(facebook, (value) => {
    emit('update:facebook', value);
});

watch(instagram, (value) => {
    emit('update:instagram', value);
});

watch(youtube, (value) => {
    emit('update:youtube', value);
});

watch(tiktok, (value) => {
    emit('update:tiktok', tiktok);
});
</script>
<template>
    <div class="grid grid-cols-4 gap-6">
        <!-- website -->
        <div class="col-span-4 sm:col-span-3">
            <label for="website" class="block text-sm font-medium text-gray-700">
                Site web
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="flex mt-1 rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 text-xs text-gray-500 border border-r-0 border-gray-300 rounded-l-md bg-gray-50">
                    <GlobeAltIcon class="w-6 h-6 text-blue-500" />
                </span>
                <input v-model="website" type="text" name="website" id="website"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-none rounded-r-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="https://www.exemple.com" autocomplete="none" />
            </div>
            <div v-if="errors.website" class="mt-2 text-xs text-red-500">
                {{ errors.website }}
            </div>
        </div>

        <!-- Email -->
        <div class="col-span-4 sm:col-span-3">
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email *
            </label>
            <div class="flex mt-1 rounded-md shadow-sm">
                <input v-model="email" type="email" name="email" id="email"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="structure@mail.com" autocomplete="none" />
            </div>
            <div v-if="errors.email" class="mt-2 text-xs text-red-500">
                {{ errors.email }}
            </div>
        </div>

        <!-- date_creation -->
        <div class="col-span-4 sm:col-span-3">
            <label for="date_creation" class="block text-sm font-medium text-gray-700">
                En activité depuis:
            </label>
            <div class="flex mt-1 rounded-md shadow-sm">
                <input type="date" v-model="date_creation" name="date_creation" id="date_creation"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md form-input focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="" autocomplete="none" />
            </div>
            <div v-if="errors.date_creation" class="mt-2 text-xs text-red-500">
                {{ errors.date_creation }}
            </div>
        </div>

        <!-- Phone1 -->
        <div class="col-span-4 sm:col-span-2">
            <label for="phone1" class="block text-sm font-medium text-gray-700">
                Numéro de téléphone *
            </label>
            <div class="flex mt-1 w-full">
                <!-- <input v-model="phone1" type="tel" name="phone1" id="phone1"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="02 10 ..." autocomplete="none" /> -->
                    <MazPhoneNumberInput
                        v-model="phone1"
                        show-code-on-list
                        color="info"
                        :only-countries="['FR', 'BE', 'LU']"
                        @update="results = $event"
                        :success="results?.isValid"
                        :noSearch="true"
                        :translations="{
                            countrySelector: {
                                placeholder: 'code pays',
                                error: 'Choisir un pays',
                            },
                            phoneInput: {
                                placeholder: 'Télephone',
                                example: 'Exemple:',
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
            <label for="phone2" class="block text-sm font-medium text-gray-700">
                Numéro de téléphone de sauvegarde
            </label>
            <div class="flex mt-1 w-full">
                <!-- <input v-model="phone2" type="tel" name="phone2" id="phone2"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="02 10 ..." autocomplete="none" /> -->
                <MazPhoneNumberInput
                        v-model="phone2"
                        show-code-on-list
                        color="info"
                        :only-countries="['FR', 'BE', 'LU']"
                        @update="results = $event"
                        :success="results?.isValid"
                        :noSearch="true"
                        :translations="{
                            countrySelector: {
                                placeholder: 'code pays',
                                error: 'Choisir un pays',
                            },
                            phoneInput: {
                                placeholder: 'Télephone',
                                example: 'Exemple:',
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
            <label for="facebook" class="block text-sm font-medium text-gray-700">
                Facebook
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="flex mt-1 rounded-md">
                <input v-model="facebook" type="text" name="facebook" id="facebook"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                    placeholder="" autocomplete="none" />
            </div>
            <div v-if="errors.facebook" class="mt-2 text-xs text-red-500">
                {{ errors.facebook }}
            </div>
        </div>

        <!-- Instagram -->
        <div class="col-span-4 sm:col-span-2">
            <label for="instagram" class="block text-sm font-medium text-gray-700">
                Instagram
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="flex mt-1 rounded-md">
                <input v-model="instagram" type="text" name="instagram" id="instagram"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                    placeholder="" autocomplete="none" />
            </div>
            <div v-if="errors.instagram" class="mt-2 text-xs text-red-500">
                {{ errors.instagram }}
            </div>
        </div>

        <!-- youtube -->
        <div class="col-span-4 sm:col-span-2">
            <label for="youtube" class="block text-sm font-medium text-gray-700">
                Youtube
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="flex mt-1 rounded-md">
                <input v-model="youtube" type="text" name="youtube" id="youtube"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                    placeholder="" autocomplete="none" />
            </div>
            <div v-if="errors.youtube" class="mt-2 text-xs text-red-500">
                {{ errors.youtube }}
            </div>
        </div>

        <!-- tiktok -->
        <div class="col-span-4 sm:col-span-2">
            <label for="tiktok" class="block text-sm font-medium text-gray-700">
                Tiktok
                <span class="text-xs italic">(url complète)</span>
            </label>
            <div class="flex mt-1 rounded-md">
                <input v-model="tiktok" type="text" name="tiktok" id="tiktok"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                    placeholder="" autocomplete="none" />
            </div>
            <div v-if="errors.tiktok" class="mt-2 text-xs text-red-500">
                {{ errors.tiktok }}
            </div>
        </div>
    </div>
</template>
