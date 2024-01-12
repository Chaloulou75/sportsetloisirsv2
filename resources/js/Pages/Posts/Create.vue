<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});

const articleForm = useForm({
    title: null,
    excerpt: null,
    body: null,
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
        title="Article de Blog"
        :description="'Creation d\'un article de blog sur www.sports-et-loisirs.fr.'"
    />

    <ResultLayout :list-disciplines="listDisciplines" :all-cities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title> Article </template>
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
            <div class="mx-auto max-w-5xl px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="addArticle" class="space-y-6">
                    <!-- title -->
                    <div class="">
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Titre de l'article *
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
                    <!-- presentation_courte -->
                    <div>
                        <label
                            for="presentation_courte"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Description courte / résumé *
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
                                placeholder="Un peu d'historique, vos activités... Mettez votre structure en valeur"
                                autocomplete="none"
                            />
                        </div>

                        <div
                            v-if="articleForm.errors.presentation_courte"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ articleForm.errors.presentation_courte }}
                        </div>
                    </div>
                    <div>
                        <label
                            for="body"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Contenu de l'article *
                        </label>
                        <QuillEditor
                            v-model:content="articleForm.body"
                            contentType="html"
                            theme="snow"
                            toolbar="full"
                        />
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
