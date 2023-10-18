<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import Modal from "@/Components/Modals/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Supprimer le compte
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Une fois que votre compte est supprimé, toutes vos données sont
                supprimées définitivement. Êtes-vous sûr·e de vouloir supprimer
                votre compte?
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion"
            >Supprimer le compte</DangerButton
        >

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Êtes-vous sûr·e de vouloir supprimer votre compte ?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Une fois que votre compte est supprimé, toutes les données
                    associées seront supprimées définitivement. Pour confirmer
                    que vous voulez supprimer définitivement votre compte,
                    renseignez votre mot de passe.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Annuler
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Supprimer le compte
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
