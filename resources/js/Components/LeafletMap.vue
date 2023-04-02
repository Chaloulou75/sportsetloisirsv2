<script setup>
import { LMap, LTileLayer, LMarker, LTooltip } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";
import { ref, computed } from "vue";
const props = defineProps({
    structure: Object,
});

// a computed ref to get lat & lng
const lat = computed(() => {
    return parseFloat(props.structure.address_lat);
});

const lng = computed(() => {
    return parseFloat(props.structure.address_lng);
});

const zoom = ref(12);
</script>

<template>
    <div class="h-[400px] w-full">
        <l-map
            :useGlobalLeaflet="false"
            ref="map"
            v-model:zoom="zoom"
            :zoom="zoom"
            :minZoom="4"
            :maxZoom="18"
            :zoomAnimation="true"
            :center="[lat, lng]"
        >
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                layer-type="base"
                name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker :lat-lng="[lat, lng]"
                ><l-tooltip class="rouded-lg px-1.5 py-1 font-semibold">
                    {{ structure.name }}
                </l-tooltip></l-marker
            >
        </l-map>
    </div>
</template>
