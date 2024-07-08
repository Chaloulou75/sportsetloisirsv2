<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    ChevronLeftIcon,
    XCircleIcon,
    PlusCircleIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    discipline: Object,
    familles: Object,
    user_can: Object,
});

const attachFamille = (familleNotIn) => {
    router.post(
        route("familles-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            familleNotIn: familleNotIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const detachFamille = (familleIn) => {
    router.put(
        route("familles-disciplines.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "put",
            familleIn: familleIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <Head :title="`Administration de la discipline ${discipline.name}`">
        <meta
            head-key="description"
            name="description"
            :content="`Administration de la discipline ${discipline.name}`"
        />
    </Head>
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.disciplines.edit', discipline)"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Familles associées à la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />

        <div class="space-y-16 px-2 py-6 md:px-6">
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 md:flex-row md:items-start md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Les familles associées à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la famille)</span
                        >
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="familleIn in discipline.familles"
                            :key="familleIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="detachFamille(familleIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ familleIn.name }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3 class="mb-4 text-center text-lg text-slate-700">
                        Ajouter des familles de disciplines
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="familleNotIn in familles"
                            :key="familleNotIn.id"
                            class="group inline-flex self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachFamille(familleNotIn)"
                                class="inline-flex w-40 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ familleNotIn.name }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
