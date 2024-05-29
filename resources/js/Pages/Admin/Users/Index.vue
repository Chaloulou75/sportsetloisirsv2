<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/Forms/TextInput.vue";
import { debounce } from "lodash";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    errors: Object,
    users: Object,
    filters: Object,
    user_can: Object,
});

let search = ref(props.filters.search);

function resetSearch() {
    search.value = "";
}

watch(
    search,
    debounce(function (value) {
        router.get(
            route("admin.users.index"),
            { search: value },
            {
                preserveState: true,
                replace: true,
                only: ["users"],
            }
        );
    }, 300)
);
</script>
<template>
    <Head
        title="Gestion des utilisateurs"
        :description="'Administration du site. Gestion des utilisateurs'"
    />
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
                    Gestion des utilisateurs (To Do!)
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des utilisateurs
            </h2>
            <div>
                <!-- search box -->
                <div
                    class="mx-auto mb-8 mt-4 flex w-full max-w-3xl flex-col items-center justify-center px-2 md:flex-row"
                >
                    <label
                        for="search"
                        value="Rechercher un utilisateur"
                        class="mb-1 pr-2 text-sm font-medium text-gray-800"
                        >Rechercher un utilisateur:</label
                    >

                    <TextInput
                        id="search"
                        type="text"
                        class="mt-1 block w-full flex-1 px-2 placeholder-gray-500 placeholder-opacity-50 focus:ring-2 focus:ring-midnight"
                        v-model="search"
                        placeholder="nom, email..."
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
                    class="flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-8 text-center text-lg font-semibold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les utilisateurs existants:
                        </h3>
                        <div
                            class="mx-auto grid h-auto grid-cols-1 place-content-center place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <Link
                                v-for="user in users.data"
                                :key="user.id"
                                class="flex w-full flex-col items-center justify-center rounded-lg bg-white px-6 py-4 text-center text-sm font-medium text-gray-700 shadow-md ring-1 ring-gray-300 transition-all duration-200 ease-in-out hover:text-gray-900 hover:shadow-lg hover:ring-gray-400 focus:outline-none"
                                :href="route('profile.edit', user)"
                            >
                                <div
                                    class="text-lg font-semibold text-gray-800"
                                >
                                    {{ user.name }}
                                </div>
                                <div class="text-xs text-gray-600">
                                    {{ user.email }}
                                </div>

                                <div
                                    v-if="user.roles.length > 0"
                                    class="mx-auto mt-4 w-full"
                                >
                                    <div
                                        class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-700"
                                    >
                                        Roles
                                    </div>
                                    <div
                                        class="flex flex-wrap justify-center gap-1"
                                    >
                                        <template
                                            v-for="role in user.roles"
                                            :key="role.id"
                                        >
                                            <div
                                                v-if="role"
                                                class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800"
                                            >
                                                {{ role.name }}
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div
                                    v-if="user.structures.length > 0"
                                    class="mx-auto mt-4 w-full"
                                >
                                    <div
                                        class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-700"
                                    >
                                        Structures
                                    </div>
                                    <div
                                        class="flex flex-wrap justify-center gap-1"
                                    >
                                        <template
                                            v-for="structure in user.structures"
                                            :key="structure.id"
                                        >
                                            <div
                                                v-if="structure"
                                                class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800"
                                            >
                                                {{ structure.name }}
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </Link>

                            <Pagination
                                :links="users.links"
                                :only="['users']"
                            />
                        </div>
                    </div>

                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-8 text-center text-lg font-semibold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un utilisateur:
                        </h3>
                        <Link
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-md transition-all duration-200 ease-in-out hover:border-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 active:bg-indigo-600"
                            :href="route('register')"
                        >
                            Créer un nouvel utilisateur
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
