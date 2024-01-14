<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { computed, defineAsyncComponent } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import {
    ChevronLeftIcon,
    HomeIcon,
    UserCircleIcon,
    TrashIcon,
    EyeIcon,
    HandThumbUpIcon,
} from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import relativeTime from "dayjs/plugin/relativeTime";
import localeData from "dayjs/plugin/localeData";
const PostComment = defineAsyncComponent(() =>
    import("@/Components/Posts/PostComment.vue")
);
const AddPostComment = defineAsyncComponent(() =>
    import("@/Components/Posts/AddPostComment.vue")
);
dayjs.extend(localeData);
dayjs.extend(relativeTime);

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    post: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const admin = computed(() => page.props.user_can.view_admin);

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").fromNow();
};

const deletePost = () => {
    router.delete(
        route("posts.destroy", {
            post: props.post,
        }),
        {
            preserveScroll: true,
        }
    );
};

const incrementPostLike = () => {
    router.post(
        route("posts.likes.store", {
            post: props.post,
        }),
        {
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <Head :title="post.title" :description="post.excerpt" />

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
                    class="mx-auto max-w-6xl gap-x-10 rounded-md bg-gray-50 px-4 py-4 lg:grid lg:grid-cols-12"
                >
                    <div class="col-span-4 mb-10 lg:pt-14 lg:text-center">
                        <img
                            v-if="post.thumbnail"
                            :src="post.image"
                            alt="image"
                            class="rounded"
                        />

                        <div class="hidden lg:block">
                            <div
                                v-if="post.tags"
                                class="flex flex-wrap items-center"
                            >
                                <div
                                    v-for="tag in post.tags"
                                    :key="tag.id"
                                    class="m-px flex items-center border bg-white p-1 text-xs"
                                >
                                    {{ tag.name }}
                                </div>
                            </div>
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

                            <div
                                class="mt-4 flex items-center justify-center space-x-5"
                            >
                                <div
                                    class="flex items-center space-x-2 text-base text-blue-600 lg:justify-center"
                                >
                                    <EyeIcon class="h-6 w-6 text-blue-600" />
                                    <span class="font-semibold">{{
                                        post.views_count
                                    }}</span>
                                </div>

                                <div
                                    class="flex items-center space-x-2 text-base text-blue-600 lg:justify-center"
                                >
                                    <button
                                        type="button"
                                        @click="incrementPostLike()"
                                    >
                                        <HandThumbUpIcon
                                            class="h-6 w-6 text-blue-600 duration-300 hover:-rotate-12 hover:scale-125 hover:text-blue-800"
                                        />
                                    </button>

                                    <span class="font-semibold">
                                        {{ post.likes }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex items-center text-sm lg:justify-center"
                            >
                                <button
                                    v-if="
                                        (user && post.user_id === user.id) ||
                                        (admin && admin === true)
                                    "
                                    class="mt-6 flex items-center space-x-3 rounded bg-red-500 p-2 hover:bg-red-600"
                                    @click="deletePost()"
                                    type="button"
                                >
                                    <TrashIcon class="h-4 w-4 text-white" />
                                    <span class="text-sm text-white"
                                        >Supprimer l'article</span
                                    >
                                </button>
                            </div>
                        </div>
                        <div class="block lg:hidden">
                            <div class="mb-6 justify-between">
                                <Link
                                    :href="route('posts.index')"
                                    class="relative inline-flex items-center text-lg transition-colors duration-200 hover:text-indigo-600"
                                >
                                    <ChevronLeftIcon class="mr-4 h-4 w-4" />
                                    Retour aux articles
                                </Link>
                            </div>
                            <div
                                v-if="post.tags"
                                class="mt-4 flex flex-wrap items-center"
                            >
                                <div
                                    v-for="tag in post.tags"
                                    :key="tag.id"
                                    class="m-px flex items-center border bg-white p-1 text-xs"
                                >
                                    {{ tag.name }}
                                </div>
                            </div>
                            <div
                                class="mt-4 flex items-center justify-between text-xs text-gray-400"
                            >
                                <div>
                                    Publié
                                    <time>{{
                                        formatDate(post.created_at)
                                    }}</time>
                                </div>

                                <div>
                                    <div
                                        class="mt-4 flex items-center justify-center space-x-5"
                                    >
                                        <div
                                            class="flex items-center space-x-2 text-base text-blue-600 lg:justify-center"
                                        >
                                            <EyeIcon
                                                class="h-6 w-6 text-blue-600"
                                            />
                                            <span class="font-semibold">{{
                                                post.views_count
                                            }}</span>
                                        </div>

                                        <div
                                            class="flex items-center space-x-2 text-base text-blue-600 lg:justify-center"
                                        >
                                            <button
                                                type="button"
                                                @click="incrementPostLike()"
                                            >
                                                <HandThumbUpIcon
                                                    class="h-6 w-6 text-blue-600 duration-300 hover:-rotate-12 hover:scale-125 hover:text-blue-800"
                                                />
                                            </button>

                                            <span class="font-semibold">
                                                {{ post.likes }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="hidden items-center text-sm lg:flex lg:justify-center"
                                    >
                                        <button
                                            v-if="
                                                (user &&
                                                    post.user_id === user.id) ||
                                                (admin && admin === true)
                                            "
                                            class="mt-6 flex items-center space-x-3 rounded bg-red-500 p-2 hover:bg-red-600"
                                            @click="deletePost()"
                                            type="button"
                                        >
                                            <TrashIcon
                                                class="h-4 w-4 text-white"
                                            />
                                            <span class="text-sm text-white"
                                                >Supprimer l'article</span
                                            >
                                        </button>
                                    </div>
                                </div>
                            </div>

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
                    </div>

                    <div class="col-span-8">
                        <div class="mb-6 hidden justify-between lg:flex">
                            <Link
                                :href="route('posts.index')"
                                class="relative inline-flex items-center text-lg transition-colors duration-200 hover:text-indigo-600"
                            >
                                <ChevronLeftIcon class="mr-4 h-4 w-4" />
                                Retour aux articles
                            </Link>
                        </div>

                        <h1 class="mb-10 text-3xl font-bold lg:text-4xl">
                            {{ post.title }}
                        </h1>

                        <div
                            class="prose prose-slate space-y-4 text-justify leading-loose lg:prose-xl lg:text-lg"
                            v-html="post.body"
                        ></div>
                    </div>

                    <section class="col-span-8 col-start-5 mt-10 space-y-6">
                        <PostComment
                            v-for="comment in post.comments"
                            :key="comment.id"
                            :post="post"
                            :comment="comment"
                            :user="user"
                            :admin="admin"
                        />
                        <AddPostComment v-if="user" :post="post" :user="user" />
                        <p v-else class="text-sm text-gray-600">
                            <Link
                                class="font-semibold text-blue-600"
                                :href="route('login')"
                            >
                                Connectez vous
                            </Link>
                            pour ajouter un commentaire.
                        </p>
                    </section>
                    <div
                        class="mt-6 flex items-center justify-end text-sm lg:hidden"
                    >
                        <button
                            v-if="
                                (user && post.user_id === user.id) ||
                                (admin && admin === true)
                            "
                            class="mt-6 flex items-center space-x-3 rounded bg-red-500 p-2 hover:bg-red-600"
                            @click="deletePost()"
                            type="button"
                        >
                            <TrashIcon class="h-4 w-4 text-white" />
                            <span class="text-sm text-white"
                                >Supprimer l'article</span
                            >
                        </button>
                    </div>
                </article>
            </div>
        </template>
    </ResultLayout>
</template>
