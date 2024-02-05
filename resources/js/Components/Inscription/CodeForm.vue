<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const props = defineProps({
    errors: Object,
    reservation: Object,
    structure: Object,
});

const codeForm = useForm({
    code: ref(null),
    reservation_id: ref(props.reservation.id),
    status: ref("finished"),
    remember: true,
});

const onCodeSubmit = () => {
    codeForm.put(
        route("structures.gestion.reservations.update", {
            structure: props.structure.slug,
            reservation: props.reservation,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                codeForm.reset();
            },
        }
    );
};
</script>
<template>
    <form @submit.prevent="onCodeSubmit" autocomplete="off" class="space-y-4">
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">
                Code de la réservation
                <span class="italic">(4 chiffres)</span>
                *
            </label>
            <div class="flex mt-1 rounded-md">
                <input
                    v-model="codeForm.code"
                    type="text"
                    name="code"
                    id="code"
                    :class="{
                        'border-red-400': codeForm.errors.code,
                    }"
                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                    placeholder="1234"
                    autocomplete="none"
                />
            </div>
            <div v-if="codeForm.errors.code" class="mt-2 text-sm text-red-500">
                {{ codeForm.errors.code }}
            </div>
        </div>
        <button
            type="submit"
            :disabled="codeForm.processing"
            :class="{
                'opacity-25': codeForm.processing,
            }"
            class="w-full px-4 py-2 text-lg text-indigo-500 bg-white border border-gray-200 rounded-md shadow hover:bg-gray-100 hover:text-indigo-800"
        >
            <LoadingSVG v-if="codeForm.processing" />
            Verifier le code
        </button>
    </form>
</template>
