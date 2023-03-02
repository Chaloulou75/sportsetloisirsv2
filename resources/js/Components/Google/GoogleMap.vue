<script setup>
import { ref, computed } from "vue";
import { useScriptTag } from "@vueuse/core";

const props = defineProps({
    club: Object,
});

// the map element in the templste
const mapDivRef = ref(null);
// the google map object
const map = ref(null);
// google marker
const marker = ref(null);

// a computed ref to get lat & lng
const lat = computed(() => {
    return parseFloat(props.club.address_lat);
});

const lng = computed(() => {
    return parseFloat(props.club.address_lng);
});

useScriptTag(
    `https://maps.googleapis.com/maps/api/js?key=${
        import.meta.env.VITE_GOOGLE_MAP_API_KEY
    }`,
    // on script tag loaded.
    () => {
        map.value = new google.maps.Map(mapDivRef.value, {
            zoom: 10,
            center: {
                lat: lat.value,
                lng: lng.value,
            },
        });

        marker.value = new google.maps.Marker({
            position: {
                lat: lat.value,
                lng: lng.value,
            },
            map: map.value,
        });
    }
);
</script>

<template>
    <div
        ref="mapDivRef"
        class="w-full h-64 rounded-sm shadow-sm shadow-sky-700"
    ></div>
</template>
