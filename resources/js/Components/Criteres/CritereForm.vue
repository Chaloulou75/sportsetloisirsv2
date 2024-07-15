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
import RangeMultiple from "@/Components/Forms/RangeMultiple.vue";
import RadioForm from "@/Components/Forms/RadioForm.vue";

const props = defineProps({
    criteres: Object,
    showCriteres: Boolean,
    showCriteresLg: Boolean,
    filters: Object,
});

const filteredCriteresByChamp = computed(() => {
    return props.criteres.filter((critere) => {
        return critere.type_champ.criterable;
    });
});

const criteresModel = defineModel("criteresBase");
const sousCriteresModel = defineModel("sousCriteres");
const emit = defineEmits(["reset-criteres"]);

const handleResetFormCriteres = () => {
    emit("reset-criteres");
};
</script>
<template>
    <form
        class="relative mx-auto w-full flex-col items-center justify-center gap-6 overflow-x-auto rounded bg-gray-50 px-2 py-2 backdrop-blur-md md:flex-row md:items-start md:justify-between md:px-6 md:pt-4"
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
                class="max-w-sm"
                v-if="critere.type_champ_form === 'select'"
                :name="critere.nom"
                v-model="criteresModel[critere.id]"
                :options="critere.valeurs"
            />
            <!-- checkbox -->
            <CheckboxForm
                class="max-w-sm"
                v-if="critere.type_champ_form === 'checkbox'"
                :name="critere.nom"
                v-model="criteresModel[critere.id]"
                :options="critere.valeurs"
            />
            <!-- radio -->
            <RadioForm
                v-if="critere.type_champ_form === 'radio'"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
                :options="critere.valeurs"
            />

            <!-- input text -->
            <div v-if="critere.type_champ_form === 'text'">
                <label
                    :for="critere.nom"
                    class="mb-1 block text-sm font-medium normal-case text-gray-700"
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
            <div v-if="critere.type_champ_form === 'number'">
                <label
                    :for="critere.nom"
                    class="mb-1 block text-sm font-medium normal-case text-gray-700"
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
            <!-- Range  -->
            <RangeInputForm
                v-if="critere.type_champ_form === 'range'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
                :unite="critere.unite"
                :min="critere.min"
                :max="critere.max"
            />
            <!-- Range multiple -->
            <RangeMultiple
                v-if="critere.type_champ_form === 'range multiple'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
                :unite="critere.unite"
                :min="critere.min"
                :max="critere.max"
            />
            <!-- Heure seule -->
            <SingleTimeForm
                v-if="critere.type_champ_form === 'time'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
            />
            <!-- Heures x2 ouverture / fermeture -->
            <OpenTimesForm
                v-if="critere.type_champ_form === 'times'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
            />
            <!-- Date seule -->
            <SingleDateForm
                v-if="critere.type_champ_form === 'date'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
            />

            <!-- Dates x 2 -->
            <OpenDaysForm
                v-if="critere.type_champ_form === 'dates'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
            />

            <!-- Mois -->
            <OpenMonthsForm
                v-if="critere.type_champ_form === 'mois'"
                class="w-full max-w-sm"
                v-model="criteresModel[critere.id]"
                :name="critere.nom"
            />

            <!-- sous criteres -->
            <template v-if="critere.valeurs">
                <div v-for="valeur in critere.valeurs" :key="valeur.id">
                    <div
                        v-for="souscritere in valeur.sous_criteres"
                        :key="souscritere.id"
                        class="ml-1 mt-2"
                    >
                        <!-- select -->
                        <SelectForm
                            class="max-w-sm"
                            v-if="
                                criteresModel[critere.id] === valeur &&
                                souscritere.type_champ_form === 'select' &&
                                souscritere.dis_cat_crit_val_id === valeur.id
                            "
                            :name="souscritere.nom"
                            v-model="sousCriteresModel[souscritere.id]"
                            :options="souscritere.sous_criteres_valeurs"
                        />
                        <CheckboxForm
                            class="max-w-sm"
                            v-if="
                                criteresModel[critere.id] === valeur &&
                                souscritere.type_champ_form === 'checkbox' &&
                                souscritere.dis_cat_crit_val_id === valeur.id
                            "
                            :name="souscritere.nom"
                            v-model="sousCriteresModel[souscritere.id]"
                            :options="souscritere.sous_criteres_valeurs"
                        />

                        <!-- radio -->
                        <RadioForm
                            v-if="
                                criteresModel[critere.id] === valeur &&
                                souscritere.type_champ_form === 'radio' &&
                                souscritere.dis_cat_crit_val_id === valeur.id
                            "
                            v-model="sousCriteresModel[souscritere.id]"
                            :name="souscritere.nom"
                            :options="souscritere.sous_criteres_valeurs"
                        />
                        <!-- number -->
                        <div
                            v-if="
                                criteresModel[critere.id] === valeur &&
                                souscritere.type_champ_form === 'number' &&
                                souscritere.dis_cat_crit_val_id === valeur.id
                            "
                            class="flex items-center space-x-4"
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
                            :min="souscritere.min"
                            :max="souscritere.max"
                            :unite="souscritere.unite"
                        />
                        <RangeMultiple
                            v-if="
                                criteresModel[critere.id] === valeur &&
                                souscritere.type_champ_form ===
                                    'range multiple' &&
                                souscritere.dis_cat_crit_val_id === valeur.id
                            "
                            class="w-full max-w-sm"
                            v-model="sousCriteresModel[souscritere.id]"
                            :name="souscritere.nom"
                            :min="souscritere.min"
                            :max="souscritere.max"
                            :unite="souscritere.unite"
                        />
                    </div>
                </div>
            </template>
        </div>
        <button
            class="flex w-full items-center justify-center place-self-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-md transition duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 md:w-auto"
            type="button"
            @click.prevent="handleResetFormCriteres"
        >
            <ArrowPathIcon
                class="h-6 w-6 text-white transition duration-200 hover:-rotate-90 md:h-8 md:w-8"
            />
            <span class="ml-2">Réinitialiser les critères</span>
        </button>
    </form>
</template>
