<script setup>
import { ref, computed } from "vue";
import { LMap, LTileLayer, LMarker, LTooltip } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    structures: Object,
});

const center = ref([
    props.structures[0].address_lat,
    props.structures[0].address_lng,
]);

const zoom = ref(6);

// a computed ref to get lat & lng
const lat = computed(() => {
    return parseFloat(props.structures[0].address_lat);
});

const lng = computed(() => {
    return parseFloat(props.structures[0].address_lng);
});
</script>

<template>
    <div class="h-[400px] w-full">
        <l-map
            :useGlobalLeaflet="false"
            ref="map"
            v-model:zoom="zoom"
            :zoom="zoom"
            :minZoom="2"
            :maxZoom="20"
            :zoomAnimation="true"
            :center="center"
        >
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                layer-type="base"
                name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker
                v-for="structure in structures"
                :key="structure.id"
                :lat-lng="[
                    parseFloat(structure.address_lat),
                    parseFloat(structure.address_lng),
                ]"
                ><l-tooltip> {{ structure.name }} </l-tooltip></l-marker
            >
        </l-map>
    </div>
</template>
