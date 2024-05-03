<script setup>
import { onUnmounted, ref, watchEffect, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const props = defineProps({
    type: String,
    message: String,
});
const page = usePage();
const isToastDisplayed = ref(false);
const timeout = ref(null);
// const flash = computed(() => page.props.flash);

const options = {
    autoClose: 4000,
    type: "default",
    hideProgressBar: false,
    position: toast.POSITION.BOTTOM_RIGHT,
    pauseOnHover: true,
    transition: toast.TRANSITIONS.BOUNCE,
};

const notify = (type, message) => {
    options["type"] = type;
    toast(message, options);
};

watchEffect(async () => {
    if (!isToastDisplayed.value && page.props.flash.success) {
        isToastDisplayed.value = true;
        notify("success", page.props.flash.success);
        clearTimeout(timeout.value);
        timeout.value = setTimeout(() => {
            page.props.flash.success = "";
            isToastDisplayed.value = false;
        }, 4000);
    }
    if (!isToastDisplayed.value && page.props.flash.error) {
        isToastDisplayed.value = true;
        notify("error", page.props.flash.error);
        clearTimeout(timeout.value);
        timeout.value = setTimeout(() => {
            page.props.flash.error = "";
            isToastDisplayed.value = false;
        }, 4000);
    }
    if (!isToastDisplayed.value && page.props.flash.message) {
        isToastDisplayed.value = true;
        notify("message", page.props.flash.message);
        clearTimeout(timeout.value);
        timeout.value = setTimeout(() => {
            page.props.flash.message = "";
            isToastDisplayed.value = false;
        }, 4000);
    }
});

onUnmounted(() => clearTimeout(timeout.value));
</script>

<template></template>
