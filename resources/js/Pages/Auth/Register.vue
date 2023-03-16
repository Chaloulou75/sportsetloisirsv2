<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { CheckIcon } from "@heroicons/vue/24/solid";
import { UserCircleIcon } from "@heroicons/vue/24/outline";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head
        title="Inscription"
        description="Sports-et-loisirs.fr recense les structures proposant des activités de sport ou de loisirs en France - plus de 300 disciplines et 32000 clubs référencés."
    />
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                <UserCircleIcon
                    class="mr-2 inline-block h-6 w-6 text-gray-600"
                ></UserCircleIcon
                >Inscription
            </h2>
            <div
                class="py-2 text-base font-medium leading-relaxed text-gray-600"
            >
                <p class="pb-2 font-semibold">Pourquoi créer un compte?</p>
                <p>
                    En créant un compte sur notre site, vous aurez accès
                    gratuitement à l'ensemble de nos services, à savoir :
                </p>
                <p class="flex items-center">
                    <CheckIcon class="mr-2 h-5 w-5 text-blue-500" />Publier la
                    fiche détaillée de votre structure
                </p>
                <p class="flex items-center">
                    <CheckIcon class="mr-2 h-5 w-5 text-blue-500" />Diffuser vos
                    événements
                </p>
            </div>
        </template>

        <div
            class="flex min-h-full flex-col items-center bg-white py-16 sm:justify-center"
        >
            <div
                class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
            >
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Nom" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Mot de passe" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirmation Mot de passe"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <Link
                            :href="route('login')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Déjà enregistré?
                        </Link>

                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            S'enregistrer
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
