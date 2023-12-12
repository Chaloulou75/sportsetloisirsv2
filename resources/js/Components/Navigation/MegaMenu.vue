<script setup></script>

<template>
    <nav class="border-gray-200 bg-white dark:bg-gray-900">
        <div
            class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between p-4"
        >
            <a
                href="https://flowbite.com"
                class="flex items-center space-x-3 rtl:space-x-reverse"
            >
                <img
                    src="https://flowbite.com/docs/images/logo.svg"
                    class="h-8"
                    alt="Flowbite Logo"
                />
                <span
                    class="self-center whitespace-nowrap text-2xl font-semibold dark:text-white"
                    >Flowbite</span
                >
            </a>
            <div
                class="flex items-center space-x-1 rtl:space-x-reverse md:order-2 md:space-x-2"
            >
                <a
                    href="#"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-800 md:px-5 md:py-2.5"
                    >Login</a
                >
                <a
                    href="#"
                    class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 md:px-5 md:py-2.5"
                    >Sign up</a
                >
                <button
                    data-collapse-toggle="mega-menu"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 md:hidden"
                    aria-controls="mega-menu"
                    aria-expanded="false"
                >
                    <span class="sr-only">Open main menu</span>
                    <svg
                        class="h-5 w-5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 17 14"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15"
                        />
                    </svg>
                </button>
            </div>
            <div
                id="mega-menu"
                class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto"
            >
                <ul
                    class="mt-4 flex flex-col font-medium rtl:space-x-reverse md:mt-0 md:flex-row md:space-x-8"
                >
                    <li>
                        <a
                            href="#"
                            class="block border-b border-gray-100 px-3 py-2 text-blue-600 hover:bg-gray-50 dark:border-gray-700 dark:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-blue-600 md:dark:hover:bg-transparent md:dark:hover:text-blue-500"
                            aria-current="page"
                            >Home</a
                        >
                    </li>
                    <li>
                        <button
                            id="mega-menu-dropdown-button"
                            data-dropdown-toggle="mega-menu-dropdown"
                            class="flex w-full items-center justify-between border-b border-gray-100 px-3 py-2 font-medium text-gray-900 hover:bg-gray-50 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-blue-500 md:w-auto md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-blue-600 md:dark:hover:bg-transparent md:dark:hover:text-blue-500"
                        >
                            Company
                            <svg
                                class="ms-3 h-2.5 w-2.5"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 10 6"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m1 1 4 4 4-4"
                                />
                            </svg>
                        </button>
                        <div
                            id="mega-menu-dropdown"
                            class="absolute z-10 grid w-auto grid-cols-2 rounded-lg border border-gray-100 bg-white text-sm shadow-md dark:border-gray-700 dark:bg-gray-700 md:grid-cols-3"
                        >
                            <div
                                class="p-4 pb-0 text-gray-900 dark:text-white md:pb-4"
                            >
                                <ul
                                    class="space-y-4"
                                    aria-labelledby="mega-menu-dropdown-button"
                                >
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            About Us
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Library
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Resources
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Pro Version
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div
                                class="p-4 pb-0 text-gray-900 dark:text-white md:pb-4"
                            >
                                <ul class="space-y-4">
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Newsletter
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Playground
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            License
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="p-4">
                                <ul class="space-y-4">
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Contact Us
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Support Center
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500"
                                        >
                                            Terms
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a
                            href="#"
                            class="block border-b border-gray-100 px-3 py-2 text-gray-900 hover:bg-gray-50 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-blue-500 md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-blue-600 md:dark:hover:bg-transparent md:dark:hover:text-blue-500"
                            >Team</a
                        >
                    </li>
                    <li>
                        <a
                            href="#"
                            class="block border-b border-gray-100 px-3 py-2 text-gray-900 hover:bg-gray-50 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-blue-500 md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-blue-600 md:dark:hover:bg-transparent md:dark:hover:text-blue-500"
                            >Contact</a
                        >
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
