<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PathsInscriptionNavigation from "@/Components/Navigation/PathsInscriptionNavigation.vue";

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
        :description="'Gestion de votre structure et activités.'"
    />
    <ProLayout :structure="structure" :can="can">
        <template #header>
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-xl font-semibold leading-tight tracking-widest text-gray-700"
                >
                    Accueil
                </h1>
            </div>
        </template>

        <div class="py-6">
            <p>Bienvenue {{ user.name }}</p>
        </div>
    </ProLayout>
</template>
