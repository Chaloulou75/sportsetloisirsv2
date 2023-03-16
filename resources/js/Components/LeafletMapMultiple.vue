<script setup>
import { LMap, LTileLayer, LMarker, LTooltip } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";
import { ref, computed } from "vue";
const props = defineProps({
    structures: Object,
});

// a computed ref to get lat & lng
const lat = computed(() => {
    return parseFloat(props.structures[0].address_lat);
});

const lng = computed(() => {
    return parseFloat(props.structures[0].address_lng);
});

const zoom = ref(6);
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
            :center="[lat, lng]"
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
