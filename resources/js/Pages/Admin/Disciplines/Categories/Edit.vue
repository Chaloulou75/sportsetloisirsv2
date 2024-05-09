<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import NavAdminDiscipline from "@/Components/Admin/NavAdminDiscipline.vue";
import NavAdminDisciplineCategorie from "@/Components/Admin/NavAdminDisciplineCategorie.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import {
    XCircleIcon,
    PlusCircleIcon,
    ChevronLeftIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    discipline: Object,
    categories: Object,
    otherCategories: Object,
    user_can: Object,
});

const detachCategorie = (categorieIn) => {
    router.put(
        route("categories-disciplines.detach", {
            discipline: props.discipline,
        }),
        {
            _method: "PUT",
            categorieIn: categorieIn.categorie_id,
        },
        {
            preserveScroll: true,
        }
    );
};

const attachCategorie = (categorieNotIn) => {
    router.post(
        route("categories-disciplines.store", {
            discipline: props.discipline,
        }),
        {
            categorieNotIn: categorieNotIn.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const categorieForms = ref([]);

onMounted(() => {
    updateCategorieForms();
});

watch(
    () => props.categories,
    () => {
        updateCategorieForms();
    }
);

const updateCategorieForms = () => {
    categorieForms.value = props.categories.map((categorie) => {
        return useForm({
            nom_categorie_client: ref(categorie.nom_categorie_client ?? ""),
            nom_categorie_pro: ref(categorie.nom_categorie_pro ?? ""),
            remember: true,
        });
    });
};

const updateCategorie = (index) => {
    router.patch(
        route("categories-disciplines.update", {
            discipline: props.discipline,
        }),
        {
            nom_categorie_client:
                categorieForms.value[index].nom_categorie_client,
            nom_categorie_pro: categorieForms.value[index].nom_categorie_pro,
            id: props.categories[index].id,
        },
        {
            errorBag: "categorieForms",
            preserveScroll: true,
            onSuccess: () => {},
        }
    );
};
</script>
<template>
    <Head
        :title="`Administration de la discipline ${discipline.name}`"
        :description="`Administration de la discipline ${discipline.name}`"
    />
    <AdminLayout>
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    :href="route('admin.disciplines.edit', discipline)"
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-3 text-center text-base font-semibold text-gray-600 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Categories associées à la discipline
                    <span class="text-indigo-600">{{ discipline.name }}</span>
                </h1>
            </div>
        </template>
        <NavAdminDiscipline :discipline="discipline" />
        <NavAdminDisciplineCategorie
            :discipline="discipline"
            :categories="categories"
        />

        <div class="space-y-16 px-2 py-6 md:px-6">
            <div
                class="mx-auto flex flex-col items-center justify-center space-y-4 md:flex-row md:items-start md:justify-around md:space-y-0"
            >
                <div class="w-full md:w-2/3">
                    <h3
                        class="mb-4 text-center text-lg font-semibold text-slate-700"
                    >
                        Les catégories associées à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                        <span class="text-sm italic">
                            (retirer en cliquant sur la catégorie)</span
                        >
                    </h3>

                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="(categorieIn, index) in categories"
                            :key="categorieIn.id"
                            class="inline-flex space-x-4"
                        >
                            <button
                                type="button"
                                @click="detachCategorie(categorieIn)"
                                class="group inline-flex w-48 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                            >
                                {{ categorieIn.categorie.nom }}
                                <XCircleIcon
                                    class="ml-2 h-5 w-5 text-red-500 group-hover:text-white"
                                />
                            </button>
                            <template v-if="categorieForms[index]">
                                <form
                                    class="inline-flex flex-col space-y-2 md:flex-row md:space-x-4 md:space-y-0"
                                    @submit.prevent="updateCategorie(index)"
                                >
                                    <div>
                                        <label
                                            for="nom_categorie_client"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Nom client
                                            <span class="text-xs italic"></span>
                                        </label>
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="
                                                    categorieForms[index]
                                                        .nom_categorie_client
                                                "
                                                type="text"
                                                name="nom_categorie_client"
                                                id="nom_categorie_client"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="
                                                categorieForms[index].errors
                                                    .nom_categorie_client
                                            "
                                            class="text-xs text-red-500"
                                        >
                                            {{
                                                categorieForms[index].errors
                                                    .nom_categorie_client[0]
                                            }}
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            for="nom_categorie_pro"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Nom Professionnel
                                            <span class="text-xs italic"></span>
                                        </label>
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="
                                                    categorieForms[index]
                                                        .nom_categorie_pro
                                                "
                                                type="text"
                                                name="nom_categorie_pro"
                                                id="nom_categorie_pro"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="
                                                categorieForms[index].errors
                                                    .nom_categorie_pro
                                            "
                                            class="text-xs text-red-500"
                                        >
                                            {{
                                                categorieForms[index].errors
                                                    .nom_categorie_pro[0]
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="mx-auto flex w-full items-center justify-center md:w-auto"
                                    >
                                        <button
                                            type="submit"
                                            class="flex items-center justify-center rounded-md border border-gray-200 px-4 py-2 md:border-none"
                                        >
                                            <span class="mr-2 text-xs md:hidden"
                                                >Mettre à jour</span
                                            >
                                            <ArrowPathIcon
                                                class="mr-1 h-6 w-6 text-indigo-600 transition-all duration-200 hover:-rotate-90 hover:text-indigo-800"
                                            />
                                            <span class="sr-only"
                                                >Mettre à jour la
                                                catégorie</span
                                            >
                                        </button>
                                    </div>
                                </form>
                            </template>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h3
                        class="mb-4 text-center text-lg font-semibold text-slate-700"
                    >
                        Ajouter des catégories à
                        <span class="text-indigo-700">{{
                            discipline.name
                        }}</span>
                    </h3>
                    <ul class="flex flex-wrap justify-center gap-2">
                        <li
                            v-for="categorieNotIn in otherCategories"
                            :key="categorieNotIn.id"
                            class="group inline-flex space-x-4 self-stretch"
                        >
                            <button
                                type="button"
                                @click="attachCategorie(categorieNotIn)"
                                class="inline-flex w-48 items-center justify-center space-y-1 rounded border border-gray-600 px-4 py-3 text-center text-sm font-medium text-gray-600 shadow-sm focus:outline-none focus:ring active:bg-indigo-500 group-hover:border-gray-100 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-lg"
                            >
                                {{ categorieNotIn.nom }}
                                <PlusCircleIcon
                                    class="ml-2 h-5 w-5 text-blue-500 group-hover:text-white"
                                />
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
