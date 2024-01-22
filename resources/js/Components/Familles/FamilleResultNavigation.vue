<script setup>
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    familles: Object,
});

const hoveredFamille = ref(null);
const hoveredFamilleDisciplines = computed(() => {
    if (hoveredFamille.value) {
        return hoveredFamille.value.disciplines;
    }
    return [];
});
</script>
<template>
    <header
        @mouseleave="hoveredFamille = null"
        class="z-50 flex w-full flex-wrap bg-white py-3 text-sm md:flex-nowrap md:justify-start md:py-0 dark:bg-gray-800"
    >
        <nav
            class="mx-auto w-full max-w-[85rem] px-4 md:px-6 lg:px-8"
            aria-label="Global"
        >
            <div class="relative md:flex md:items-center md:justify-center">
                <div class="flex items-center justify-end">
                    <div class="md:hidden">
                        <button
                            type="button"
                            class="hs-collapse-toggle flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-gray-800 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-collapse="#navbar-collapse-with-animation"
                            aria-controls="navbar-collapse-with-animation"
                            aria-label="Toggle navigation"
                        >
                            <svg
                                class="h-4 w-4 flex-shrink-0 hs-collapse-open:hidden"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <line x1="3" x2="21" y1="6" y2="6" />
                                <line x1="3" x2="21" y1="12" y2="12" />
                                <line x1="3" x2="21" y1="18" y2="18" />
                            </svg>
                            <svg
                                class="hidden h-4 w-4 flex-shrink-0 hs-collapse-open:block"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    id="navbar-collapse-with-animation"
                    class="hs-collapse hidden grow basis-full overflow-hidden transition-all duration-300 md:block"
                >
                    <div
                        class="max-h-[75vh] overflow-hidden overflow-y-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-slate-700 [&::-webkit-scrollbar]:w-2"
                    >
                        <div
                            class="mt-5 flex flex-col gap-x-0 divide-y divide-dashed divide-gray-200 md:mt-0 md:flex-row md:items-center md:justify-center md:gap-x-7 md:divide-y-0 md:divide-solid md:ps-7 dark:divide-gray-700"
                        >
                            <div
                                v-for="famille in familles"
                                :key="famille.id"
                                class="hs-dropdown py-3 [--adaptive:none] [--strategy:static] md:py-4 md:[--strategy:absolute] md:[--trigger:hover]"
                            >
                                <button
                                    @mouseenter="hoveredFamille = famille"
                                    type="button"
                                    class="flex w-full items-center font-medium text-gray-500 hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                >
                                    {{ famille.name }}
                                    <svg
                                        class="ms-2 h-4 w-4 flex-shrink-0"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div
                                    class="hs-dropdown-menu start-0 top-full z-[1200] hidden w-full min-w-[15rem] rounded-lg bg-white py-2 opacity-0 transition-[opacity,margin] duration-[0.1ms] before:absolute before:-top-5 before:start-0 before:h-5 before:w-full hs-dropdown-open:opacity-100 md:p-4 md:shadow-2xl md:duration-[150ms] dark:divide-gray-700 dark:bg-gray-800"
                                >
                                    <span
                                        class="mb-2 text-xs font-semibold uppercase text-gray-800 dark:text-gray-200"
                                        >{{ famille.name }}</span
                                    >
                                    <div
                                        class="gap-4 md:grid md:grid-cols-2 lg:grid-cols-3"
                                    >
                                        <div class="mx-1 flex flex-col md:mx-0">
                                            <Link
                                                :href="
                                                    route('disciplines.show', {
                                                        discipline: discipline,
                                                    })
                                                "
                                                preserve-scroll
                                                v-for="discipline in hoveredFamilleDisciplines"
                                                :key="discipline.id"
                                                class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                            >
                                                <svg
                                                    class="mt-1 h-5 w-5 flex-shrink-0"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"
                                                    />
                                                    <path
                                                        d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"
                                                    />
                                                </svg>
                                                <div class="grow">
                                                    <p
                                                        class="font-medium text-gray-800 dark:text-gray-200"
                                                    >
                                                        {{ discipline.name }}
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-500 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                                                    >
                                                        Explore advice and
                                                        explanations for all of
                                                        Preline's features.
                                                    </p>
                                                </div>
                                            </Link>
                                        </div>

                                        <div class="mx-1 flex flex-col md:mx-0">
                                            <a
                                                class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="#"
                                            >
                                                <svg
                                                    class="mt-1 h-5 w-5 flex-shrink-0"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <circle
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                    />
                                                    <path
                                                        d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"
                                                    />
                                                    <path d="M12 17h.01" />
                                                </svg>
                                                <div class="grow">
                                                    <p
                                                        class="font-medium text-gray-800 dark:text-gray-200"
                                                    >
                                                        Help Center
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-500 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                                                    >
                                                        Learn how to install,
                                                        set up, and use Preline.
                                                    </p>
                                                </div>
                                            </a>

                                            <a
                                                class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="#"
                                            >
                                                <svg
                                                    class="mt-1 h-5 w-5 flex-shrink-0"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <circle
                                                        cx="12"
                                                        cy="12"
                                                        r="4"
                                                    />
                                                    <path
                                                        d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"
                                                    />
                                                </svg>
                                                <div class="grow">
                                                    <p
                                                        class="font-medium text-gray-800 dark:text-gray-200"
                                                    >
                                                        Developer Hub
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-500 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                                                    >
                                                        Learn how to integrate
                                                        or build on top of
                                                        Preline.
                                                    </p>
                                                </div>
                                            </a>

                                            <a
                                                class="group flex gap-x-5 rounded-lg p-4 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="#"
                                            >
                                                <svg
                                                    class="mt-1 h-5 w-5 flex-shrink-0"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                    />
                                                    <circle
                                                        cx="9"
                                                        cy="7"
                                                        r="4"
                                                    />
                                                    <path
                                                        d="M22 21v-2a4 4 0 0 0-3-3.87"
                                                    />
                                                    <path
                                                        d="M16 3.13a4 4 0 0 1 0 7.75"
                                                    />
                                                </svg>
                                                <div class="grow">
                                                    <p
                                                        class="font-medium text-gray-800 dark:text-gray-200"
                                                    >
                                                        Community Forum
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-500 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                                                    >
                                                        Learn, share, and
                                                        connect with other
                                                        Preline users.
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->
</template>
