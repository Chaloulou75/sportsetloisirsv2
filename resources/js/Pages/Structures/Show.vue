<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import TabsComponent from "@/Components/TabsComponent.vue";
import ModalDeleteStructure from "@/Components/ModalDeleteStructure.vue";

import {
    CheckIcon,
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
} from "@heroicons/vue/24/solid";

let props = defineProps({
    structure: Object,
    can: Object,
});

const showModal = ref(false);
</script>

<template>
    <Head
        :title="
            structure.name +
            ' - ' +
            structure.city +
            ' - ' +
            structure.structuretype.name
        "
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
    />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        {{ structure.name }}
                        <span class="text-xs italic text-gray-600"
                            >({{ structure.view_count }} vues)
                        </span>
                    </h2>
                    <div
                        class="py-2 text-base font-medium leading-relaxed text-gray-600"
                    >
                        <p>
                            Situé à
                            <span class="font-semibold">{{
                                structure.city
                            }}</span>
                            <span class="text-xs">
                                ({{ structure.zip_code }})
                            </span>
                            le
                            <span class="font-semibold">{{
                                structure.name
                            }}</span>
                            vous propose de pratiquer:
                        </p>
                        <p
                            v-for="discipline in structure.disciplines"
                            :key="discipline.id"
                            class="flex items-center font-semibold"
                        >
                            <CheckIcon class="mr-2 h-5 w-5 text-blue-500" />
                            {{ discipline.name }}
                        </p>
                    </div>
                </div>
                <div v-if="$page.props.auth.user" class="w-full md:w-1/4">
                    <div
                        v-if="can.update"
                        class="flex flex-col justify-between space-y-2 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="route('activites.create', structure)"
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Ajouter des activités</Link
                        >

                        <!-- <Link
                            :href="route('media.edit', club.slug)"
                            v-if="can.update"
                            class="flex flex-col items-center justify-center px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Mettre à jour les images</Link
                        >  -->
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

        <section class="mx-auto my-4 max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div
                class="flex flex-col justify-between rounded-lg bg-white px-4 py-6 shadow-lg shadow-sky-700 md:flex-row"
            >
                <div class="w-full space-y-4 md:w-2/3 md:pr-10">
                    <div class="relative mb-4 md:mb-6">
                        <p
                            class="mb-2 text-lg font-medium uppercase tracking-wider text-gray-500"
                        >
                            {{ structure.structuretype.name }}
                        </p>
                        <div
                            class="mt-4 mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3"
                        >
                            <div
                                v-for="discipline in structure.disciplines"
                                :key="discipline.id"
                                class="inline-flex cursor-auto flex-col items-center justify-center overflow-hidden truncate rounded-sm bg-blue-800 px-2 py-1.5 text-center text-sm text-gray-50 shadow-lg ring-1 ring-gray-400 ring-offset-1"
                            >
                                <span class="truncate">{{
                                    discipline.name
                                }}</span>
                            </div>
                        </div>
                        <div
                            class="my-4 flex items-center justify-start space-x-4"
                        >
                            <img
                                v-if="structure.logo"
                                alt="img"
                                :src="clubLogoUrl"
                                class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                            />
                            <img
                                v-else
                                alt="img"
                                src="https://via.placeholder.com/360x360.png/151f32?text=LOGO"
                                class="h-20 w-20 shrink-0 rounded-full object-cover object-center"
                            />
                            <h2
                                class="inline-block text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ structure.name }}
                            </h2>
                        </div>
                    </div>
                    <TabsComponent :structure="structure"></TabsComponent>
                    <!-- <div
                        v-if="mediasImg.data[0]"
                        class="grid grid-cols-3 gap-2 p-2 mx-auto justify-evenly md:grid-cols-6"
                    >
                        <div
                            v-for="(media, index) in mediasImg.data"
                            :key="index"
                            :class="
                                index === 0
                                    ? 'col-span-3 md:col-span-6'
                                    : 'col-span-3 md:col-span-2'
                            "
                        >
                            <img
                                loading="lazy"
                                :src="media.preview_url"
                                :alt="media.file_name"
                                class="object-cover w-auto h-auto p-2 m-2 mx-auto border-2 rounded-lg shadow-md shadow-sky-700"
                            />
                        </div>
                    </div> -->
                </div>
                <div class="w-full md:w-1/3">
                    <div class="my-4 flex items-center justify-center md:mb-8">
                        <h3 class="text-base font-semibold uppercase">
                            Coordonnées de la structure
                        </h3>
                        <!-- <button
                            type="button"
                            class="flex flex-col items-center justify-center px-4 py-2 overflow-hidden text-sm text-center text-gray-600 transition duration-150 bg-white shadow-lg hover:bg-blue-400 hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-4 focus:ring-2 focus:ring-green-400 focus:ring-offset-4 sm:rounded-lg"
                        >
                            Contacter le club
                        </button> -->
                    </div>
                    <div class="mb-4 space-y-6">
                        <p class="text-base font-semibold text-gray-700">
                            <UserIcon class="inline-block h-4 w-4" />
                            {{ structure.user.name }}
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
                            v-if="structure.phone"
                            class="text-base font-medium text-gray-700"
                        >
                            <PhoneIcon class="inline-block h-4 w-4" />
                            {{ structure.phone }}
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
            </div>
        </section>
        <section
            v-if="structure.activites"
            class="mx-auto my-4 max-w-7xl space-y-4 px-4 py-6 sm:px-6 lg:px-8"
        >
            <h2 class="text-xl font-bold">Nos activités</h2>
            <div
                v-for="(activite, index) in structure.activites"
                :key="activite.id"
                :index="index"
                class="flex w-full rounded-lg border border-blue-600 px-6 py-4 text-gray-800 shadow-md"
            >
                <div class="flex w-full flex-col">
                    <h3 class="text-lg font-semibold">{{ activite.name }}</h3>
                    <p class="text-base font-semibold">
                        Niveaux:
                        <span class="font-normal">{{
                            activite.nivel.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Type d'activité:
                        <span class="font-normal">{{
                            activite.activitetype.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Discipline pratiquée:
                        <span class="font-normal">{{
                            activite.discipline.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Public:
                        <span class="font-normal">{{
                            activite.publictype.name
                        }}</span>
                    </p>
                    <p class="text-base font-semibold">
                        Description:
                        <span class="font-normal">{{
                            activite.description
                        }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-end px-4">
                    <!-- <button
                        v-if="can.delete"
                        @click="destroy(activite.id)"
                        class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-red-400 hover:text-white hover:ring-2 hover:ring-red-400 hover:ring-offset-2 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 sm:rounded-lg"
                    >
                        Supprimer cette activité
                    </button> -->
                </div>
            </div>
        </section>
    </AppLayout>
</template>
