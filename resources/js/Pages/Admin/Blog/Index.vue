<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import NavAdminBlog from "@/Components/Admin/NavAdminBlog.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PostFeaturedCard from "@/Components/Posts/PostFeaturedCard.vue";
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
import autoAnimate from "@formkit/auto-animate";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    posts: Object,
    tags: Object,
    filters: Object,
    user_can: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

let search = ref(props.filters.search);

const resetSearch = () => {
    search.value = "";
};

watch(
    search,
    debounce(function (value) {
        router.get(
            route("admin.blog.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const toAnimateOne = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
});
</script>
<template>
    <Head title="Gestion du blog">
        <meta
            head-key="description"
            name="description"
            content="Administration du blog."
        />
    </Head>
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion du blog
                </h1>
            </div>
        </template>
        <NavAdminBlog />

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-end">
                    <Link
                        v-if="user"
                        :href="route('posts.create')"
                        class="flex w-full max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                    >
                        Ecrire un article
                    </Link>
                </div>
                <!-- search box -->
                <div
                    class="mx-auto mb-8 mt-4 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row md:items-center"
                >
                    <label
                        for="search"
                        value="Rechercher un article"
                        class="mb-1 pr-2 text-sm font-medium text-gray-800"
                        >Rechercher un article:</label
                    >

                    <TextInput
                        id="search"
                        type="text"
                        class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                        v-model="search"
                        placeholder="mots clés..."
                    />

                    <button type="button" @click="resetSearch">
                        <svg
                            class="my-3 ml-2 h-6 w-6 text-gray-400 hover:text-gray-700 lg:my-0 lg:h-8 lg:w-8"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    ref="toAnimateOne"
                    v-if="posts.data"
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                >
                    <PostFeaturedCard
                        v-for="post in posts.data"
                        :key="post.id"
                        :post="post"
                    />
                </div>
                <div v-if="posts.data" class="flex justify-end p-10">
                    <Pagination :links="posts.links" :only="['posts']" />
                </div>
                <template v-else>
                    <div class="py-6 md:py-12">
                        <div
                            class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8"
                        >
                            <p class="font-medium text-gray-700">
                                Pas encore d'article. Revenez plus tard.
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
