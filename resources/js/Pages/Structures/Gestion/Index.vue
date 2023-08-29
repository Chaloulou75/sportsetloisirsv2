<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PathsInscriptionNavigation from "@/Components/Navigation/PathsInscriptionNavigation.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    structure: Object,
    can: Object,
});
</script>
<template>
    <Head
        title="Gestion de votre structure"
        :description="'Gestion de votre structure, disciplines et activités.'"
    />
    <ProLayout :structure="structure" :can="can">
        <template #header>
            <h1
                class="text-xl font-semibold leading-tight tracking-widest text-gray-700"
            >
                Accueil
            </h1>
        </template>

        <template #default="{ displayAdresses, handleCloseEvent }">
            <div class="px-2 py-6 text-gray-700 md:px-4">
                <h3 class="mb-4 text-xl">
                    Bienvenue
                    <span class="text-indigo-700">{{ user.name }}</span>
                </h3>
                <p>Vos demandes de réservations en attente</p>
                <ul>
                    <!-- <li v-for="reservation in "></li> -->
                </ul>

                <template v-if="displayAdresses">
                    <div
                        class="5 w-full space-y-6 rounded-sm border border-gray-200 px-4 py-2"
                    >
                        <div class="flex items-center justify-between">
                            <h3>Vos adresses</h3>
                            <button type="button" @click="handleCloseEvent">
                                <XCircleIcon class="h-7 w-7 text-red-500" />
                            </button>
                        </div>

                        <ul class="space-y-2">
                            <li
                                v-for="adresse in structure.adresses"
                                :key="adresse.id"
                            >
                                {{ adresse.address }}, {{ adresse.zip_code }}
                                {{ adresse.city }}
                            </li>
                        </ul>
                    </div>
                </template>
            </div>
        </template>
    </ProLayout>
</template>
