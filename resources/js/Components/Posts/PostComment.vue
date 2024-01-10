<script setup>
import dayjs from "dayjs";
import "dayjs/locale/fr";
import relativeTime from "dayjs/plugin/relativeTime";
import localeData from "dayjs/plugin/localeData";
dayjs.extend(localeData);
dayjs.extend(relativeTime);

const props = defineProps({
    comment: Object,
});

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").fromNow();
};
</script>
<template>
    <article class="flex space-x-4">
        <div class="flex-shrink-0">
            <img
                src="https://i.pravatar.cc/60?u=fake@pravatar.com"
                alt="avatar"
                width="60"
                height="60"
                class="rounded-xl"
            />
        </div>

        <div>
            <header class="mb-4">
                <h3 class="font-bold">{{ comment.author.name }}</h3>

                <p class="text-xs">
                    Publié
                    <time>{{ formatDate(comment.created_at) }}</time>
                </p>
            </header>

            <p v-html="comment.body"></p>
        </div>
    </article>
</template>
