<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const props = defineProps({
    errors: Object,
    users: Object,
    user_can: Object,
});
</script>
<template>
    <Head
        title="Gestion des utilisateurs"
        :description="'Administration du site. Gestion des utilisateurs'"
    />
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
                    Gestion des utilisateurs (To Do!)
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des utilisateurs
            </h2>
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les utilisateurs existants:
                        </h3>
                        <div
                            class="flex flex-wrap items-stretch justify-center gap-4"
                        >
                            <Link
                                v-for="user in users.data"
                                :key="user.id"
                                class="flex w-auto flex-col items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                :href="route('profile.edit', user)"
                            >
                                {{ user.name }}
                                <span class="text-xs">{{ user.email }}</span>
                            </Link>

                            <Pagination :links="users.links" />
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un utilisateur:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            :href="route('register')"
                            >Créer une nouvel utilisateur</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
