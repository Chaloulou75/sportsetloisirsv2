<script setup>
import { ref, watch } from "vue";
const emit = defineEmits(["eventFromChild"]);

const props = defineProps({
    activite: Object,
    discipline: Object,
    categorie: Object,
    currentDisplay: String,
});

const activeButton = ref(props.currentDisplay);

const emitEvent = (message) => {
    emit("eventFromChild", message);
    activeButton.value = message;
};

const getButtonClass = (buttonName) => {
    return {
        "bg-indigo-500": activeButton.value === buttonName,
        "text-white": activeButton.value === buttonName,
    };
};

watch(
    () => props.currentDisplay,
    (newDisplay) => {
        activeButton.value = newDisplay;
    }
);
</script>
<template>
    <div
        class="flex w-full flex-col items-center border border-b border-gray-200 md:flex-row md:justify-start"
    >
        <div
            class="flex w-full flex-col items-center divide-x-2 divide-gray-200 md:w-auto md:flex-row"
        >
            <button
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Mes activites')"
                :class="getButtonClass('Mes activites')"
            >
                <span
                    :class="{
                        hidden: route().current('structures.categories.show'),
                    }"
                    >Mes activités
                </span>

                <span v-if="categorie">
                    {{ categorie.nom_categorie_pro }} de
                </span>
                <span v-if="discipline"> {{ discipline.name }} </span>
                <div
                    v-if="activeButton === 'Mes activites'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>
            <button
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Planning')"
                :class="getButtonClass('Planning')"
            >
                Planning
                <span v-if="categorie">
                    {{ categorie.nom_categorie_pro }} de
                </span>

                <span v-if="discipline"> {{ discipline.name }} </span>
                <div
                    v-if="activeButton === 'Planning'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>

            <button
                class="group relative w-full px-4 py-2 text-left text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 md:w-auto md:px-6 md:py-2.5 md:text-base md:font-semibold"
                type="button"
                @click="emitEvent('Mes tarifs')"
                :class="getButtonClass('Mes tarifs')"
            >
                <p>
                    Tarifs
                    <span v-if="categorie"
                        >{{ categorie.nom_categorie_pro }} de
                    </span>
                    <span v-if="discipline"> {{ discipline.name }} </span>
                </p>
                <div
                    v-if="activeButton === 'Mes tarifs'"
                    class="absolute inset-x-2 -bottom-2 mx-auto h-4 w-4 rotate-45 bg-indigo-500 group-hover:hidden"
                ></div>
            </button>
        </div>
    </div>
</template>
