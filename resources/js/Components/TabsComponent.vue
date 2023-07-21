<script setup>
import { ref } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import { LockOpenIcon, ClockIcon, MapPinIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    structure: Object,
});
const categories = ref({
    Présentation: [
        {
            id: 1,
            resume_court: props.structure.presentation_courte,
            resume_long: props.structure.presentation_longue,
        },
    ],
    Horaires: [
        {
            id: 1,
            weekdays: props.structure.weekdays,
            hour_start: props.structure.hour_start_at,
            hour_end: props.structure.hour_end_at,
        },
    ],
    Lieux: [
        {
            id: 1,
            address: props.structure.address,
            zip_code: props.structure.zip_code,
            city: props.structure.city?.ville ?? props.structure.city,
            country: props.structure.country,
            structure: props.structure,
        },
    ],
});

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>
<template>
    <div class="w-full px-0 my-4 sm:px-2">
        <TabGroup>
            <TabList class="flex p-1 space-x-1 bg-gray-300 rounded-xl">
                <Tab v-for="category in Object.keys(categories)" as="template" :key="category" v-slot="{ selected }">
                    <button :class="[
                            'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-gray-700',
                            'ring-white ring-opacity-90 ring-offset-2 ring-offset-gray-400 focus:outline-none focus:ring-2',
                            selected
                                ? 'bg-white shadow'
                                : 'hover:text-700 text-gray-800 hover:bg-white/[0.12]',
                        ]">
                        {{ category }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="(infos, idx) in Object.values(categories)" :key="idx" :class="[
                        'rounded-xl bg-white p-3',
                        'ring-white ring-opacity-60 ring-offset-2 focus:outline-none focus:ring-2 focus:ring-offset-blue-400',
                    ]">
                    <ul>
                        <li v-for="info in infos" :key="info.id" class="relative px-1 py-3 space-y-2 rounded-md bg-gray-50">
                            <p v-if="info.resume_long"
                                class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line">
                                {{ info.resume_long }}
                            </p>
                            <p v-if="info.resume_court"
                                class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line">
                                {{ info.resume_court }}
                            </p>
                            <h3 v-if="info.address"
                                class="text-base font-medium leading-5 text-gray-700 whitespace-pre-line">
                                <MapPinIcon class="mr-1.5 inline-block h-4 w-4" />{{ info.address }}
                            </h3>
                            <h3 v-if="info.weekdays" class="px-6 text-sm font-medium leading-5 text-gray-700">
                                <LockOpenIcon class="mr-1.5 inline-block h-4 w-4" />
                                Ouvert les:
                                <span v-for="weekday in info.weekdays" :key="weekday.id">
                                    {{ weekday.name }} &bullet;
                                </span>
                            </h3>

                            <ul v-if="info.address"
                                class="flex px-6 mt-1 space-x-1 text-sm font-medium leading-4 text-gray-500">
                                <li>{{ info.zip_code }}</li>
                                <li>&middot;</li>
                                <li>{{ info.city }}</li>
                                <li>&middot;</li>
                                <li>{{ info.country }}</li>
                            </ul>

                            <div v-if="info.address" class="max-w-2xl px-4 mx-auto">
                                <div class="container w-full mx-auto">
                                    <Suspense>
                                        <template #default>
                                            <!-- <GoogleMap :structure="structure" /> -->
                                            <LeafletMap :item="structure" />
                                        </template>

                                        <!-- loading map -->
                                        <template #fallback>
                                            Chargement de la carte...
                                        </template>
                                    </Suspense>
                                </div>
                            </div>
                            <ul v-if="info.hour_start"
                                class="flex mt-1 space-x-1 text-sm font-medium leading-4 text-gray-500">
                                <li>
                                    <ClockIcon class="mr-1.5 inline-block h-4 w-4" />
                                </li>
                                <li>de {{ info.hour_start }}</li>
                                <li>&middot;</li>
                                <li>à {{ info.hour_end }}</li>
                                <li>&middot;</li>
                            </ul>
                        </li>
                    </ul>
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
</template>
