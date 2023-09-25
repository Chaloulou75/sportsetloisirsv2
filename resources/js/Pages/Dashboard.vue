<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Accueil" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Accueil
            </h2>
        </template>

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Vous êtes connecté!</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
