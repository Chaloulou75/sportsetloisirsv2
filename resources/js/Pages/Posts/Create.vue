<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, watchEffect } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
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
    disciplines: Object,
    tags: Object,
});

const filterDiscipline = ref("");
const filteredDisciplines = ref(null);

watchEffect(() => {
    // Update filteredDisciplines based on filterDiscipline.value
    filteredDisciplines.value = filterDiscipline.value
        ? props.disciplines.filter(
              (discipline) =>
                  discipline.name
                      .toLowerCase()
                      .includes(filterDiscipline.value.toLowerCase()) &&
                  !selectedDisciplines.value.some(
                      (selectedDiscipline) =>
                          selectedDiscipline.id === discipline.id
                  )
          )
        : null;
});

const selectedDisciplines = ref([]);
const addDisciplineFromList = (discipline) => {
    if (
        !selectedDisciplines.value.some(
            (selectedDiscipline) => selectedDiscipline.id === discipline.id
        )
    ) {
        selectedDisciplines.value.push(discipline);
        articleForm.disciplines.push(discipline);
        filterDiscipline.value = "";
    }
};

const removeDisciplineFromList = (discipline) => {
    const selectedDisciplineIndex =
        selectedDisciplines.value.indexOf(discipline);
    const articleFormDisciplineIndex =
        articleForm.disciplines.indexOf(discipline);
    if (selectedDisciplineIndex !== -1) {
        selectedDisciplines.value.splice(selectedDisciplineIndex, 1);
    }
    if (articleFormDisciplineIndex !== -1) {
        articleForm.disciplines.splice(articleFormDisciplineIndex, 1);
    }
};

//tags
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
    disciplines: [],
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

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Ecrire un article </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex overflow-hidden text-gray-600 border border-gray-200 rounded-lg"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
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
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
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
                class="max-w-5xl px-2 py-3 mx-auto rounded-md shadow bg-gray-50 sm:px-6 lg:px-8"
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
                        <div class="flex mt-1 rounded-md">
                            <input
                                ref="title"
                                v-model="articleForm.title"
                                type="text"
                                name="title"
                                id="title"
                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
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
                    <!-- disciplines -->
                    <div>
                        <InputLabel
                            for="tags"
                            value="Disciplines liées à l'article"
                            class="block text-sm font-medium text-gray-700"
                        />
                        <div
                            v-if="selectedDisciplines.length > 0"
                            class="flex flex-wrap items-center"
                        >
                            <button
                                type="button"
                                @click.prevent="
                                    removeDisciplineFromList(selectedDiscipline)
                                "
                                v-for="selectedDiscipline in selectedDisciplines"
                                :key="selectedDiscipline.id"
                                class="flex items-center p-1 m-px text-xs bg-white border group hover:bg-blue-600 hover:text-white"
                            >
                                {{ selectedDiscipline.name }}
                                <XCircleIcon
                                    class="w-4 h-4 ml-2 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </div>

                        <TextInput
                            type="text"
                            name="tags"
                            class="block w-full mt-1"
                            v-model="filterDiscipline"
                            placeholder="Rechercher et ajouter des disciplines."
                        />
                        <InputError
                            v-if="articleForm.errors.disciplines"
                            class="mt-2"
                            :message="articleForm.errors.disciplines"
                        />
                        <div class="flex flex-wrap items-center mt-2">
                            <button
                                type="button"
                                v-for="discipline in filteredDisciplines"
                                :key="discipline.id"
                                @click.prevent="
                                    addDisciplineFromList(discipline)
                                "
                                class="p-1 m-px text-xs bg-white border hover:bg-blue-600 hover:text-white"
                            >
                                {{ discipline.name }}
                            </button>
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
                                class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                class="flex items-center p-1 m-px text-xs bg-white border group hover:bg-blue-600 hover:text-white"
                            >
                                {{ selectedTag.name }}
                                <XCircleIcon
                                    class="w-4 h-4 ml-2 text-red-500 group-hover:text-white"
                                />
                            </button>
                        </div>

                        <TextInput
                            type="text"
                            name="tags"
                            class="block w-full mt-1"
                            v-model="filterInput"
                            placeholder="Rechercher et ajouter des tags."
                        />
                        <InputError
                            v-if="articleForm.errors.tags"
                            class="mt-2"
                            :message="articleForm.errors.tags"
                        />
                        <div class="flex flex-wrap items-center mt-2">
                            <button
                                type="button"
                                v-for="tag in filteredTags"
                                :key="tag.id"
                                @click.prevent="addTagFromList(tag)"
                                class="p-1 m-px text-xs bg-white border hover:bg-blue-600 hover:text-white"
                            >
                                {{ tag.name }}
                            </button>
                        </div>
                    </div>
                    <!-- body -->
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
                                    ['link', 'image'],
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

                    <div class="flex justify-end mt-6">
                        <button
                            class="flex items-center justify-center w-full max-w-xs px-4 py-2 text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                            :disabled="articleForm.processing"
                            :class="{
                                'opacity-25': articleForm.processing,
                            }"
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
