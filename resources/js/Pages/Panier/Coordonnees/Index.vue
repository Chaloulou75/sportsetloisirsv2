<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Head, usePage } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const props = defineProps({
    user: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});

const coordonneesForm = useForm({
    user: props.user ?? null,
    name: props.user?.name ?? null,
    email: props.user?.email ?? null,
});

const onSubmit = () => {
    coordonneesForm.post(route("panier.coordonnees.store"), {
        preserveScroll: true,
    });
};
</script>
<template>
    <Head title="Coordonnées" description="Mes Coordonnées" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Mes coordonnées</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <form @submit.prevent="onSubmit" autocomplete="off">
            <div class="container mx-auto flex flex-col gap-4 py-6">
                <div class="w-full space-y-4">
                    <h2 class="text-lg font-semibold">Coordonnées:</h2>
                    <div>
                        <InputLabel for="name" value="Nom" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.name"
                        />
                    </div>
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.email"
                            required
                            autocomplete="username"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.email"
                        />
                    </div>
                </div>

                <div
                    class="w-full max-w-sm self-end text-base font-bold text-gray-700"
                >
                    <button
                        :disabled="coordonneesForm.processing"
                        :class="{
                            'opacity-25': coordonneesForm.processing,
                        }"
                        type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-500 px-4 py-2.5 font-medium text-blue-50 hover:bg-blue-600"
                    >
                        <LoadingSVG v-if="coordonneesForm.processing" />
                        Réserver
                    </button>
                </div>
            </div>
        </form>
    </ResultLayout>
</template>
