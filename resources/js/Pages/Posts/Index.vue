<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, onMounted, watch, defineAsyncComponent } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PostFeaturedCard from "@/Components/Posts/PostFeaturedCard.vue";
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
import autoAnimate from "@formkit/auto-animate";
import { ArrowRightIcon } from "@heroicons/vue/24/outline";
const props = defineProps({
    posts: Object,
    filters: Object,
    familles: Object,
    listDisciplines: Object,
    discipline: Object,
    allCities: Object,
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
            route("posts.index"),
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
    <Head title="Blog" :description="'Blog de www.sports-et-loisirs.fr.'" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Blog </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex overflow-hidden text-gray-600 border border-gray-200 rounded-lg"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
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
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
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
            <div class="max-w-full px-2 mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-end">
                    <Link
                        v-if="user"
                        :href="route('posts.create')"
                        class="flex items-center text-sm text-gray-800 group hover:text-gray-900 md:text-base"
                    >
                        Ecrire un article
                        <ArrowRightIcon
                            class="w-5 ml-2 group-hover:text-gray-900"
                        />
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
                        placeholder="mots clés, tag, discipline..."
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
                    v-if="posts.data.length > 0"
                    class="grid h-auto grid-cols-1 gap-4 place-items-stretch sm:grid-cols-2 md:grid-cols-3"
                >
                    <PostFeaturedCard
                        v-for="post in posts.data"
                        :key="post.id"
                        :post="post"
                    />
                </div>
                <div v-if="posts.data.length > 0" class="flex justify-end p-10">
                    <Pagination :links="posts.links" />
                </div>
                <template v-else>
                    <div class="py-6 md:py-12">
                        <div
                            class="max-w-full min-h-full px-2 mx-auto sm:px-6 lg:px-8"
                        >
                            <p class="font-medium text-gray-700">
                                Pas encore d'article
                                <span v-if="discipline"
                                    >concernant la discipline
                                </span>
                                <span
                                    v-if="discipline"
                                    class="text-indigo-700"
                                    >{{ discipline.name }}</span
                                >. Revenez plus tard.
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </ResultLayout>
</template>
