<script setup>
import {
    UserCircleIcon,
    HandThumbUpIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import relativeTime from "dayjs/plugin/relativeTime";
import localeData from "dayjs/plugin/localeData";

dayjs.extend(localeData);
dayjs.extend(relativeTime);

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").fromNow();
};

const props = defineProps({
    post: Object,
});
</script>
<template>
    <article
        class="rounded-xl border border-indigo-600 border-opacity-5 bg-gray-50 transition-colors duration-300 hover:border-opacity-100 hover:bg-gray-100"
    >
        <div class="px-5 py-6 lg:flex">
            <div v-if="post.thumbnail" class="lg:mr-8">
                <img
                    :src="post.image_url"
                    alt="Blog Post illustration"
                    class="h-20 w-20 rounded-xl object-cover"
                />
            </div>

            <div class="flex flex-1 flex-col justify-between">
                <header class="mt-2">
                    <div
                        v-if="post.disciplines"
                        class="flex flex-wrap items-center"
                    >
                        <div
                            v-for="discipline in post.disciplines"
                            :key="discipline.id"
                            class="m-px flex items-center border bg-white p-1 text-xs"
                        >
                            {{ discipline.name }}
                        </div>
                        <template v-if="post.tags">
                            <div
                                v-for="tag in post.tags"
                                :key="tag.id"
                                class="m-px flex items-center border bg-white p-1 text-xs"
                            >
                                {{ tag.name }}
                            </div>
                        </template>
                    </div>
                    <div class="mt-4">
                        <h1 class="text-3xl text-gray-800 hover:text-gray-900">
                            <Link :href="route('posts.show', post.slug)">
                                {{ post.title }}
                            </Link>
                        </h1>

                        <div
                            class="mt-2 flex w-full items-center justify-between"
                        >
                            <div class="block text-xs text-gray-400">
                                Publié
                                <time>{{ formatDate(post.created_at) }}</time>
                            </div>
                            <div
                                class="flex items-center space-x-2 text-xs text-gray-400"
                            >
                                <EyeIcon class="h-5 w-5 text-gray-400" />
                                <span>{{ post.views_count }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <div
                    class="prose prose-slate mt-2 space-y-4 text-sm"
                    v-html="post.excerpt"
                ></div>
                <div
                    class="mt-2 flex items-center justify-between text-xs text-gray-600"
                >
                    <span
                        >{{ post.comments_count }}
                        <span v-if="post.comments_count > 1">commentaires</span>
                        <span v-else>commentaire</span>
                    </span>
                    <div
                        class="flex items-center space-x-2 text-xs text-gray-400"
                    >
                        <HandThumbUpIcon class="h-5 w-5 text-gray-400" />
                        <span class="font-semibold">
                            {{ post.likes }}
                        </span>
                    </div>
                </div>

                <footer class="mt-8 flex items-center justify-between">
                    <div class="flex items-center text-sm">
                        <UserCircleIcon class="h-6 w-6 fill-gray-300" />
                        <div class="ml-3">
                            <h5 class="font-bold">
                                {{ post.author.name }}
                            </h5>
                        </div>
                    </div>

                    <div class="hidden lg:block">
                        <Link
                            :href="route('posts.show', post.slug)"
                            class="rounded-full bg-gray-200 px-8 py-2 text-xs font-semibold transition-colors duration-300 hover:bg-gray-300"
                            >Lire plus</Link
                        >
                    </div>
                </footer>
            </div>
        </div>
    </article>
</template>
