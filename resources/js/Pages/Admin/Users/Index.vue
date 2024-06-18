<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted, defineAsyncComponent } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import autoAnimate from "@formkit/auto-animate";
import { debounce } from "lodash";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import {
    ChevronLeftIcon,
    CheckIcon,
    ChevronUpDownIcon,
} from "@heroicons/vue/24/outline";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    errors: Object,
    users: Object,
    roles: Object,
    filters: Object,
    user_can: Object,
});

let search = ref(props.filters.search);

const resetSearch = () => {
    search.value = "";
};

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
    }, 500)
);

const showCreateRoleForm = ref(false);
const toggleCreateRoleForm = () => {
    showCreateRoleForm.value = !showCreateRoleForm.value;
};

const createRoleForm = useForm({
    name: null,
    description: null,
});

const createRole = () => {
    createRoleForm.post(route("admin.roles.store"), {
        errorBag: "createRoleForm",
        preserveScroll: true,
        only: ["users", "roles"],
        onSuccess: () => {
            createRoleForm.reset();
            toggleCreateRoleForm();
        },
    });
};

const currentRole = ref(null);
const showUpdateRoleForm = ref(false);
const updateRoleForm = useForm({
    name: null,
    description: null,
});

const openUpdateRoleForm = (role) => {
    showUpdateRoleForm.value = true;
    currentRole.value = role;
    if (role) {
        updateRoleForm.name = currentRole.value.name;
        updateRoleForm.description = currentRole.value.description;
    }
};

const cancelUpdateRoleForm = () => {
    showUpdateRoleForm.value = false;
    updateRoleForm.clearErrors();
    currentRole.value = null;
};

const updateRole = (role) => {
    updateRoleForm.patch(
        route("admin.roles.update", {
            role: role,
        }),
        {
            errorBag: "updateRoleForm",
            preserveScroll: true,
            only: ["users", "roles"],
            onSuccess: () => {
                updateRoleForm.reset();
                openUpdateRoleForm(null);
            },
        }
    );
};

const deleteRole = (role) => {
    router.delete(
        route("admin.roles.destroy", {
            role: role,
        }),
        {
            only: ["users", "roles"],
            preserveScroll: true,
        }
    );
};

const selectedUser = ref(props.users.data[0] ?? null);
const query = ref("");

const filteredUsers = computed(() =>
    query.value === ""
        ? props.users.data
        : props.users.data.filter((user) => {
              return user.name
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(query.value.toLowerCase().replace(/\s+/g, ""));
          })
);

const assignRoleForm = useForm({
    user: selectedUser.value.id,
    role: null,
});

const assignRole = () => {
    assignRoleForm.post(route("admin.role_user.store"), {
        errorBag: "assignRoleForm",
        preserveScroll: true,
        onSuccess: () => assignRoleForm.reset(),
    });
};

const cancelAssignment = () => {
    assignRoleForm.clearErrors();
    assignRoleForm.reset();
};

const detachRoleForm = useForm({
    user: null,
    role: null,
});

const detachRole = () => {
    detachRoleForm.delete(route("admin.role_user.destroy"), {
        errorBag: "detachRoleForm",
        preserveScroll: true,
        onSuccess: () => detachRoleForm.reset(),
    });
};

const keepAssignment = () => {
    detachRoleForm.clearErrors();
    detachRoleForm.reset();
};

const toAnimateOne = ref();
const toAnimateTwo = ref();
onMounted(() => {
    if (toAnimateOne.value) {
        autoAnimate(toAnimateOne.value);
    }
    if (toAnimateTwo.value) {
        autoAnimate(toAnimateTwo.value);
    }
});
</script>
<template>
    <Head title="Gestion des utilisateurs">
        <meta
            head-key="description"
            name="description"
            :content="`Administration du site. Gestion des utilisateurs.`"
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
                    Gestion des utilisateurs
                </h1>
            </div>
        </template>

        <div class="w-full space-y-16 px-2 py-6 text-slate-700 md:px-6">
            <h2
                class="text-center text-xl text-slate-700 underline decoration-indigo-600 decoration-4 underline-offset-4 md:text-2xl"
            >
                Gestion des utilisateurs et des rôles.
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
                            Gestion des utilisateurs existants:
                        </h3>
                        <div
                            v-if="users.data.length > 0"
                            ref="toAnimateOne"
                            class="mx-auto grid h-auto grid-cols-1 place-content-center place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <Link
                                v-for="user in users.data"
                                :key="user.id"
                                class="relative flex w-full flex-col items-center justify-center rounded-lg bg-white px-6 py-4 text-center text-sm font-medium text-gray-700 shadow-md ring-1 ring-gray-300 transition-all duration-200 ease-in-out hover:text-gray-900 hover:shadow-lg hover:ring-gray-400 focus:outline-none"
                                :href="route('profile.edit', user)"
                            >
                                <div
                                    v-if="user.customer"
                                    class="absolute -top-3 rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800"
                                >
                                    Client
                                </div>
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
                                        class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-700"
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
                                                class="rounded-full bg-blue-100 px-2 py-1 text-xs uppercase text-blue-800"
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
                                        class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-700"
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
                <div
                    class="mt-8 flex w-full flex-col items-start justify-center space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-2/3">
                        <h3
                            class="mb-8 text-center text-lg font-semibold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Gérer les rôles existants:
                        </h3>
                        <div
                            v-if="roles.length > 0"
                            ref="toAnimateTwo"
                            class="mx-auto grid h-auto grid-cols-1 place-content-center place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <div
                                v-for="role in roles"
                                :key="role.id"
                                class="relative flex w-full flex-col items-center justify-center rounded-lg bg-white px-6 py-4 text-center text-sm font-medium text-gray-700 shadow-md ring-1 ring-gray-300 transition-all duration-200 ease-in-out hover:text-gray-900 hover:shadow-lg hover:ring-gray-400 focus:outline-none"
                            >
                                <button
                                    type="button"
                                    @click.prevent="openUpdateRoleForm(role)"
                                    class="text-lg font-semibold text-gray-800 hover:text-indigo-800 hover:underline"
                                >
                                    {{ role.name }}
                                </button>
                                <div class="text-xs text-gray-600">
                                    {{ role.description }}
                                </div>
                                <div class="my-1 text-xs text-gray-500">
                                    {{ role.users_count }}
                                    <span v-if="role.users_count > 1"
                                        >utilisateurs</span
                                    ><span v-else>utilisateur</span>
                                </div>
                                <button
                                    type="button"
                                    @click.prevent="deleteRole(role)"
                                    class="mt-2.5 flex items-center justify-center rounded-full border border-gray-200 bg-red-100 px-3 py-1 text-sm text-red-700 hover:bg-red-200 hover:text-red-900"
                                >
                                    supprimer
                                </button>
                            </div>
                        </div>
                        <form
                            v-if="showUpdateRoleForm"
                            class="mt-6 flex w-full max-w-md flex-col items-start space-y-4"
                            @submit.prevent="updateRole(currentRole)"
                        >
                            <div class="mt-1 flex w-full flex-col rounded-md">
                                <p class="my-1 text-xs italic text-red-600">
                                    Il est vivement déconseillé de changer ou
                                    supprimer le nom d'un rôle.
                                </p>
                                <InputLabel for="role_name" value="Nom:" />
                                <TextInput
                                    v-model="updateRoleForm.name"
                                    type="text"
                                    name="role_name"
                                    id="role_name"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <InputError
                                    v-if="errors.updateRoleForm"
                                    class="mt-1"
                                    :message="errors.updateRoleForm.name"
                                />
                            </div>
                            <div class="mt-1 flex w-full flex-col rounded-md">
                                <InputLabel
                                    for="role_description"
                                    value="Description:"
                                />
                                <textarea
                                    v-model="updateRoleForm.description"
                                    name="role_description"
                                    id="role_description"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                ></textarea>
                                <InputError
                                    v-if="errors.updateRoleForm"
                                    class="mt-1"
                                    :message="errors.updateRoleForm.description"
                                />
                            </div>

                            <div
                                class="flex w-full items-center justify-between"
                            >
                                <button
                                    :disabled="updateRoleForm.processing"
                                    :class="{
                                        'opacity-25': updateRoleForm.processing,
                                    }"
                                    class="flex w-auto max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                                    type="submit"
                                >
                                    <LoadingSVG
                                        v-if="updateRoleForm.processing"
                                    />
                                    Enregistrer
                                </button>
                                <button
                                    class="rounded border border-gray-300 bg-white px-4 py-2 text-center text-base font-medium text-gray-600 shadow-sm"
                                    type="button"
                                    @click="cancelUpdateRoleForm"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                    <div
                        class="flex w-full flex-col items-center justify-center md:w-1/3"
                    >
                        <h3
                            class="mb-8 w-full text-center text-lg font-bold text-slate-700 underline decoration-sky-600 decoration-2 underline-offset-2"
                        >
                            Créer un rôle:
                        </h3>
                        <button
                            type="button"
                            v-if="!showCreateRoleForm"
                            @click="toggleCreateRoleForm"
                            class="inline-flex w-auto items-center justify-center space-y-1 rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-medium text-gray-700 shadow-md transition-all duration-200 ease-in-out hover:border-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 active:bg-indigo-600"
                        >
                            Créer un nouveau rôle
                        </button>
                        <form
                            v-if="showCreateRoleForm"
                            class="flex w-full flex-col items-start space-y-4 px-4"
                            @submit.prevent="createRole"
                        >
                            <div class="mt-1 flex w-full flex-col rounded-md">
                                <InputLabel for="role_name" value="Nom:" />
                                <TextInput
                                    v-model="createRoleForm.name"
                                    type="text"
                                    name="role_name"
                                    id="role_name"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                                <InputError
                                    v-if="errors.createRoleForm"
                                    class="mt-1"
                                    :message="errors.createRoleForm.name"
                                />
                            </div>
                            <div class="mt-1 flex w-full flex-col rounded-md">
                                <InputLabel
                                    for="role_description"
                                    value="Description:"
                                />
                                <textarea
                                    v-model="createRoleForm.description"
                                    name="role_description"
                                    id="role_description"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                ></textarea>
                                <InputError
                                    v-if="errors.createRoleForm"
                                    class="mt-1"
                                    :message="errors.createRoleForm.description"
                                />
                            </div>

                            <div
                                class="flex w-full items-center justify-between"
                            >
                                <button
                                    :disabled="createRoleForm.processing"
                                    :class="{
                                        'opacity-25': createRoleForm.processing,
                                    }"
                                    class="flex w-auto max-w-xs items-center justify-center rounded-md border border-gray-200 bg-indigo-800 px-4 py-2 text-base text-white shadow hover:bg-indigo-900"
                                    type="submit"
                                >
                                    <LoadingSVG
                                        v-if="createRoleForm.processing"
                                    />
                                    Enregistrer
                                </button>
                                <button
                                    class="rounded border border-gray-300 bg-white px-4 py-2 text-center text-base font-medium text-gray-600 shadow-sm"
                                    type="button"
                                    @click="toggleCreateRoleForm"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div
                    class="mt-8 flex w-full flex-col items-start justify-evenly space-y-4 py-4 md:flex-row md:justify-around md:space-y-0"
                >
                    <div class="w-full md:w-1/2">
                        <div
                            class="mx-auto mt-10 max-w-lg rounded-lg bg-white p-6 shadow-md"
                        >
                            <h4 class="mb-4 text-base font-semibold">
                                Assigner un rôle:
                            </h4>
                            <form @submit.prevent="assignRole">
                                <div class="mb-4">
                                    <label
                                        for="user"
                                        class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                        >Selectionner un utilisateur</label
                                    >
                                    <select
                                        v-model="assignRoleForm.user"
                                        id="user"
                                        name="user"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                        <option value="" disabled>
                                            Selectionner un utilisateur
                                        </option>
                                        <option
                                            v-for="user in users.data"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        v-if="errors.assignRoleForm"
                                        class="mt-1"
                                        :message="errors.assignRoleForm.user"
                                    />
                                </div>
                                <!-- <div class="mb-4">
                                    <Combobox v-model="selectedUser">
                                        <div class="relative mt-1">
                                            <div
                                                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
                                            >
                                                <ComboboxInput
                                                    class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                                                    :displayValue="
                                                        (user) => user.name
                                                    "
                                                    @change="
                                                        query =
                                                            $event.target.value
                                                    "
                                                />
                                                <ComboboxButton
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                                                >
                                                    <ChevronUpDownIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </ComboboxButton>
                                            </div>
                                            <TransitionRoot
                                                leave="transition ease-in duration-100"
                                                leaveFrom="opacity-100"
                                                leaveTo="opacity-0"
                                                @after-leave="query = ''"
                                            >
                                                <ComboboxOptions
                                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                                                >
                                                    <div
                                                        v-if="
                                                            filteredUsers.length ===
                                                                0 &&
                                                            query !== ''
                                                        "
                                                        class="relative cursor-default select-none px-4 py-2 text-gray-700"
                                                    >
                                                        Pas de résultat.
                                                    </div>

                                                    <ComboboxOption
                                                        v-for="user in filteredUsers"
                                                        as="template"
                                                        :key="user.id"
                                                        :value="user"
                                                        v-slot="{
                                                            selected,
                                                            active,
                                                        }"
                                                    >
                                                        <li
                                                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                            :class="{
                                                                'bg-teal-600 text-white':
                                                                    active,
                                                                'text-gray-900':
                                                                    !active,
                                                            }"
                                                        >
                                                            <span
                                                                class="block truncate text-xs"
                                                                :class="{
                                                                    'font-medium':
                                                                        selected,
                                                                    'font-normal':
                                                                        !selected,
                                                                }"
                                                            >
                                                                {{ user.name }}
                                                                ({{
                                                                    user.email
                                                                }})
                                                            </span>
                                                            <span
                                                                v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                                :class="{
                                                                    'text-white':
                                                                        active,
                                                                    'text-teal-600':
                                                                        !active,
                                                                }"
                                                            >
                                                                <CheckIcon
                                                                    class="h-5 w-5"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </li>
                                                    </ComboboxOption>
                                                </ComboboxOptions>
                                            </TransitionRoot>
                                        </div>
                                    </Combobox>
                                    <InputError
                                        v-if="errors.assignRoleForm"
                                        class="mt-1"
                                        :message="errors.assignRoleForm.user"
                                    />
                                </div> -->

                                <div class="mb-4">
                                    <label
                                        for="role"
                                        class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                        >Selectionner un rôle</label
                                    >
                                    <select
                                        v-model="assignRoleForm.role"
                                        id="role"
                                        name="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                        <option value="" disabled>
                                            Selectionner un rôle
                                        </option>
                                        <option
                                            v-for="role in roles"
                                            :key="role.id"
                                            :value="role.id"
                                        >
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        v-if="errors.assignRoleForm"
                                        class="mt-1"
                                        :message="errors.assignRoleForm.role"
                                    />
                                </div>

                                <div class="flex items-center justify-between">
                                    <button
                                        :disabled="assignRoleForm.processing"
                                        :class="{
                                            'opacity-50':
                                                assignRoleForm.processing,
                                        }"
                                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-700 focus:border-gray-800 focus:outline-none focus:ring active:bg-gray-800 disabled:opacity-25"
                                    >
                                        <LoadingSVG
                                            v-if="assignRoleForm.processing"
                                        />
                                        Assigner le rôle
                                    </button>
                                    <button
                                        type="button"
                                        @click="cancelAssignment"
                                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <div
                            class="mx-auto mt-10 max-w-lg rounded-lg bg-white p-6 shadow-md"
                        >
                            <h4 class="mb-4 text-base font-semibold">
                                Détacher un rôle:
                            </h4>
                            <form @submit.prevent="detachRole">
                                <div class="mb-4">
                                    <label
                                        for="user"
                                        class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                        >Selectionner un utilisateur</label
                                    >
                                    <select
                                        v-model="detachRoleForm.user"
                                        id="user"
                                        name="user"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                        <option value="" disabled>
                                            Selectionner un utilisateur
                                        </option>
                                        <option
                                            v-for="user in users.data"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        v-if="errors.detachRoleForm"
                                        class="mt-1"
                                        :message="errors.detachRoleForm.user"
                                    />
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="role"
                                        class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                        >Selectionner un rôle</label
                                    >
                                    <select
                                        v-model="detachRoleForm.role"
                                        id="role"
                                        name="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                        <option value="" disabled>
                                            Selectionner un rôle
                                        </option>
                                        <option
                                            v-for="role in roles"
                                            :key="role.id"
                                            :value="role.id"
                                        >
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        v-if="errors.detachRoleForm"
                                        class="mt-1"
                                        :message="errors.detachRoleForm.role"
                                    />
                                </div>

                                <div class="flex items-center justify-between">
                                    <button
                                        :disabled="detachRoleForm.processing"
                                        :class="{
                                            'opacity-50':
                                                detachRoleForm.processing,
                                        }"
                                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-700 focus:border-gray-800 focus:outline-none focus:ring active:bg-gray-800 disabled:opacity-25"
                                    >
                                        <LoadingSVG
                                            v-if="detachRoleForm.processing"
                                        />
                                        Détacher le rôle
                                    </button>
                                    <button
                                        type="button"
                                        @click="keepAssignment"
                                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
