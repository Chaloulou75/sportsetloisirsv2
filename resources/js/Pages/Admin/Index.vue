<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";

const page = usePage();

const user = computed(() => page.props.auth.user);
const appName = computed(() => page.props.appName);
</script>
<template>
    <Head title="Gestion du site" :description="'Administration du site.'" />
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Panel d'administration
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <p class="text-lg text-gray-700">
                Bienvenue
                <span class="font-semibold">{{ user.name }}</span> dans le panel
                d'administration de
                <span class="font-semibold">{{ appName }}</span>
            </p>
        </div>
    </AdminLayout>
</template>
