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
    name_receiver: null,
    email_receiver: null,
});

const showGiftFields = ref(false);
const openGiftFields = () => {
    showGiftFields.value = !showGiftFields.value;
};

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
                    <h2 class="text-lg font-semibold">
                        Remplissez vos informations:
                    </h2>
                    <div>
                        <InputLabel for="name" value="Nom complet:" />

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
                        <InputLabel for="email" value="Email:" />

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
                    <button
                        class="rounded-sm bg-blue-700 px-3 py-2 text-sm text-white hover:bg-blue-600"
                        type="button"
                        @click.prevent="openGiftFields"
                    >
                        <span v-if="!showGiftFields">C'est pour offrir ?</span
                        ><span v-else>C'est pour vous?</span>
                    </button>
                    <template v-if="showGiftFields">
                        <p class="text-sm font-semibold text-gray-700">
                            Coordonnées de la personne qui reçoit votre cadeau:
                        </p>
                    </template>
                    <div v-if="showGiftFields">
                        <InputLabel
                            for="name_receiver"
                            value="Nom complet du recevant:"
                        />

                        <TextInput
                            id="name_receiver"
                            type="text"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.name_receiver"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.name_receiver"
                        />
                    </div>
                    <div v-if="showGiftFields">
                        <InputLabel
                            for="email_receiver"
                            value="Email du recevant:"
                        />

                        <TextInput
                            id="email_receiver"
                            type="email"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.email_receiver"
                            required
                            autocomplete="email"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.email_receiver"
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
