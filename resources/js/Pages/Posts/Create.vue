<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, watchEffect } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    tags: Object,
});

const filterInput = ref("");
const filteredTags = ref(null);

watchEffect(() => {
    // Update filteredTags based on filterInput.value
    filteredTags.value = filterInput.value
        ? props.tags.filter(
              (tag) =>
                  tag.name
                      .toLowerCase()
                      .includes(filterInput.value.toLowerCase()) &&
                  !selectedTags.value.some(
                      (selectedTag) => selectedTag.id === tag.id
                  )
          )
        : null;
});

const selectedTags = ref([]);
const addTagFromList = (tag) => {
    if (!selectedTags.value.some((selectedTag) => selectedTag.id === tag.id)) {
        selectedTags.value.push(tag);
        articleForm.tags.push(tag);
        filterInput.value = "";
    }
};

const removeTagFromList = (tag) => {
    const selectedTagIndex = selectedTags.value.indexOf(tag);
    const articleFormTagIndex = articleForm.tags.indexOf(tag);
    if (selectedTagIndex !== -1) {
        selectedTags.value.splice(selectedTagIndex, 1);
    }
    if (articleFormTagIndex !== -1) {
        articleForm.tags.splice(articleFormTagIndex, 1);
    }
};

const articleForm = useForm({
    title: null,
    thumbnail: null,
    excerpt: null,
    body: null,
    tags: [],
});

const addArticle = () => {
    articleForm.post(route("posts.store"), {
        preserveScroll: true,
        onSuccess: () => articleForm.reset(),
    });
};
</script>

<template>
    <Head
        title="Ecrire un article de blog"
        :description="'Creation d\'un article de blog sur www.sports-et-loisirs.fr.'"
    />

    <ResultLayout :list-disciplines="listDisciplines" :all-cities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title> Ecrire un article </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                        />
                                    </svg>

                                    <span class="ms-1.5 text-xs font-medium">
                                        Accueil
                                    </span>
                                </Link>
                            </li>
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('posts.index')"
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    Blog
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <div class="py-6 md:py-12">
            <div
                class="mx-auto max-w-5xl rounded-md bg-gray-50 px-2 py-3 shadow sm:px-6 lg:px-8"
            >
                <form
                    @submit.prevent="addArticle"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    <!-- title -->
                    <div class="">
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Titre de l'article*
                        </label>
                        <div class="mt-1 flex rounded-md">
                            <input
                                ref="title"
                                v-model="articleForm.title"
                                type="text"
                                name="title"
                                id="title"
                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                placeholder=""
                                autocomplete="none"
                            />
                        </div>
                        <div
                            v-if="articleForm.errors.title"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ articleForm.errors.title }}
                        </div>
                    </div>
                    <!-- image -->
                    <div>
                        <label
                            for="thumbnail"
                            class="block text-sm font-medium text-gray-700"
                            >Image:</label
                        >
                        <input
                            class="mt-1 text-sm text-gray-700"
                            type="file"
                            id="thumbnail"
                            name="thumbnail"
                            @input="
                                articleForm.thumbnail = $event.target.files[0]
                            "
                        />
                        <span
                            class="mt-2 text-xs text-red-500"
                            v-if="articleForm.errors.thumbnail"
                            v-text="articleForm.errors.thumbnail"
                        ></span>
                    </div>
                    <!-- excerpt -->
                    <div>
                        <label
                            for="excerpt"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Extrait / résumé*
                        </label>
                        <div class="mt-1">
                            <textarea
                                v-model="articleForm.excerpt"
                                id="excerpt"
                                name="excerpt"
                                rows="2"
                                class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{
                                    errors: 'border-red-500 focus:ring focus:ring-red-200',
                                }"
                                placeholder="Résumé de l'article."
                                autocomplete="none"
                            />
                        </div>

                        <div
                            v-if="articleForm.errors.excerpt"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ articleForm.errors.excerpt }}
                        </div>
                    </div>
                    <!-- tags -->
                    <div>
                        <InputLabel
                            for="tags"
                            value="Tags de l'article *"
                            class="block text-sm font-medium text-gray-700"
                        />
                        <div
                            v-if="selectedTags.length > 0"
                            class="flex flex-wrap items-center"
                        >
                            <button
                                type="button"
                                @click.prevent="removeTagFromList(selectedTag)"
                                v-for="selectedTag in selectedTags"
                                :key="selectedTag.id"
                                class="group m-px flex items-center border bg-white p-1 text-xs hover:bg-blue-600 hover:text-white"
                            >
                                {{ selectedTag.name }}
                                <XCircleIcon
                                    class="ml-2 h-4 w-4 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </div>

                        <TextInput
                            type="text"
                            name="tags"
                            class="mt-1 block w-full"
                            v-model="filterInput"
                            placeholder="Rechercher et ajouter des tags."
                        />
                        <InputError
                            v-if="articleForm.errors.tags"
                            class="mt-2"
                            :message="articleForm.errors.tags"
                        />
                        <div class="mt-2 flex flex-wrap items-center">
                            <button
                                type="button"
                                v-for="tag in filteredTags"
                                :key="tag.id"
                                @click.prevent="addTagFromList(tag)"
                                class="m-px border bg-white p-1 text-xs hover:bg-blue-600 hover:text-white"
                            >
                                {{ tag.name }}
                            </button>
                        </div>
                    </div>
                    <div>
                        <label
                            for="body"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Contenu de l'article*
                        </label>
                        <div class="bg-white">
                            <QuillEditor
                                v-model:content="articleForm.body"
                                contentType="html"
                                theme="snow"
                                :toolbar="[
                                    ['bold', 'italic', 'underline'],
                                    ['blockquote'],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    [{ header: [2, 3, 4, 5, 6, false] }],
                                    [
                                        {
                                            size: ['small', false, 'large'],
                                        },
                                    ],
                                    [{ indent: '-1' }, { indent: '+1' }],
                                    [{ color: [] }, { background: [] }],
                                    [{ font: [] }],
                                    [{ align: [] }],
                                    ['link'],
                                ]"
                            />
                        </div>
                        <div
                            v-if="articleForm.errors.body"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ articleForm.errors.body }}
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            class="flex w-full max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                            :disabled="articleForm.processing"
                            type="submit"
                        >
                            <LoadingSVG v-if="articleForm.processing" />
                            Publier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </ResultLayout>
</template>
