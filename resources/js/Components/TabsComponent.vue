<script setup>
import { ref } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
// import GoogleMap from "@/Components/Google/GoogleMap.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import {
    CheckIcon,
    UserIcon,
    AtSymbolIcon,
    LockOpenIcon,
    GlobeAltIcon,
    ClockIcon,
    PhoneIcon,
    LocationMarkerIcon,
} from "@heroicons/vue/24/solid";
const props = defineProps({
    structure: Object,
});
const categories = ref({
    Présentation: [
        {
            id: 1,
            resume: props.structure.description,
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
            city: props.structure.city,
            country: props.structure.country,
            structure: props.structure,
        },
    ],
});
</script>
<template>
    <div class="my-4 w-full px-0 sm:px-2">
        <TabGroup>
            <TabList class="flex space-x-1 rounded-xl bg-gray-300 p-1">
                <Tab
                    v-for="category in Object.keys(categories)"
                    as="template"
                    :key="category"
                    v-slot="{ selected }"
                >
                    <button
                        :class="[
                            'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-gray-700',
                            'ring-white ring-opacity-90 ring-offset-2 ring-offset-gray-400 focus:outline-none focus:ring-2',
                            selected
                                ? 'bg-white shadow'
                                : 'hover:text-700 text-gray-800 hover:bg-white/[0.12]',
                        ]"
                    >
                        {{ category }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel
                    v-for="(infos, idx) in Object.values(categories)"
                    :key="idx"
                    :class="[
                        'rounded-xl bg-white p-3',
                        'ring-white ring-opacity-60 ring-offset-2 focus:outline-none focus:ring-2 focus:ring-offset-blue-400',
                    ]"
                >
                    <ul>
                        <li
                            v-for="info in infos"
                            :key="info.id"
                            class="relative space-y-2 rounded-md bg-gray-50 px-1 py-3"
                        >
                            <h3
                                v-if="info.resume"
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ info.resume }}
                            </h3>
                            <h3
                                v-if="info.address"
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                <LocationMarkerIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />{{ info.address }}
                            </h3>
                            <h3
                                v-if="info.weekdays"
                                class="text-sm font-medium leading-5 text-gray-700"
                            >
                                <LockOpenIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Ouvert les:
                                <span
                                    v-for="weekday in info.weekdays"
                                    :key="weekday.id"
                                >
                                    {{ weekday.name }} &bullet;
                                </span>
                            </h3>

                            <ul
                                v-if="info.address"
                                class="mt-1 flex space-x-1 text-sm font-medium leading-4 text-gray-500"
                            >
                                <li>{{ info.zip_code }}</li>
                                <li>&middot;</li>
                                <li>{{ info.city }}</li>
                                <li>&middot;</li>
                                <li>{{ info.country }}</li>
                            </ul>

                            <div
                                v-if="info.address"
                                class="mx-auto max-w-2xl py-4"
                            >
                                <div class="container mx-auto w-full">
                                    <Suspense>
                                        <template #default>
                                            <!-- <GoogleMap :structure="structure" /> -->
                                            <LeafletMap
                                                :structure="structure"
                                            />
                                        </template>

                                        <!-- loading map -->
                                        <template #fallback>
                                            Chargement de la carte...
                                        </template>
                                    </Suspense>
                                </div>
                            </div>
                            <ul
                                v-if="info.hour_start"
                                class="mt-1 flex space-x-1 text-sm font-medium leading-4 text-gray-500"
                            >
                                <li>
                                    <ClockIcon
                                        class="mr-1.5 inline-block h-4 w-4"
                                    />
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
