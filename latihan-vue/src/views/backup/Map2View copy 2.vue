<template>
  <div class="container flex w-full h-full">
    <aside class="z-20">
      <header
        class="fixed bg-white flex justify-end mb-[100px] w-full p-4 shadow-xl z-50"
      >
        <Logo_PLN class="h-[40px] w-[auto] mr-5 border-black" />
      </header>
      <div
        class="absolute z-100 left-[20px] top-28 w-[100px] h-[75vh] rounded-md transition-all duration-500 ease-in-out flex flex-col justify-center items-start"
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
            <span class="mb-2">Legend</span>
          </button>
        </div>

        <div
          v-if="showLegend"
          class="dialogue absolute w-[200px] mt-[200px]"
          @click="klikdiLuar"
        >
          <LegendaModal @close="showLegend = !showLegend" class="" />
        </div>
      </div>

      <div
        class="sidebar bg-[#ffffff] text-black h-screen flex flex-col rounded-md transition-all duration-500 ease-in-out absolute overflow-hidden shadow-md items-center"
        :class="{ 'w-[27%] z-20': sidebarOpen, 'w-0 z-20': !sidebarOpen }"
      >
        <div
          id="mekanik"
          class="mt-[150px] p-2 relative flex flex-col justify-center items-center border border-[black] border-separate rounded-md"
        >
          <h1 class="text-2xl underline">Customize</h1>
          <select
            v-model="selected_provinsi"
            v-show="!is_loading"
            class="m-5 absolute left-0 w-[150px]"
          >
            <option value="">Pilih Provinsi</option>
            <option
              class="overflow-y-scroll"
              v-for="list in provinsi"
              :key="list"
              :value="list"
            >
              {{ list.name }}
            </option>
          </select>
          <div
            id="checkbox group"
            class="flex space-x-3 justify-center items-center mt-[130px]"
          >
            <div class="first-row">
              <div class="flex items-center mb-4">
                <input
                  v-model="checkbox[0]"
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
                  v-model="checkbox[1]"
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
            </div>

            <div class="flex items-center mb-4">
              <input
                v-model="checkbox[2]"
                id="default-checkbox"
                type="checkbox"
                value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="default-checkbox"
                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Transmisi</label
              >
            </div>
          </div>
        </div>
      </div>
    </aside>

    <div
      id="container-map"
      style="height: 100%; width: 100%"
      class="absolute right-0"
    >
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
            v-if="checkbox[0]"
            :visible="is_show"
            layer-name="Layer GI"
            :is-loading="is_loading"
            :data="data_gardu_induk"
          >
          </custom-layer-group>
        </span>
        <!-- end of layer group gardu induk -->
        <!-- start of layer group pembangkit -->
        <span v-if="Object.keys(selected_provinsi).length !== 0">
          <custom-layer-group
            v-if="checkbox[1]"
            :visible="is_show"
            layer-name="Layer Kit"
            :is-loading="is_loading"
            :data="data_pembangkit"
          >
          </custom-layer-group>
        </span>
        <!-- end of layer group pembangkit -->
        <!-- start of layer group transmisi -->
        <span v-if="is_show">
          <custom-layer-group
            v-if="checkbox[2]"
            :visible="is_show"
            layer-name="Layer Transmisi"
            :is-loading="is_loading"
            :data="data_transmisi"
          >
          </custom-layer-group>
        </span>

        <!-- end of layer group transmisi -->
      </l-map>

      <!-- mekanik -->
      <div
        class="flex space-x-6 mt-[100px] z-10 absolute right-0 top-[30px] bg-red-500"
      >
        <!-- ini selection provinsi -->
        <Menu
          v-show="!is_loading"
          as="div"
          class="relative inline-block text-left"
        >
          <div>
            <MenuButton
              class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
            >
              Pilih Provinsi
              <ChevronDownIcon
                class="-mr-1 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
            </MenuButton>
          </div>

          <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <MenuItems
              class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
              <div class="py-1">
                <MenuItem
                  v-for="list in provinsi"
                  :key="list"
                  v-slot="{ active }"
                >
                  <button
                    :class="[
                      active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                      'block w-full px-4 py-2 text-left text-sm',
                    ]"
                    @click="selected_provinsi = list"
                  >
                    {{ list.name }}
                  </button>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>
        <!-- batas selection provinsi -->
        <!-- ini selection tipe -->
        <!-- Dropdown for selecting type -->
        <select v-model="selectedType" class="form-select">
          <option value="" selected>Pilih Tipe</option>
          <option value="Gardu Induk">Gardu Induk</option>
          <option value="Pembangkit">Pembangkit</option>
          <option value="Transmisi">Transmisi</option>
        </select>
        <!-- batas selection tipe -->
        <!-- ini selection nama aset -->
        <select name="" id="">
          <option value="" selected>Pilih Aset</option>
          <option
            v-for="(aset, i) in filter.cekAset"
            :key="i"
            :value="aset.nama_aset"
          >
            {{ aset.nama_aset }}
          </option>
        </select>
        <!-- batas selection nama aset -->
      </div>
      <!-- batas mekanik -->
    </div>
  </div>
</template>

<script setup>
//icon

import ChevronRight from "@/components/icons/ChevronRight.vue";
import ChevronLeft from "@/components/icons/ChevronLeft.vue";
import Logo_PLN from "@/components/icons/Logo_PLN.vue";
import MarkQuestion from "@/components/icons/MarkQuestion.vue";

//component
import CustomLayerGroup from "../../components/LayerGroup.vue";
import Sidebar from "@/components/backup/Sidebar.vue";
import LegendaModal from "@/components/LegendaModal.vue";

//library
import "leaflet/dist/leaflet.css";
import { latLng, latLngBounds } from "leaflet";
import {
  LMap,
  LTileLayer,
  LControl,
  LControlZoom,
} from "@vue-leaflet/vue-leaflet";
import { ref, onMounted, watch, toRaw, computed } from "vue";
import axios from "axios";

// tailwind
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

// import json data
// import json_gi from "@/components/json/aset_gardu_induk.json";
// import json_kit from "@/components/json/aset_pembangkit.json";
// import json_trans from "@/components/json/aset_transmisi.json";

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
let selectedType = ref("");
let selectedAset = ref("");
//batas dom
let multipolygon_provinsi = ref([]);
let data_gardu_induk = ref([]);
let data_pembangkit = ref([]);
let data_transmisi = ref([]);
let cekAset = ref([]);

//dom sidebar

const checkbox = ref([true, true, true]);
const showLegend = ref(false);
const sidebarOpen = ref(false);
function showSidebar() {
  sidebarOpen.value = !sidebarOpen.value;
}

const klikdiLuar = (event) => {
  if (event.target === event.currentTarget) {
    showLegend.value = !showLegend.value;
  }
};

const filterCheckbox = computed(() => {
  if (checkbox[0].value === false) {
    GarduInduk.value = false;
  }
  if (checkbox[0].value == true) {
    Pembangkit.value = true;
  }
  if (checkbox[0].value == true) {
    Transmisi.value = true;
  }
});
//batas dom sidebar
//ini filter tipe
let filter = computed(() => {
  if (selectedType.value === "") {
    return {
      cekAset: [],
      garduInduk: data_gardu_induk.value,
      pembangkit: data_pembangkit.value,
      transmisi: data_transmisi.value,
    };
  }
  if (selectedType.value === "Gardu Induk") {
    return {
      cekAset: data_gardu_induk.value,
      garduInduk: data_gardu_induk.value,
      pembangkit: [],
      transmisi: [],
    };
  }
  if (selectedType.value === "Pembangkit") {
    return {
      cekAset: data_pembangkit.value,
      garduInduk: [],
      pembangkit: data_pembangkit.value,
      transmisi: [],
    };
  }
  if (selectedType.value === "Transmisi") {
    return {
      cekAset: data_transmisi.value,
      garduInduk: [],
      pembangkit: [],
      transmisi: data_transmisi.value,
    };
  }
});

onMounted(async () => {
  is_loading.value = true;
  // console.log(filterTipeGI);

  await getDataProvinsi();
  is_loading.value = false;
});

watch(selected_provinsi, async (newvalue, oldvalue) => {
  // if dropdown value changed
  if (newvalue !== oldvalue) {
    is_show.value = false;
    is_loading.value = true;
    // load multipolygon and assets if any
    await loadMultiPolygonData();
    await getDataGarduInduk();
    await getDataPembangkit();
    await getDataTransmisi();
    is_show.value = true;
    is_loading.value = false;
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

let zoomPolygon = ref([]);
async function loadMultiPolygonData() {
  // Assign area based on selected provinsi
  multipolygon_provinsi.value = provinsi.value.filter(
    (x) => x.name === selected_provinsi.value.name
  );

  // zoomPolygon.value = {
  //   kordinat: multipolygon_provinsi.value[0],
  // };
  // console.log(zoomPolygon.value);
  // ambilPolygon(zoomPolygon.value.kordinat);
  // await zoomToPolygon(zoomPolygon.value);
}

// Flatten function definition
function ambilPolygon(area) {
  let coordinates = area.coordinates.map((polygon) => {
    return polygon.map((ring) => {
      return ring.map((coord) => [coord[1], coord[0]]);
    });
  });

  console.log(coordinates);
  // let multipolygon = L.polygon(coordinates, {
  //   color: "blue",
  //   fillColor: "#3388ff",
  //   fillOpacity: 0.2,
  // }).addTo(layerGroupPolygon);

  // map.value.fitBounds(multipolygon.getBounds());
}

const zoomToPolygon = async (x) => {
  if (map.value) {
    // Flatten and format coordinates
    const flatCoords = flattenCoordinates(x.kordinat);

    // Ensure there's at least one valid coordinate
    if (flatCoords.length > 0) {
      try {
        // Create bounds from the coordinates
        let bounds = latLngBounds(flatCoords);
        map.value.leafletObject.fitBounds(bounds);
      } catch (error) {
        console.error("Error creating bounds:", error);
      }
    } else {
      console.error("No valid coordinates to zoom to.");
    }
  } else {
    console.error("Map is not available.");
  }
};

const resetMap = () => {
  return new Promise((resolve) => {
    if (map.value) {
      map.value.leafletObject.setView(
        map_properties.center,
        map_properties.zoom
      ); // Reset center and zoom
    }
    resolve(); // Resolve the promise after the operation
  });
};
//batas zoom in polygon

async function getDataGarduInduk() {
  let marker_gi = [];

  await axios
    .get(base_url + "/aset_gardu_induk/" + selected_provinsi.value.id)
    .then((response) => {
      let data = response.data;
      for (let i = 0; i < data.length; i++) {
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
    });
}

async function ready() {
  // when map obj ready, start execute function here
  // refresh map tiles / remove grey background
  map.value.leafletObject.invalidateSize();
}
</script>

<style scoped></style>
