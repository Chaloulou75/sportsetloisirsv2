<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { defineAsyncComponent } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import relativeTime from "dayjs/plugin/relativeTime";
import localeData from "dayjs/plugin/localeData";
const PostComment = defineAsyncComponent(() =>
    import("@/Components/Posts/PostComment.vue")
);
dayjs.extend(localeData);
dayjs.extend(relativeTime);

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    post: Object,
});

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").fromNow();
};
</script>

<template>
    <Head :title="post.title" :description="post.title" />

    <ResultLayout :list-disciplines="listDisciplines" :all-cities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    {{ post.title }}
                </template>
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
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
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

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('posts.show', post.slug)"
                                    class="flex h-10 shrink-0 items-center truncate bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ post.title }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <template #default>
            <div class="px-3 py-6 md:py-12">
                <article
                    class="mx-auto max-w-6xl gap-x-10 lg:grid lg:grid-cols-12"
                >
                    <div class="col-span-4 mb-10 lg:pt-14 lg:text-center">
                        <img
                            v-if="post.thumbnail"
                            src="{{ asset('storage/' . post.thumbnail) }}"
                            alt=""
                            class="rounded-xl"
                        />

                        <p class="mt-4 block text-xs text-gray-400">
                            Publié
                            <time>{{ formatDate(post.created_at) }}</time>
                        </p>

                        <div
                            class="mt-4 flex items-center text-sm lg:justify-center"
                        >
                            <UserCircleIcon class="h-6 w-6 fill-gray-300" />
                            <div class="ml-3 text-left">
                                <h5 class="font-bold">
                                    {{ post.author.name }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-8">
                        <div class="mb-6 justify-between lg:flex">
                            <Link
                                :href="route('posts.index')"
                                class="relative inline-flex items-center text-lg transition-colors duration-300 hover:text-blue-500"
                            >
                                <svg
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    class="mr-2"
                                >
                                    <g fill="none" fill-rule="evenodd">
                                        <path
                                            stroke="#000"
                                            stroke-opacity=".012"
                                            stroke-width=".5"
                                            d="M21 1v20.16H.84V1z"
                                        ></path>
                                        <path
                                            class="fill-current"
                                            d="M13.854 7.224l-3.847 3.856 3.847 3.856-1.184 1.184-5.04-5.04 5.04-5.04z"
                                        ></path>
                                    </g>
                                </svg>

                                Retour aux articles
                            </Link>
                        </div>

                        <h1 class="mb-10 text-3xl font-bold lg:text-4xl">
                            {{ post.title }}
                        </h1>

                        <div
                            class="space-y-4 leading-loose lg:text-lg"
                            v-html="post.body"
                        ></div>
                    </div>

                    <section class="col-span-8 col-start-5 mt-10 space-y-6">
                        <PostComment
                            v-for="comment in post.comments"
                            :key="comment.id"
                            :comment="comment"
                        />
                    </section>
                </article>
            </div>
        </template>
    </ResultLayout>
</template>
