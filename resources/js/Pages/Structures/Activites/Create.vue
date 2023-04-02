<script setup>
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/24/solid";
import AppLayout from "@/Layouts/AppLayout.vue";
import LogoInput from "@/Components/LogoInput.vue";
import StepsIndicator from "@/Components/Inscription/StepsIndicator.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, defineAsyncComponent } from "vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const props = defineProps({
    niveaux: Object,
    publictypes: Object,
    activitestypes: Object,
    errors: Object,
});

const form = useForm({
    activitestypes: ref(null),
    name: ref(null),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    category_id: ref(null),
    discipline: ref(null),
    description: ref(null),
    nivel_id: ref(null),
    publictypes_id: ref(null),
});

const formStep = ref(1);
const name = ref(null);
const categories = ref([]);
const disciplinesList = ref([]);

const getCategories = async () => {
    let response = await axios.get("/api/categories");
    categories.value = response.data.data;
};

onMounted(() => {
    getCategories();
});

watch(
    () => form.category_id,
    async (newCategoryID) => {
        axios
            .get("/api/disciplines?category_id=" + newCategoryID)
            .then((response) => {
                disciplinesList.value = response.data.data;
            })
            .catch((e) => {
                console.log(e);
            });
    }
);

function nextStep() {
    formStep.value++;
}

function prevStep() {
    formStep.value--;
}

function submit() {
    form.post("/activites");
}

function removeActivite(index) {
    form.activites.splice(index, 1);
}
</script>
<template></template>
