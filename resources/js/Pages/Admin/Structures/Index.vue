<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    structures: Object,
    errors: Object,
    user_can: Object,
});
</script>
<template>
    <Head title="Gestion des structures">
        <meta
            head-key="description"
            name="description"
            :content="`Administration du site.`"
        />
    </Head>
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
                    Gestion des structures
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div>
                <div
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les structures existantes:
                        </h3>
                        <div
                            class="mt-4 grid h-full grid-cols-1 place-content-center place-items-stretch gap-4 md:grid-cols-4 md:gap-6"
                        >
                            <Link
                                v-for="structure in structures"
                                :key="structures.id"
                                class="inline-flex items-center justify-center space-y-1 rounded border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-blue-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-blue-500"
                                :href="route('structures.edit', structure.slug)"
                            >
                                {{ structure.name }}
                            </Link>
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-4 text-center text-lg text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un structure:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-md transition-all duration-200 ease-in-out hover:border-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 active:bg-indigo-600"
                            :href="route('structures.create')"
                            >Créer une nouvelle structure</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
