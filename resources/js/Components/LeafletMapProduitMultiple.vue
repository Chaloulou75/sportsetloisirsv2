<script setup>
import { ref, watch, defineAsyncComponent } from "vue";
import { Link } from "@inertiajs/vue3";
import L from "leaflet";
import {
    LMap,
    LTileLayer,
    LMarker,
    LControlScale,
    LPopup,
    LTooltip,
    LIcon,
} from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    produits: Object,
    hoveredProduit: Number,
    structures: Object,
    hoveredStructure: Number,
    zoom: Number,
});

const ProduitCardXS = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCardXS.vue")
);
const StructureCardXS = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCardXS.vue")
);

const map = ref(null);
const markerProd = ref([]);

const center = ref([
    props.produits[0].adresse.address_lat,
    props.produits[0].adresse.address_lng,
]);

const zoom = ref(props.zoom ? props.zoom : 7);
const imperial = ref(false);
const mapOptions = ref({
    zoomSnap: 0.5,
});

const structureIcon = L.divIcon({
    className: "text-green-700 h-8 w-8",
    html: `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
  <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
</svg>

      `,
    iconSize: [35, 41],
    iconAnchor: [16, 37],
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

watch(
    () => props.hoveredStructure,
    (newValue) => {
        if (newValue !== null) {
            const structure = props.structures.find((s) => s.id === newValue);
            if (structure) {
                center.value = [structure.address_lat, structure.address_lng];
            }
        } else {
            center.value = [
                props.structures[0].address_lat,
                props.structures[0].address_lng,
            ];
        }
    }
);
</script>

<template>
    <div class="flex h-full flex-col items-center justify-start space-y-4 pb-4">
        <div class="flex items-center justify-start space-x-4">
            <label for="zoomSize">Zoom:</label>
            <input id="zoomSize" v-model="zoom" type="range" min="2" max="20" />
        </div>
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
                <l-control-scale :imperial="imperial" position="topright" />
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
                    :ref="markerProd"
                >
                    <l-tooltip :content="produit.activite.titre"></l-tooltip>
                    <l-popup
                        :options="{
                            interactive: true,
                            autoPan: true,
                            autoPanPadding: [4, 4],
                            keepInView: true,
                        }"
                    >
                        <ProduitCardXS
                            :produit="produit"
                            :discipline="produit.discipline"
                            :link="
                                route('structures.activites.show', {
                                    activite: produit.activite.id,
                                })
                            "
                            :data="{
                                produit: produit.id,
                            }"
                        />
                    </l-popup>
                </l-marker>
                <l-marker
                    v-for="structure in structures"
                    :key="structure.id"
                    :lat-lng="[
                        parseFloat(structure.address_lat),
                        parseFloat(structure.address_lng),
                    ]"
                    :icon="structureIcon"
                >
                    <l-tooltip :content="structure.name"></l-tooltip>
                    <l-popup :options="{ interactive: true }">
                        <StructureCardXS
                            :structure="structure"
                            :link="
                                route('structures.show', {
                                    structure: structure.slug,
                                })
                            "
                            :data="{}"
                        />
                    </l-popup>
                </l-marker>
            </l-map>
        </div>
    </div>
</template>
