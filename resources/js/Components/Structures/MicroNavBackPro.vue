<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ModalDeleteStructure from "@/Components/Modals/ModalDeleteStructure.vue";
const emit = defineEmits(["eventFromChild"]);
const activeButton = ref("Edit Structure");

const emitEvent = (message) => {
    emit("eventFromChild", message);
    activeButton.value = message;
};

const props = defineProps({
    structure: Object,
    can: Object,
});

const showModal = ref(false);

const getButtonClass = (buttonName) => {
    return {
        "bg-indigo-500": activeButton.value === buttonName,
        "text-white": activeButton.value === buttonName,
    };
};
</script>
<template>
    <div
        class="flex w-full flex-col items-center border border-b border-gray-200 md:flex-row md:justify-between"
    >
        <div class="flex w-full flex-col items-center md:w-auto md:flex-row">
            <button
                class="group relative w-full px-4 py-1.5 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Edit Structure')"
                :class="getButtonClass('Edit Structure')"
            >
                Ma structure
                <div
                    v-if="activeButton === 'Edit Structure'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>

            <button
                class="group relative w-full px-4 py-1.5 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Mes adresses')"
                :class="getButtonClass('Mes adresses')"
            >
                Mes adresses
                <div
                    v-if="activeButton === 'Mes adresses'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>
            <button
                class="group relative w-full px-4 py-1.5 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Partenaires')"
                :class="getButtonClass('Partenaires')"
            >
                Partenaires / Instructeurs
                <div
                    v-if="activeButton === 'Partenaires'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>
        </div>
        <div
            class="flex w-full flex-col items-start md:w-auto md:flex-row md:items-center"
        >
            <div
                class="w-full px-4 py-1.5 text-left text-sm font-medium text-gray-500 hover:text-gray-800 md:w-auto md:px-6 md:py-2.5"
            >
                <Link
                    :href="
                        route('structures.show', {
                            structure: structure.slug,
                        })
                    "
                >
                    Voir ma structure
                </Link>
            </div>
            <div
                class="w-full px-4 py-1.5 text-left text-sm font-medium text-gray-500 hover:text-gray-800 md:w-auto md:px-6 md:py-2.5"
            >
                <button v-if="can.delete" @click="showModal = true">
                    Supprimer cette structure
                </button>
            </div>
        </div>
    </div>
    <ModalDeleteStructure
        :structure="structure"
        :show="showModal"
        @close="showModal = false"
    />
</template>
