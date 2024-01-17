<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, onMounted, watch, defineAsyncComponent } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PostFeaturedCard from "@/Components/Posts/PostFeaturedCard.vue";
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
import autoAnimate from "@formkit/auto-animate";
const props = defineProps({
    posts: Object,
    filters: Object,
    familles: Object,
    listDisciplines: Object,
    discipline: Object,
    allCities: Object,
});

const page = usePage();
const user = page.props.auth.user;

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

    <ResultLayout :list-disciplines="listDisciplines" :all-cities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title> Blog </template>
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
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
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
                        placeholder="mots clés, tag, discipline..."
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
                    v-if="posts.data.length > 0"
                    class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
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
                            class="mx-auto min-h-full max-w-full px-2 sm:px-6 lg:px-8"
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
