<script setup>
import { ref, watch } from "vue";
import {
    LMap,
    LTileLayer,
    LMarker,
    LControlScale,
    LTooltip,
} from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    produits: Object,
    hoveredProduit: Number,
    zoom: Number,
});

const map = ref(null);

const center = ref([
    props.produits[0].adresse.address_lat,
    props.produits[0].adresse.address_lng,
]);

const zoom = ref(props.zoom ? props.zoom : 7);
const imperial = ref(false);
const mapOptions = ref({
    zoomSnap: 0.5,
});

watch(
    () => props.hoveredProduit,
    (newValue) => {
        if (newValue !== null) {
            const produit = props.produits.find((s) => s.id === newValue);
            if (produit) {
                center.value = [
                    produit.adresse.address_lat,
                    produit.adresse.address_lng,
                ];
            }
        } else {
            center.value = [
                props.produits[0].adresse.address_lat,
                props.produits[0].adresse.address_lng,
            ];
        }
    }
);
</script>

<template>
    <div class="h-[400px] w-full shadow-md">
        <l-map
            :useGlobalLeaflet="false"
            ref="map"
            :zoom="zoom"
            :minZoom="2"
            :maxZoom="20"
            :zoomAnimation="true"
            :center="center"
            :options="mapOptions"
        >
            <l-control-scale :imperial="imperial" />
            <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution='&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                layer-type="base"
                name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker
                v-for="produit in produits"
                :key="produit.id"
                :lat-lng="[
                    parseFloat(produit.adresse.address_lat),
                    parseFloat(produit.adresse.address_lng),
                ]"
            >
                <l-tooltip
                    :options="{ interactive: true }"
                    :content="produit.activite.titre"
                    class="rouded-lg px-1.5 py-1 font-semibold"
                >
                </l-tooltip>
            </l-marker>
        </l-map>
    </div>
</template>
