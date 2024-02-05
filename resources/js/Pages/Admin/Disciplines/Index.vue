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
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion du contenu (disciplines, catégories, critères)
                </h1>
            </div>
        </template>

        <div class="w-full px-2 py-6 space-y-16 text-slate-700 md:px-6">
            <div
                class="flex flex-col items-start justify-between w-full space-y-4 gap-x-4"
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
                        <span class="inline-flex mr-4 group-hover:text-white"
                            >Gérer {{ discipline }}</span
                        >
                        <MagnifyingGlassIcon
                            class="w-5 h-5 text-indigo-500 group-hover:text-white"
                        />
                    </Link>
                </template>

                <template
                    class="flex flex-col items-end justify-center w-full space-y-2 gap-x-4 md:flex-row md:space-y-0"
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
                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium bg-gray-100 border border-transparent rounded shadow-sm text-slate-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 focus:ring-offset-2"
                            >
                                Annuler
                            </button>
                            <button
                                :disabled="createInfoBaseForm.processing"
                                :class="{
                                    'opacity-25': createInfoBaseForm.processing,
                                }"
                                type="submit"
                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
