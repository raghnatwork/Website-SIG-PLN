<template>
  <l-map ref="mapRef" style="height: 350px" :zoom="zoom" :center="center">
    <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
    <l-polygon
      v-if="showPolygon"
      :lat-lngs="taroPolygon.latlngs"
      :color="taroPolygon.color"
    ></l-polygon>
  </l-map>

  <button
    @click="play"
    :disabled="isLoading"
    type="button"
    class="bg-blue-500 text-white rounded-md m-5"
  >
    Ke Polygon
  </button>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { LMap, LTileLayer, LPolygon } from "@vue-leaflet/vue-leaflet";
import { latLngBounds } from "leaflet";

// Define reactive properties using ref
const url = ref("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
const attribution = ref(
  '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors'
);
const zoom = ref(8);
const center = ref([47.31322, -1.319482]);

const showPolygon = ref(false); // Initial state to control the visibility
const isLoading = ref(false); // State to control the button's disabled state

const taroPolygon = computed(() => ({
  latlngs: [
    [47.2263299, -1.6222],
    [47.21024000000001, -1.6270065],
    [47.1969447, -1.6136169],
    [47.18527929999999, -1.6143036],
    [47.1794457, -1.6098404],
    [47.1775788, -1.5985107],
    [47.1676598, -1.5753365],
    [47.1593731, -1.5521622],
    [47.1593731, -1.5319061],
    [47.1722111, -1.5143967],
    [47.1960115, -1.4841843],
    [47.2095404, -1.4848709],
    [47.2291277, -1.4683914],
    [47.2533687, -1.5116501],
    [47.2577961, -1.5531921],
    [47.26828069, -1.5621185],
    [47.2657179, -1.589241],
    [47.2589612, -1.6204834],
    [47.237287, -1.6266632],
    [47.2263299, -1.6222],
  ],
  color: "green",
}));

const mapRef = ref(null);

const play = async () => {
  if (isLoading.value) return; // Prevent action if loading

  isLoading.value = true;
  showPolygon.value = !showPolygon.value;

  if (showPolygon.value) {
    // Directly await zoomToPolygon
    await zoomToPolygon();
  } else {
    // Directly await resetMap
    await resetMap();
  }

  isLoading.value = false; // Re-enable the button after completion
};

const zoomToPolygon = async () => {
  if (mapRef.value) {
    const bounds = latLngBounds(taroPolygon.value.latlngs);
    mapRef.value.leafletObject.fitBounds(bounds);
  }

  // resolve(); // Resolve the promise after the operation
  console.log("halo");
};

const resetMap = async () => {
  if (mapRef.value) {
    mapRef.value.leafletObject.setView(center.value, zoom.value); // Reset center and zoom
  }
};

onMounted(() => {});
</script>
