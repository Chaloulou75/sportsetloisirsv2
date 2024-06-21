<script setup>
import { computed } from "vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import RangeInputForm from "@/Components/Forms/RangeInputForm.vue";
import OpenDaysForm from "@/Components/Forms/DayTime/OpenDaysForm.vue";
import SingleDateForm from "@/Components/Forms/DayTime/SingleDateForm.vue";
import SingleTimeForm from "@/Components/Forms/DayTime/SingleTimeForm.vue";
import OpenTimesForm from "@/Components/Forms/DayTime/OpenTimesForm.vue";
import OpenMonthsForm from "@/Components/Forms/DayTime/OpenMonthsForm.vue";
import { ArrowPathIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    criteres: Object,
    showCriteres: Boolean,
    showCriteresLg: Boolean,
    isCheckboxSelected: Function,
});

const filteredCriteresByChamp = computed(() => {
    return props.criteres.filter((critere) => {
        return [
            "select",
            "checkbox",
            "radio",
            "text",
            "number",
            "rayon",
            "range",
        ].includes(critere.type_champ_form);
    });
});

const criteresModel = defineModel("criteresBase");
const sousCriteresModel = defineModel("sousCriteres");
const emit = defineEmits(["reset-criteres", "update-checkboxes"]);

const handleUpdateSelectedCheckboxes = (critereId, optionValue, checked) => {
    emit("update-checkboxes", critereId, optionValue, checked);
};

const handleResetFormCriteres = () => {
    emit("reset-criteres");
};
</script>
<template>
    <div
        v-if="criteres"
        class="mx-auto w-full flex-col items-start justify-center gap-4 overflow-x-auto rounded bg-transparent px-2 py-2 backdrop-blur-md md:flex-row md:items-center md:justify-between md:space-y-0 md:px-6"
        :class="{
            flex: showCriteres,
            hidden: !showCriteres,
            'md:flex': showCriteresLg,
            'md:hidden': !showCriteresLg,
        }"
    >
        <div
            v-for="critere in filteredCriteresByChamp"
            :key="critere.id"
            class="w-full max-w-sm shrink-0 md:w-auto"
        >
            <!-- select -->
            <SelectForm
                :classes="'flex items-center space-x-2'"
                class="max-w-sm"
                v-if="critere.type_champ_form === 'select'"
                :name="critere.nom"
                v-model="criteresModel[critere.id]"
                :options="critere.valeurs"
            />
            <!-- checkbox -->
            <CheckboxForm
                :classes="'flex items-center space-x-2'"
                class="max-w-sm"
                v-if="critere.type_champ_form === 'checkbox'"
                :critere="critere"
                :name="critere.nom"
                v-model="criteresModel[critere.id]"
                :options="critere.valeurs"
                :is-checkbox-selected="isCheckboxSelected"
                @update-selected-checkboxes="handleUpdateSelectedCheckboxes"
            />
            <!-- radio -->
            <div v-if="critere.type_champ_form === 'radio'">
                <div class="flex items-center space-x-2">
                    <label
                        :for="critere.nom"
                        class="block text-sm font-medium normal-case text-gray-700"
                    >
                        {{ critere.nom }}
                    </label>
                    <div class="flex rounded-md">
                        <div>
                            <label
                                class="inline-flex items-center"
                                v-for="(option, index) in critere.valeurs"
                                :key="option.id"
                            >
                                <input
                                    v-model="criteresModel[critere.id]"
                                    type="radio"
                                    class="form-radio"
                                    :name="option.valeur"
                                    :value="option.valeur"
                                    checked
                                />
                                <span class="ml-2">{{ option.valeur }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- input text -->
            <div
                v-if="critere.type_champ_form === 'text'"
                class="flex items-center space-x-2"
            >
                <label
                    :for="critere.nom"
                    class="block text-sm font-medium normal-case text-gray-700"
                >
                    {{ critere.nom }}
                </label>
                <div class="flex-1">
                    <TextInput
                        type="text"
                        v-model="criteresModel[critere.id]"
                        :name="critere.nom"
                        :id="critere.nom"
                    />
                </div>
            </div>
            <!-- input Number -->
            <div
                v-if="critere.type_champ_form === 'number'"
                class="flex items-center space-x-2"
            >
                <label
                    :for="critere.nom"
                    class="block text-sm font-medium normal-case text-gray-700"
                >
                    {{ critere.nom }}
                </label>
                <div class="flex-1">
                    <TextInput
                        type="number"
                        min="0"
                        v-model="criteresModel[critere.id]"
                        :name="critere.nom"
                        :id="critere.nom"
                    />
                </div>
            </div>
            <!-- Range km  -->
            <RangeInputForm
                v-if="critere.type_champ_form === 'rayon'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
                :metric="critere.nom"
            />
            <RangeInputForm
                v-if="critere.type_champ_form === 'range'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
                :metric="critere.nom"
            />
            <!-- Heure seule -->
            <div
                v-if="critere.type_champ_form === 'time'"
                class="flex max-w-sm flex-col items-start"
            >
                <SingleTimeForm
                    class="w-full"
                    v-model="criteresModel[critere.id]"
                    :name="critere.nom"
                />
            </div>
            <!-- Heures x2 ouverture / fermeture -->
            <div
                v-if="critere.type_champ_form === 'times'"
                class="flex max-w-sm flex-col items-start space-y-3"
            >
                <OpenTimesForm
                    class="w-full"
                    v-model="criteresModel[critere.id]"
                    :name="critere.nom"
                />
            </div>
            <!-- Date seule -->
            <div
                v-if="critere.type_champ_form === 'date'"
                class="flex max-w-sm flex-col items-start space-y-3"
            >
                <SingleDateForm
                    class="w-full"
                    v-model="criteresModel[critere.id]"
                    :name="critere.nom"
                />
            </div>
            <!-- Dates x 2 -->
            <div
                v-if="critere.type_champ_form === 'dates'"
                class="flex max-w-sm flex-col items-start space-y-3"
            >
                <OpenDaysForm
                    class="w-full"
                    v-model="criteresModel[critere.id]"
                    :name="critere.nom"
                />
            </div>
            <!-- Mois -->
            <div v-if="critere.type_champ_form === 'mois'">
                <div class="flex max-w-sm flex-col items-start space-y-3">
                    <OpenMonthsForm
                        class="w-full"
                        v-model="criteresModel[critere.id]"
                        :name="critere.nom"
                    />
                </div>
            </div>
            <!-- sous criteres -->
            <div v-for="valeur in critere.valeurs" :key="valeur.id">
                <div
                    v-for="souscritere in valeur.sous_criteres"
                    :key="souscritere.id"
                    class=""
                >
                    <!-- select -->
                    <SelectForm
                        :classes="'flex items-center space-x-4'"
                        class="max-w-sm py-2"
                        v-if="
                            criteresModel[critere.id] === valeur &&
                            souscritere.type_champ_form === 'select' &&
                            souscritere.dis_cat_crit_val_id === valeur.id
                        "
                        :name="souscritere.nom"
                        v-model="sousCriteresModel[souscritere.id]"
                        :options="souscritere.sous_criteres_valeurs"
                    />
                    <!-- number -->
                    <div
                        v-if="
                            criteresModel[critere.id] === valeur &&
                            souscritere.type_champ_form === 'number' &&
                            souscritere.dis_cat_crit_val_id === valeur.id
                        "
                        class="mt-2 flex items-center space-x-4"
                    >
                        <InputLabel
                            class="py-2"
                            :for="souscritere.nom"
                            :value="souscritere.nom"
                        />
                        <TextInput
                            class="w-full"
                            type="number"
                            min="0"
                            :id="souscritere.nom"
                            :name="souscritere.nom"
                            v-model="sousCriteresModel[souscritere.id]"
                        />
                    </div>
                    <!-- text -->
                    <div
                        v-if="
                            criteresModel[critere.id] === valeur &&
                            souscritere.type_champ_form === 'text' &&
                            souscritere.dis_cat_crit_val_id === valeur.id
                        "
                        class="mt-2 flex items-center space-x-4"
                    >
                        <InputLabel
                            class="py-2"
                            :for="souscritere.nom"
                            :value="souscritere.nom"
                        />
                        <TextInput
                            class="w-full"
                            type="text"
                            :id="souscritere.nom"
                            :name="souscritere.nom"
                            v-model="sousCriteresModel[souscritere.id]"
                        />
                    </div>
                    <!-- range -->
                    <RangeInputForm
                        v-if="
                            criteresModel[critere.id] === valeur &&
                            souscritere.type_champ_form === 'range' &&
                            souscritere.dis_cat_crit_val_id === valeur.id
                        "
                        class="w-full max-w-sm"
                        v-model="sousCriteresModel[souscritere.id]"
                        :name="souscritere.nom"
                        :metric="souscritere.nom"
                    />
                </div>
            </div>
        </div>
        <button
            class="flex w-full justify-center md:w-auto"
            type="button"
            @click="handleResetFormCriteres"
        >
            <ArrowPathIcon
                class="h-6 w-6 text-gray-500 transition duration-200 hover:-rotate-90 hover:text-gray-700 md:h-8 md:w-8"
            />
        </button>
    </div>
</template>
