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
    <Head title="Gestion du blog" :description="'Administration du blog.'" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-start h-full">
                <Link
                    :href="route('admin.index')"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-base font-semibold text-center text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Gestion du blog
                </h1>
            </div>
        </template>
        <NavAdminBlog />

        <div class="py-6 md:py-12">
            <div class="max-w-full px-2 mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-end">
                    <Link
                        v-if="user"
                        :href="route('posts.create')"
                        class="flex items-center justify-center w-full max-w-xs px-4 py-2 text-base text-white bg-indigo-800 border border-gray-200 rounded-md shadow hover:bg-indigo-900"
                    >
                        Ecrire un article
                    </Link>
                </div>
                <!-- search box -->
                <div
                    class="flex flex-col items-center justify-center w-full max-w-3xl px-2 mx-auto mt-4 mb-8 md:flex-row md:items-center"
                >
                    <label
                        for="search"
                        value="Rechercher un article"
                        class="pr-2 mb-1 text-sm font-medium text-gray-800"
                        >Rechercher un article:</label
                    >

                    <TextInput
                        id="search"
                        type="text"
                        class="flex-1 block w-full px-2 mt-1 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                        v-model="search"
                        placeholder="mots clés..."
                    />

                    <button type="button" @click="resetSearch">
                        <svg
                            class="w-6 h-6 my-3 ml-2 text-gray-400 hover:text-gray-700 lg:my-0 lg:h-8 lg:w-8"
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
                    class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-3"
                >
                    <PostFeaturedCard
                        v-for="post in posts.data"
                        :key="post.id"
                        :post="post"
                    />
                </div>
                <div v-if="posts.data" class="flex justify-end p-10">
                    <Pagination :links="posts.links" />
                </div>
                <template v-else>
                    <div class="py-6 md:py-12">
                        <div
                            class="max-w-full min-h-screen px-2 mx-auto sm:px-6 lg:px-8"
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
