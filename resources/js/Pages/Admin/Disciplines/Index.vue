<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import AutocompleteDisciplineNav from "@/Components/Navigation/AutocompleteDisciplineNav.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    ChevronLeftIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const CreateInfoBase = defineAsyncComponent(() =>
    import("@/Components/Disciplines/CreateInfoBase.vue")
);

const props = defineProps({
    listDisciplines: Object,
    errors: Object,
    user_can: Object,
});

const discipline = ref(null);

const displayCreateDisciplineForm = ref(false);

const showCreateDisciplineForm = () => {
    return (displayCreateDisciplineForm.value =
        !displayCreateDisciplineForm.value);
};

const createInfoBaseForm = useForm({
    name: null,
    description: null,
    theme: "light",
    remember: true,
});

const submitCreateInfoBase = () => {
    router.post(
        route("disciplines.create"),
        {
            name: createInfoBaseForm.name,
            description: createInfoBaseForm.description,
            theme: createInfoBaseForm.theme,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                createInfoBaseForm.reset();
                showCreateDisciplineForm();
            },
        }
    );
};
</script>
<template>
    <Head title="Gestion du contenu" :description="'Administration du site.'" />
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
                    Gestion du contenu (disciplines, catégories, critères)
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <div
                class="flex w-full flex-col items-start justify-between gap-x-4 space-y-4"
            >
                <h2 class="text-lg font-medium">Rechercher une discipline:</h2>
                <AutocompleteDisciplineNav
                    :disciplines="listDisciplines"
                    v-model="discipline"
                />
                <template v-if="discipline" class="w-full md:w-auto">
                    <Link
                        :href="
                            route(
                                'admin.disciplines.informations.edit',
                                discipline
                            )
                        "
                        class="group inline-flex w-full max-w-md items-center justify-center rounded border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-600 shadow-sm hover:bg-indigo-500 hover:text-white focus:outline-none focus:ring-2"
                    >
                        <span class="mr-4 inline-flex group-hover:text-white"
                            >Gérer {{ discipline }}</span
                        >
                        <MagnifyingGlassIcon
                            class="h-5 w-5 text-indigo-500 group-hover:text-white"
                        />
                    </Link>
                </template>

                <template
                    class="flex w-full flex-col items-end justify-center gap-x-4 space-y-2 md:flex-row md:space-y-0"
                    v-if="!discipline"
                >
                    <button
                        v-if="!displayCreateDisciplineForm"
                        @click="showCreateDisciplineForm"
                        type="button"
                        class="inline-flex w-full max-w-md justify-center rounded border border-transparent bg-indigo-600 px-4 py-2.5 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Créer une nouvelle discipline
                    </button>
                    <form
                        v-if="displayCreateDisciplineForm"
                        @submit.prevent="submitCreateInfoBase"
                        class="w-full max-w-md space-y-4"
                    >
                        <CreateInfoBase
                            :errors="errors"
                            v-model:name="createInfoBaseForm.name"
                            v-model:description="createInfoBaseForm.description"
                            v-model:theme="createInfoBaseForm.theme"
                        />

                        <div class="flex max-w-md space-x-2">
                            <button
                                @click="showCreateDisciplineForm"
                                type="button"
                                class="inline-flex w-full justify-center rounded border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 focus:ring-offset-2"
                            >
                                Annuler
                            </button>
                            <button
                                :disabled="createInfoBaseForm.processing"
                                type="submit"
                                class="inline-flex w-full justify-center rounded border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <LoadingSVG
                                    v-if="createInfoBaseForm.processing"
                                />
                                Créer la discipline
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
