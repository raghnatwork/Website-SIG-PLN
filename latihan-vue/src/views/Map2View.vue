<template>
  <div class="container flex">
    <div id="map" class="z-0"></div>
    <div class="absolute mt-10 ml-40">
      <Input :map="mapInstance" />
    </div>
  </div>
</template>

<script setup>
import Input from "@/components/Input.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

let mapInstance = ref(null);

onMounted(() => {
  // Create the map instance
  const map = L.map("map", {
    center: [-2.548926, 118.0148634],
    zoom: 4,
    attributionControl: false,
    zoomControl: false,
  });

  // Define and add tile layers
  const osm = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
  });

  const osmHOT = L.tileLayer("https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png", {
    maxZoom: 19,
  });

  const baseLayers = {
    "Open Street Map": osm,
    "Open Street Map Hot": osmHOT,
  };

  osm.addTo(map);
  L.control.layers(baseLayers).addTo(map);
  L.control.zoom({ position: "bottomright" }).addTo(map);

  // Assign the map instance to the ref
  mapInstance.value = map;

  // Ensure mapInstance is not null
  if (!mapInstance.value) {
    console.error("Map instance is not available.");
  }
});
</script>

<style scoped>
.container {
  height: 100%;
  width: 100%;
}
#map {
  height: 100vh;
  width: 100vw;
}

.leaflet-control-zoom {
  background-color: red;
}
</style>
