<script setup>
import IconGarduInduk from "./icons/IconGarduInduk.vue";
import IconPembangkit from "./icons/IconPembangkit.vue";
import pembangkitIconUrl from "@/components/icons/iconKIT.svg";
import garduIndukIconUrl from "@/components/icons/iconGI.svg";
import { computed, defineEmits, defineProps } from "vue";
import {
  LMarker,
  LIcon,
  LLayerGroup,
  LGeoJson,
} from "@vue-leaflet/vue-leaflet";
import { marker } from "leaflet";

const props = defineProps({
  layerName: { type: String },
  data: {
    type: Array,
    default: () => [],
  },
  visible: { type: Boolean },
  isLoading: { type: Boolean },
  icon: { type: String },
});

const emits = defineEmits({
  markerClick: null,
});

// const emits = defineEmits({});
const layer_name = computed(() => {
  return props.layerName;
});

const data = computed(() => {
  return props.data;
});

const is_loading = computed(() => {
  return props.isLoading;
});

const visible = computed(() => {
  return props.visible;
});

const type = computed(() => {
  if (props.layerName === "Layer Batas Provinsi") {
    return "area";
  } else if (props.layerName === "Layer Transmisi") {
    return "line";
  } else if (
    props.layerName === "Layer GI" ||
    props.layerName === "Layer Kit"
  ) {
    return "point";
  }
  return false;
});

const styleFunction = computed(() => {
  return () => ({
    color: "black",
    weight: 2,
    opacity: 3,
    // fillOpacity: 0.5,
  });
});

const getIcon = (namaTipe) => {
  if (namaTipe === "Gardu Induk") {
    return garduIndukIcon.value;
  } else if (namaTipe === "Pembangkit") {
    return pembangkitIcon.value;
  }
  return garduIndukIcon.value;
};

const garduIndukIcon = computed(() =>
  L.icon({
    iconUrl: garduIndukIconUrl,
    iconSize: [30, 30],
    iconAnchor: [15, 30],
    popupAnchor: [0, -30],
  })
);

const pembangkitIcon = computed(() =>
  L.icon({
    iconUrl: pembangkitIconUrl,
    iconSize: [30, 30],
    iconAnchor: [15, 30],
    popupAnchor: [0, -30],
  })
);

const zoomMarker = (marker) => {
  emits("markerClick", marker);
};
</script>
<template>
  <span>
    <!-- :visible="visible && !is_loading" -->
    <l-layer-group layer-type="overlay" :name="layer_name">
      <!-- render geojson if layer is named Layer Batas Provinsi -->
      <span v-if="type === 'area'">
        <l-geo-json :geojson="data[0].area"></l-geo-json>
      </span>
      <!-- render narjer if layer is named Layer GI or Layer Kit -->
      <span v-else-if="type === 'point'">
        <l-marker
          v-for="(marker, idx) in data"
          :key="idx"
          :lat-lng="marker.position"
          :options="{
            opacity: 1,
            riseOnHover: true,
            draggable: false,
          }"
          :icon="getIcon(marker.nama_tipe)"
          @click="zoomMarker(marker)"
        >
        </l-marker>
      </span>
      <span v-else-if="type === 'line'">
        <l-geo-json
          v-for="(line, idx) in data"
          :key="idx"
          :geojson="line.geometry"
          :options-style="styleFunction"
        ></l-geo-json>
      </span>
    </l-layer-group>
  </span>
</template>
<style scoped></style>
