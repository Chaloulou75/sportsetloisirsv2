<script setup>
import { router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import relativeTime from "dayjs/plugin/relativeTime";
import localeData from "dayjs/plugin/localeData";
import { TrashIcon } from "@heroicons/vue/24/outline";
dayjs.extend(localeData);
dayjs.extend(relativeTime);

const props = defineProps({
    post: Object,
    comment: Object,
    user: Object,
    admin: Boolean,
});

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").fromNow();
};

const deleteComment = () => {
    router.delete(
        route("posts.comments.destroy", {
            post: props.post,
            comment: props.comment,
        }),
        {
            preserveScroll: true,
            only: ["comments"],
        }
    );
};
</script>
<template>
    <article class="flex space-x-4">
        <div class="flex-shrink-0">
            <img
                src="https://i.pravatar.cc/60?u={{ comment.author.id }}"
                alt="avatar"
                width="60"
                height="60"
                class="rounded-xl"
            />
        </div>

        <div class="flex-1">
            <header class="mb-4">
                <h3 class="font-bold">{{ comment.author.name }}</h3>

                <p class="text-xs">
                    Publié
                    <time>{{ formatDate(comment.created_at) }}</time>
                </p>
            </header>

            <p v-html="comment.body" class="text-justify"></p>
            <footer class="flex items-end justify-end w-full mt-2">
                <button
                    v-if="
                        (user && comment.user_id === user.id) ||
                        (admin && admin === true)
                    "
                    class="p-2 bg-red-400 rounded hover:bg-red-600"
                    @click="deleteComment()"
                    type="button"
                >
                    <TrashIcon class="w-4 h-4 text-white" />
                </button>
            </footer>
        </div>
    </article>
</template>
