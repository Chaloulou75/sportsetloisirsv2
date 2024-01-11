<script setup>
import { useForm } from "@inertiajs/vue3";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
const props = defineProps({
    post: Object,
    user: Object,
});

const commentForm = useForm({
    body: null,
    remember: true,
});

const addComment = () => {
    commentForm.post(
        route("posts.comments.store", {
            post: props.post,
        }),
        {
            preserveScroll: true,
            onSuccess: () => commentForm.reset(),
        }
    );
};
</script>
<template>
    <form @submit.prevent="addComment">
        <header class="flex items-center">
            <img
                src="https://i.pravatar.cc/60?u={{ user.id }}"
                alt=""
                width="40"
                height="40"
                class="rounded-full"
            />
            <label
                for="commentPost"
                class="ml-4 block text-sm font-medium text-gray-700"
            >
                Ajouter un commentaire?
            </label>
        </header>

        <div class="mt-6">
            <textarea
                id="commentPost"
                name="body"
                v-model="commentForm.body"
                class="mt-2 w-full rounded-lg border-gray-200 align-top shadow-sm sm:text-sm"
                rows="4"
                placeholder=""
                required
            ></textarea>
            <span
                v-if="commentForm.errors.body"
                class="mt-2 text-sm text-red-500"
            >
                {{ commentForm.errors.body }}
            </span>
        </div>

        <div class="mt-6 flex justify-end">
            <button
                class="flex w-full max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                :disabled="commentForm.processing"
                type="submit"
            >
                <LoadingSVG v-if="commentForm.processing" />
                Publier
            </button>
        </div>
    </form>
</template>
