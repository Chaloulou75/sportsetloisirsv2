<script setup>
import { UserCircleIcon } from "@heroicons/vue/24/outline";
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
        class="rounded-xl border border-black border-opacity-0 transition-colors duration-300 hover:border-opacity-5 hover:bg-gray-100"
    >
        <div class="px-5 py-6 lg:flex">
            <div v-if="post.thumbnail" class="flex-1 lg:mr-8">
                <img
                    src="{{ asset('storage/' . post.thumbnail) }}"
                    alt="Blog Post illustration"
                    class="rounded-xl"
                />
            </div>

            <div class="flex flex-1 flex-col justify-between">
                <header class="mt-8 lg:mt-0">
                    <div class="mt-4">
                        <h1 class="text-3xl">
                            <Link :href="route('posts.show', post.slug)">
                                {{ post.title }}
                            </Link>
                        </h1>

                        <span class="mt-2 block text-xs text-gray-400">
                            Publié
                            <time>{{ formatDate(post.created_at) }}</time>
                        </span>
                    </div>
                </header>

                <div class="mt-2 space-y-4 text-sm" v-html="post.excerpt"></div>

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
