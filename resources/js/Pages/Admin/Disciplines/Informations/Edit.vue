<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";

const UpdateInfoBase = defineAsyncComponent(() =>
    import("@/Components/Disciplines/UpdateInfoBase.vue")
);

const props = defineProps({
    errors: Object,
    discipline: Object,
    user_can: Object,
});

const updateInfoBaseForm = useForm({
    name: props.discipline.name,
    description: props.discipline.description,
    theme: props.discipline.theme,
    remember: true,
});

const submitUpdateInfoBase = () => {
    router.put(
        route("disciplines.update", {
            discipline: props.discipline,
        }),
        {
            name: updateInfoBaseForm.name,
            description: updateInfoBaseForm.description,
            theme: updateInfoBaseForm.theme,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <Head
        :title="`Administration de la discipline ${discipline.name}`"
        :description="`Administration de la discipline ${discipline.name}`"
    />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="route('admin.disciplines.edit', discipline)"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Informations de la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />

        <div class="px-2 py-6 space-y-16 md:px-6">
            <form @submit.prevent="submitUpdateInfoBase" class="space-y-4">
                <UpdateInfoBase
                    :errors="errors"
                    v-model:name="updateInfoBaseForm.name"
                    v-model:description="updateInfoBaseForm.description"
                    v-model:theme="updateInfoBaseForm.theme"
                />
                <button
                    :disabled="updateInfoBaseForm.processing"
                    :class="{
                        'opacity-25': updateInfoBaseForm.processing,
                    }"
                    type="submit"
                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 md:w-auto"
                >
                    <LoadingSVG v-if="updateInfoBaseForm.processing" />
                    Editer la discipline
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
