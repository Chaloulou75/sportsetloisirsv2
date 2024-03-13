<script setup>
import { computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const props = defineProps({
    type: String,
    message: String,
});
const page = usePage();
const flash = page.props.flash;

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

if (flash.success) {
    notify("success", flash.success);
}
if (flash.error) {
    notify("error", flash.error);
}
if (flash.message) {
    notify("message", flash.message);
}
</script>

<template></template>
