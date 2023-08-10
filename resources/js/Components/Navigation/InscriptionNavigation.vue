<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { UserCircleIcon } from "@heroicons/vue/24/outline";
import ModalDeleteStructure from "@/Components/Modals/ModalDeleteStructure.vue";

const emit = defineEmits(["eventFromChild"]);

const emitEvent = (message) => {
    emit("eventFromChild", message);
};

const props = defineProps({
    structure: Object,
    categorie: Object,
    displayActivity: Boolean,
    displayTarif: Boolean,
    displayPlanning: Boolean,
    can: Object,
});

const showModal = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
<template>
    <div
        class="flex min-h-screen w-full flex-col justify-between border-e bg-white md:w-1/5"
    >
        <div class="sticky inset-x-0 top-0 px-4 py-2">
            <ul class="space-y-1">
                <li>
                    <Link
                        v-if="user && !user.structures.length > 0"
                        :href="route('structures.create')"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700"
                        :class="{
                            'bg-blue-600 text-white': route().current(
                                'structures.create',
                                structure
                            ),
                        }"
                    >
                        Inscription
                    </Link>
                </li>
                <li v-if="structure">
                    <a
                        href=""
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        Ranking Score
                    </a>
                </li>
                <li v-if="structure">
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                        >
                            <span class="text-sm font-medium">
                                Gestion des activités
                            </span>

                            <span
                                class="shrink-0 transition duration-300 group-open:-rotate-180"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </summary>

                        <ul class="mt-2 space-y-1 px-4">
                            <li>
                                <Link
                                    :href="
                                        route(
                                            'structures.activites.index',
                                            structure
                                        )
                                    "
                                    v-if="can.update"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'structures.activites.index',
                                                structure
                                            ),
                                    }"
                                >
                                    Ajouter des activités
                                </Link>
                            </li>

                            <li
                                v-if="
                                    route().current('structures.activites.edit')
                                "
                            >
                                <button
                                    type="button"
                                    @click="emitEvent('Mes activites')"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            displayActivity,
                                    }"
                                >
                                    Mes activités
                                </button>
                            </li>
                            <li
                                v-if="
                                    route().current('structures.activites.edit')
                                "
                            >
                                <button
                                    type="button"
                                    @click="emitEvent('Mes tarifs')"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white': displayTarif,
                                    }"
                                >
                                    Mes Tarifs
                                </button>
                            </li>
                            <li
                                v-if="
                                    route().current('structures.activites.edit')
                                "
                            >
                                <button
                                    type="button"
                                    @click="emitEvent('Mon planning')"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            displayPlanning,
                                    }"
                                >
                                    Mes Horaires
                                </button>
                            </li>
                        </ul>
                    </details>
                </li>

                <li>
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                        >
                            <span class="text-sm font-medium">
                                Gestion de ma structure
                            </span>

                            <span
                                class="shrink-0 transition duration-300 group-open:-rotate-180"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </summary>

                        <ul class="mt-2 space-y-1 px-4">
                            <li v-if="structure">
                                <Link
                                    v-if="
                                        user &&
                                        user.structures.length > 0 &&
                                        can.update
                                    "
                                    :href="route('structures.edit', structure)"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'structures.edit',
                                                structure
                                            ),
                                    }"
                                >
                                    Editer ma structure
                                </Link>
                            </li>
                            <li>
                                <Link
                                    v-if="user && !user.structures.length > 0"
                                    :href="route('structures.create')"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'structures.create',
                                                structure
                                            ),
                                    }"
                                >
                                    Créer une structure
                                </Link>
                            </li>
                            <li v-if="structure">
                                <button
                                    v-if="can.delete"
                                    @click="showModal = true"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                >
                                    Supprimer cette structure
                                </button>
                                <ModalDeleteStructure
                                    :structure="structure"
                                    :show="showModal"
                                    @close="showModal = false"
                                />
                            </li>
                        </ul>
                    </details>
                </li>

                <li v-if="structure">
                    <Link
                        :href="
                            route('structures.show', {
                                structure: structure.slug,
                            })
                        "
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        Voir ma structure
                    </Link>
                </li>
            </ul>
        </div>

        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100">
            <Link
                :href="route('profile.edit')"
                class="hidden items-center gap-2 bg-white p-4 hover:bg-gray-50 md:flex"
            >
                <!-- <img
                    alt="Man"
                    src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1770&q=80"
                    class="h-10 w-10 rounded-full object-cover"
                /> -->
                <UserCircleIcon class="mr-2 h-8 w-8" />

                <div>
                    <p class="text-xs">
                        <strong class="block font-medium">{{
                            user.name
                        }}</strong>

                        <span> {{ user.email }}</span>
                    </p>
                </div>
            </Link>
        </div>
    </div>
</template>
