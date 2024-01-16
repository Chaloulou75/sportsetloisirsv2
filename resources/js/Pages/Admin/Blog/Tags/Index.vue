<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ChevronLeftIcon, XCircleIcon } from "@heroicons/vue/24/outline";
import NavAdminBlog from "@/Components/Admin/NavAdminBlog.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/Forms/InputError.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import autoAnimate from "@formkit/auto-animate";
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const props = defineProps({
    errors: Object,
    tags: Object,
    user_can: Object,
});

const filterInput = ref("");

const filteredTags = computed(() => {
    return props.tags.data.filter((tag) =>
        tag.name.toLowerCase().includes(filterInput.value.toLowerCase())
    );
});

const tagForm = useForm({
    name: null,
    // disciplines: false,
    categories: false,
});

const addTag = () => {
    tagForm.post(route("admin.blog.tags.store"), {
        preserveScroll: true,
        onSuccess: () => tagForm.reset(),
    });
};

const deleteTag = (tag) => {
    const confirmed = window.confirm(
        "Êtes-vous sûr de vouloir supprimer ce tag ?"
    );
    if (confirmed) {
        router.delete(
            route("admin.blog.tags.destroy", {
                tag: tag,
            }),
            {
                preserveScroll: true,
            }
        );
    }
};
const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head
        title="Gestion des tags du blog"
        :description="'Administration du blog.'"
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
                    Gestion du Blog
                </h1>
            </div>
        </template>
        <NavAdminBlog />

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="addTag" class="max-w-sm space-y-3">
                    <h3 class="mt-4 text-lg font-semibold text-gray-700">
                        Ajouter des tags:
                    </h3>
                    <TextInput
                        type="text"
                        name="tag"
                        class="mt-1 block w-full"
                        v-model="tagForm.name"
                        placeholder="Ajouter un tag"
                    />
                    <InputError
                        v-if="tagForm.errors.name"
                        class="mt-2"
                        :message="tagForm.errors.name"
                    />
                    <!-- <div class="flex items-center">
                        <input
                            v-model="tagForm.disciplines"
                            id="disciplines"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                        />
                        <label
                            for="disciplines"
                            class="ml-2 text-sm font-medium text-gray-700"
                            >Ajouter les noms de toutes les disciplines?</label
                        >
                    </div> -->
                    <div class="flex items-center">
                        <input
                            v-model="tagForm.categories"
                            id="categories"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                        />
                        <label
                            for="categories"
                            class="ml-2 text-sm font-medium text-gray-700"
                            >Ajouter les noms de toutes les catégories?</label
                        >
                    </div>
                    <!-- <div class="flex items-center">
                        <input
                            v-model="tagForm.cities"
                            id="cities"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                        />
                        <label
                            for="cities"
                            class="ml-2 text-sm font-medium text-gray-700"
                            >Ajouter les noms de toutes les villes?</label
                        >
                    </div> -->
                    <button
                        class="flex w-full max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                        :disabled="tagForm.processing"
                        type="submit"
                    >
                        <LoadingSVG v-if="tagForm.processing" />
                        Ajouter
                    </button>
                </form>
                <h3 class="mt-4 text-lg font-semibold text-gray-700">
                    Liste des Tags existants:
                </h3>
                <TextInput
                    type="text"
                    name="tags"
                    class="mt-1 block w-full max-w-sm"
                    v-model="filterInput"
                    placeholder="Rechercher un tag"
                />
                <div
                    ref="toAnimateOne"
                    v-if="tags.data && tags.data.length > 0"
                    class="mt-2 flex flex-wrap items-center"
                >
                    <button
                        type="button"
                        v-for="tag in filteredTags"
                        :key="tag.id"
                        @click.prevent="deleteTag(tag)"
                        class="group m-px flex items-center border bg-white p-1 text-sm hover:bg-blue-600 hover:text-white"
                    >
                        <div class="flex items-center space-x-1">
                            <span>{{ tag.name }}</span>

                            <span
                                class="text-indigo-600 group-hover:text-white"
                                v-if="tag.posts_count > 0"
                                >{{ tag.posts_count }}
                                <span v-if="tag.posts_count > 1">articles</span>
                                <span v-else>article</span>
                            </span>
                        </div>

                        <XCircleIcon
                            class="ml-2 h-4 w-4 text-red-500 group-hover:text-white"
                        />
                    </button>
                </div>
                <div v-if="tags.data" class="flex justify-end p-10">
                    <Pagination :links="tags.links" />
                </div>
                <div v-else>
                    <p class="text-base text-gray-700">Pas encore de tags.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
