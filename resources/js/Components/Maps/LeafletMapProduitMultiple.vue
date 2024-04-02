<script setup>
import "leaflet/dist/leaflet.css";
import { debounce } from "lodash";
import { ref, watch, nextTick, defineAsyncComponent } from "vue";
import * as L from "leaflet/dist/leaflet-src.esm";
import {
    LMap,
    LTileLayer,
    LMarker,
    LControlScale,
    LPopup,
    LTooltip,
} from "@vue-leaflet/vue-leaflet";

const emit = defineEmits([
    "update:filteredProduits",
    "update:filteredStructures",
]);

const props = defineProps({
    produits: Object,
    hoveredProduit: Number,
    structures: Object,
    hoveredStructure: Number,
    zoom: Number,
    filteredProduits: Array,
    filteredStructures: Array,
});

const ProduitCardXS = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCardXS.vue")
);
const StructureCardXS = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCardXS.vue")
);

const map = ref(null);
const center = ref([
    props.produits[0].adresse.address_lat,
    props.produits[0].adresse.address_lng,
]);
const bounds = ref(null);
const filteredProduits = ref(props.filteredProduits);
const filteredStructures = ref(props.filteredStructures);
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

const produitRefs = ref([]);
const structureRefs = ref([]);

const openMarkerPopup = (markerId) => {
    nextTick(() => {
        const markerRef = produitRefs.value.find(
            (marker) => marker.options.produitId === markerId
        );
        const structureRef = structureRefs.value.find(
            (marker) => marker.options.structureId === markerId
        );
        if (markerRef) {
            markerRef.leafletObject.openPopup();
        }
        if (structureRef) {
            structureRef.leafletObject.openPopup();
        }
    });
};

watch(
    () => props.hoveredProduit,
    debounce((newValue) => {
        if (newValue !== null) {
            const produit = props.produits.find((s) => s.id === newValue);
            if (produit) {
                center.value = [
                    produit.adresse.address_lat,
                    produit.adresse.address_lng,
                ];
                openMarkerPopup(produit.id);
            }
        } else {
            center.value = [
                props.produits[0].adresse.address_lat,
                props.produits[0].adresse.address_lng,
            ];
            map.value.leafletObject.closePopup();
        }
    }, 300)
);

watch(
    () => props.hoveredStructure,
    debounce((newValue) => {
        if (newValue !== null) {
            const structure = props.structures.find((s) => s.id === newValue);
            if (structure) {
                center.value = [structure.address_lat, structure.address_lng];
            }
            openMarkerPopup(structure.id);
        } else {
            center.value = [
                props.structures[0].address_lat,
                props.structures[0].address_lng,
            ];
            map.value.leafletObject.closePopup();
        }
    }, 300)
);

const filterProduits = debounce(() => {
    if (bounds.value) {
        const filtered = props.produits.filter((produit) => {
            const latLng = L.latLng(
                parseFloat(produit.adresse.address_lat),
                parseFloat(produit.adresse.address_lng)
            );
            return bounds.value.contains(latLng);
        });
        filteredProduits.value = filtered;
        emit("update:filteredProduits", filtered);

        const filteredStr = props.structures.filter((structure) => {
            const latLng = L.latLng(
                parseFloat(structure.address_lat),
                parseFloat(structure.address_lng)
            );
            return bounds.value.contains(latLng);
        });
        filteredStructures.value = filteredStr;
        emit("update:filteredStructures", filteredStr);
    }
}, 300);

const handleMoveEnd = () => {
    if (window.innerWidth >= 768) {
        const theMap = map.value.leafletObject;
        bounds.value = theMap.getBounds();
        filterProduits();
    }
};
</script>

<template>
    <div class="flex h-full flex-col items-center justify-start space-y-4 pb-4">
        <div class="flex items-center justify-start space-x-4">
            <label for="zoomSize">Zoom:</label>
            <input id="zoomSize" v-model="zoom" type="range" min="7" max="18" />
        </div>
        <div class="h-[400px] w-full shadow-md">
            <l-map
                :useGlobalLeaflet="false"
                ref="map"
                v-model:zoom="zoom"
                :minZoom="7"
                :maxZoom="18"
                :zoomAnimation="true"
                :center="center"
                :options="mapOptions"
                @update:center="handleMoveEnd"
                @update:zoom="handleMoveEnd"
            >
                <l-control-scale :imperial="imperial" position="topright" />
                <l-tile-layer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    attribution='&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    layer-type="base"
                    name="OpenStreetMap"
                ></l-tile-layer>
                <l-marker
                    ref="produitRefs"
                    v-for="produit in produits"
                    :key="produit.id"
                    :options="{ produitId: produit.id, produit: produit }"
                    :lat-lng="[
                        parseFloat(produit.adresse.address_lat),
                        parseFloat(produit.adresse.address_lng),
                    ]"
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
                                    activite: produit.activite,
                                    slug: produit.activite.slug_title,
                                })
                            "
                            :data="{
                                produit: produit.id,
                            }"
                        />
                    </l-popup>
                </l-marker>

                <l-marker
                    ref="structureRefs"
                    v-for="structure in structures"
                    :key="structure.id"
                    :lat-lng="[
                        parseFloat(structure.address_lat),
                        parseFloat(structure.address_lng),
                    ]"
                    :icon="structureIcon"
                    :options="{
                        structureId: structure.id,
                        structure: structure,
                    }"
                >
                    <l-tooltip :content="structure.name"></l-tooltip>
                    <l-popup :options="{ interactive: true }" ref="popup">
                        <StructureCardXS
                            :structure="structure"
                            :link="
                                route('structures.show', {
                                    structure: structure,
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
