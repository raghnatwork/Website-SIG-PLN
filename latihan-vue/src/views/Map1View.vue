<template>
  <div class="container flex w-full h-full">
    <aside class="z-20">
      <header
        class="fixed bg-white flex justify-between mb-[100px] w-full p-4 shadow-xl z-50"
      >
        <a href="#">
          <Logo_PLN class="h-[40px] w-[auto] mr-5 border-black" />
        </a>

        <a href="#" @click="logout">
          <Logout class="h-[40px]" />
        </a>
      </header>
      <div
        class="absolute z-100 left-[20px] top-28 w-[0] h-[75vh] rounded-md transition-all duration-500 ease-in-out flex flex-col justify-center items-start bg-red-500"
        :class="{ 'ml-[30%]': sidebarOpen }"
      >
        <button
          @click="showSidebar"
          class="my-2 mx-2 text-black absolute top-0 bg-white rounded-md flex justify-center items-center"
        >
          <span class="mt-[2px]">
            <component :is="sidebarOpen ? ChevronLeft : ChevronRight" />
          </span>
        </button>

        <div
          class="text-black absolute bottom-0 flex items-center rounded-md space-x-[10px] bg-white"
        >
          <button
            @click="showLegend = !showLegend"
            class="text-black left-0 flex justify-center items-center my-2 mx-2 space-x-1"
          >
            <span><MarkQuestion /></span>
            <span class="mb-2">Legenda</span>
          </button>
        </div>

        <div v-if="showLegend" class="dialogue absolute w-[200px] bottom-0 mb-[60px]">
          <LegendaModal @click="showLegend = !showLegend" class="" />
        </div>
      </div>

      <div
        class="sidebar bg-[#ffffff] text-black h-screen flex flex-col rounded-md transition-all duration-500 ease-in-out absolute overflow-hidden shadow-md items-center"
        :class="{ 'w-[27%] z-20': sidebarOpen, 'w-0 z-20': !sidebarOpen }"
      >
        <div
          v-if="is_loading"
          class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 z-[100]"
        >
          <!-- Animasi loading -->
          <div
            class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500"
          ></div>
        </div>

        <div
          id="mekanik"
          class="mt-[15px] p-2 relative flex flex-col justify-center items-center rounded-md z-50 w-full"
        >
          <select
            v-model="selected_provinsi"
            class="mt-[20%] mb-2 left-0 h-[50px] rounded-md w-[75%] ml-[3%]"
          >
            <option :value="{}" selected>Pilih Provinsi</option>
            <option class="p-6" v-for="list in provinsi" :key="list" :value="list">
              {{ list.name }}
            </option>
          </select>

          <div
            id="checkbox group"
            class="flex flex-col justify-start items-start mt-[2%] w-[70%]"
          >
            <div class="flex items-center mb-4">
              <input
                v-model="checkbox.GI"
                id="default-checkbox"
                type="checkbox"
                value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="default-checkbox"
                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Gardu Induk</label
              >
            </div>

            <div class="flex items-center mb-4">
              <input
                v-model="checkbox.KIT"
                id="default-checkbox"
                type="checkbox"
                value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="default-checkbox"
                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Pembangkit</label
              >
            </div>

            <div class="flex items-center mb-4">
              <input
                v-model="checkbox.TI"
                id="default-checkbox"
                type="checkbox"
                value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="default-checkbox"
                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Transmisi
              </label>
            </div>

            <!-- batas checkbox -->
          </div>
          <!-- HR -->
          <div class="flex w-full bg-black text-[1px]">test</div>

          <!-- Info -->
          <div id="info" class="w-[75%] ml-[3%] mt-[3%] space-y-4">
            <div
              class="border-[2px] border-black rounded-md w-[40%] flex items-center space-x-4"
            >
              <IconGarduInduk width="35px" height="35px" />
              <p>{{ nomorGI || 0 }}</p>
            </div>
            <div
              class="border-[2px] border-black rounded-md w-[40%] flex items-center space-x-4"
            >
              <IconPembangkit width="35px" height="35px" />
              <p>{{ nomorPembangkit || 0 }}</p>
            </div>
            <div
              class="border-[2px] border-black rounded-md w-[40%] flex items-center space-x-4"
            >
              <IconLine width="35px" height="35px" />
              <p>{{ nomorTransmisi || 0 }}</p>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <div id="container-map" style="height: 100%; width: 100%" class="absolute flex">
      <l-map
        class="z-0"
        ref="map"
        :zoom="map_properties.zoom"
        :center="map_properties.center"
        :options="{ zoomControl: false, attributionControl: false }"
        :useGlobalLeaflet="false"
        @ready="ready"
      >
        <l-control-zoom position="bottomright" />
        <l-tile-layer
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          layer-type="base"
          name="OpenStreetMap"
        ></l-tile-layer>
        <!-- start of layer group batas provinsi -->
        <custom-layer-group
          v-if="Object.keys(selected_provinsi).length !== 0"
          :visible="is_show"
          layer-name="Layer Batas Provinsi"
          :is-loading="is_loading"
          :data="multipolygon_provinsi"
        >
        </custom-layer-group>
        <!-- end of layer group batas provinsi -->
        <!-- start of layer group gardu induk -->
        <span v-if="Object.keys(selected_provinsi).length !== 0">
          <custom-layer-group
            v-if="checkbox.GI"
            :visible="is_show"
            layer-name="Layer GI"
            :is-loading="is_loading"
            :data="data_gardu_induk"
            @markerClick="onMarkerClick"
          >
          </custom-layer-group>
        </span>
        <!-- end of layer group gardu induk -->
        <!-- start of layer group pembangkit -->
        <span v-if="Object.keys(selected_provinsi).length !== 0">
          <custom-layer-group
            v-if="checkbox.KIT"
            :visible="is_show"
            layer-name="Layer Kit"
            :is-loading="is_loading"
            :data="data_pembangkit"
            @markerClick="onMarkerClick"
          >
          </custom-layer-group>
        </span>
        <!-- end of layer group pembangkit -->
        <!-- start of layer group transmisi -->
        <span v-if="is_show">
          <custom-layer-group
            v-if="checkbox.TI"
            :visible="is_show"
            layer-name="Layer Transmisi"
            :is-loading="is_loading"
            :data="data_transmisi"
          >
          </custom-layer-group>
        </span>

        <!-- end of layer group transmisi -->
      </l-map>

      <!-- Marker Popup -->
      <span
        v-if="markerPopup.button"
        class="z-10 absolute bottom-0 ml-[430px] mb-[190px]"
      >
        <MarkerModal @close="markerPopup.button = false" :marker="markerPopup.marker" />
      </span>
      <!-- batas marker popup -->
    </div>
  </div>
</template>

<script setup>
//icon
import ChevronRight from "@/components/icons/ChevronRight.vue";
import ChevronLeft from "@/components/icons/ChevronLeft.vue";
import Logo_PLN from "@/components/icons/Logo_PLN.vue";
import MarkQuestion from "@/components/icons/MarkQuestion.vue";
import IconLine from "@/components/icons/IconLine.vue";
import Logout from "@/components/icons/Logout.vue";
import IconPembangkit from "@/components/icons/IconPembangkit.vue";
import IconGarduInduk from "@/components/icons/IconGarduInduk.vue";

//component
import Sidebar from "@/components/backup/Sidebar.vue";
import CustomLayerGroup from "../components/LayerGroup.vue";
import LegendaModal from "@/components/LegendaModal.vue";
import MarkerModal from "@/components/MarkerModal.vue";

//library
import { useRouter } from "vue-router"; // Pastikan ini ada
import "leaflet/dist/leaflet.css";
import { latLng, latLngBounds } from "leaflet";
import { LMap, LTileLayer, LControl, LControlZoom } from "@vue-leaflet/vue-leaflet";
import { ref, onMounted, watch, toRaw, computed } from "vue";
import axios from "axios";

// tailwind
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

const nomorGI = ref();
const nomorPembangkit = ref();
const nomorTransmisi = ref();

const base_url = "http://localhost:8001/api/map";
// used to get map obj
const map = ref(null);
const map_properties = {
  zoom: 5,
  center: [-2.548926, 116.0148634],
};
let is_loading = ref(false);
let is_show = ref(false);
let provinsi = ref([]);

//dom
let selected_provinsi = ref({});
let markerPopup = ref({
  button: false,
  marker: null,
});
//batas dom
let multipolygon_provinsi = ref([]);
let data_gardu_induk = ref([]);
let data_pembangkit = ref([]);
let data_transmisi = ref([]);

//dom sidebar
const checkbox = ref({
  GI: true,
  KIT: true,
  TI: true,
});
const showLegend = ref(false);
const sidebarOpen = ref(false);
function showSidebar() {
  sidebarOpen.value = !sidebarOpen.value;
  if (!sidebarOpen.value) {
    showLegend.value = false;
  }
}
//batas dom sidebar
let originalAssets = ref([]);

onMounted(async () => {
  is_loading.value = true;
  // console.log(filterTipeGI);

  await getDataProvinsi();
  is_loading.value = false;
});

watch(selected_provinsi, async (newvalue, oldvalue) => {
  // if dropdown value changed

  nomorGI.value = 0;
  nomorPembangkit.value = 0;
  nomorTransmisi.value = 0;

  if (!newvalue || Object.keys(newvalue).length === 0) {
    alert("Data tidak valid");
    return; // Stop execution here
  }

  if (newvalue !== oldvalue) {
    is_show.value = false;
    is_loading.value = true;
    // load multipolygon and assets if any
    await loadMultiPolygonData();
    // await playZoom();
    await getDataGarduInduk();
    await getDataPembangkit();
    await getDataTransmisi();
    is_show.value = true;
    is_loading.value = false;

    filterAssets();
  }
});

async function getDataProvinsi() {
  await axios
    .get(base_url + "/all_provinsi")
    .then((response) => {
      provinsi.value = response?.data ? response.data : null;
      provinsi.value.sort(function (a, b) {
        return a.name.localeCompare(b.name);
      });
    })
    .catch((error) => {
      console.error(error);
    });
}

let zoomPolygon = ref({});
async function loadMultiPolygonData() {
  // Assign area based on selected provinsi
  multipolygon_provinsi.value = provinsi.value.filter(
    (x) => x.name === selected_provinsi.value.name
  );

  // console.log(multipolygon_provinsi.value[0].area);
  zoomPolygon = {
    zoom: multipolygon_provinsi.value[0].zoom,
    center_point: multipolygon_provinsi.value[0].center_point.coordinates,
  };

  let [lng, lat] = zoomPolygon.center_point;
  let zoom = zoomPolygon.zoom;
  map.value.leafletObject.flyTo([lat, lng], zoom);
}
//play zoom
function onMarkerClick(marker) {
  console.log(marker);
  const { lat, lng } = marker.position;
  map.value.leafletObject.flyTo([lat, lng], 12); // Ubah zoom level sesuai kebutuhan
  showLegend.value = false;
  sidebarOpen.value = false;
  //show marker popup

  markerPopup.value.button = true;
  markerPopup.value.marker = marker;

  console.log(markerPopup.value);
}
// batas play zoom
async function getDataGarduInduk() {
  let marker_gi = [];

  await axios
    .get(base_url + "/aset_gardu_induk/" + selected_provinsi.value.id)
    .then((response) => {
      let data = response.data;
      for (let i = 0; i < data.length; i++) {
        nomorGI.value = i + 1;
        const newformat = {
          position: latLng(
            data[i].geometry.coordinates[1],
            data[i].geometry.coordinates[0]
          ),
          nama_aset: data[i].nama_aset,
          nama_tipe: "Gardu Induk",
        };
        marker_gi.push(newformat);
      }
      data_gardu_induk.value = marker_gi;
    });
}

async function getDataPembangkit() {
  let marker_kit = [];

  await axios
    .get(base_url + "/aset_pembangkit/" + selected_provinsi.value.id)
    .then((response) => {
      let data = response.data;
      // console.log("ini data fetching", data);
      for (let i = 0; i < data.length; i++) {
        nomorPembangkit.value = i + 1;
        const newformat = {
          position: latLng(
            data[i].geometry.coordinates[1],
            data[i].geometry.coordinates[0]
          ),
          nama_aset: data[i].nama_aset, //ini apa ?
          nama_tipe: "Pembangkit",
        };
        marker_kit.push(newformat);
      }
      data_pembangkit.value = marker_kit;
      // console.log("ini data pembangkit", data_pembangkit.value);
    });
}

async function getDataTransmisi() {
  await axios
    .get(base_url + "/aset_transmisi/" + selected_provinsi.value.id)
    .then((response) => {
      let data = response.data;
      data_transmisi.value = data;
      nomorTransmisi.value = data.length;
    });
}

async function ready() {
  // when map obj ready, start execute function here
  // refresh map tiles / remove grey background
  map.value.leafletObject.invalidateSize();
}

//logout
const router = useRouter(); // Inisialisasi router
// Fungsi logout
const logout = () => {
  // Hapus token dari localStorage atau cookies
  localStorage.removeItem("auth_token"); // Jika token disimpan di localStorage

  // Alihkan pengguna ke halaman login setelah logout
  router.push("/login");
};
</script>

<style scoped></style>
