<template>
  <div id="map" style="height: 100%; width: 100%">
    <l-map
      ref="map"
      :zoom="map_properties.zoom"
      :center="map_properties.center"
      :options="{ zoomControl: false }"
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
      <custom-layer-group
        v-if="Object.keys(selected_provinsi).length !== 0"
        :visible="is_show"
        layer-name="Layer GI"
        :is-loading="is_loading"
        :data="filter.garduInduk"
      >
      </custom-layer-group>
      <!-- end of layer group gardu induk -->
      <!-- start of layer group pembangkit -->
      <custom-layer-group
        v-if="Object.keys(selected_provinsi).length !== 0"
        :visible="is_show"
        layer-name="Layer Kit"
        :is-loading="is_loading"
        :data="filter.pembangkit"
      >
      </custom-layer-group>
      <!-- end of layer group pembangkit -->
      <!-- start of layer group transmisi -->
      <custom-layer-group
        v-if="is_show"
        :visible="is_show"
        layer-name="Layer Transmisi"
        :is-loading="is_loading"
        :data="filter.transmisi"
      >
      </custom-layer-group>
      <!-- end of layer group transmisi -->
      <l-control position="topright" class="flex space-x-6 mt-3">
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
      </l-control>
    </l-map>
  </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css";
import { latLng } from "leaflet";
import {
  LMap,
  LTileLayer,
  LControl,
  LControlZoom,
} from "@vue-leaflet/vue-leaflet";
import { ref, onMounted, watch, toRaw, computed } from "vue";
import axios from "axios";
import CustomLayerGroup from "../../components/LayerGroup.vue";

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

// let filterTipeGI = computed(() => {
//   if (selectedType.value == "") {
//     cekAset = [];
//     return data_gardu_induk.value;
//   }
//   if (selectedType.value == "Gardu Induk") {
//     cekAset = data_gardu_induk.value;
//     return data_gardu_induk.value;
//   }
//   if (selectedType.value == "Pembangkit" || selectedType.value == "Transmisi") {
//     return [];
//   }
// });

// let filterTipeKIT = computed(() => {
//   if (selectedType.value == "") {
//     cekAset = [];
//     return data_pembangkit.value;
//   }
//   if (selectedType.value == "Pembangkit") {
//     cekAset = data_pembangkit.value;
//     return data_pembangkit.value;
//   }
//   if (
//     selectedType.value == "Gardu Induk" ||
//     selectedType.value == "Transmisi"
//   ) {
//     return [];
//   }
// });

// let filterTipeTI = computed(() => {
//   if (selectedType.value == "") {
//     cekAset = [];
//     return data_transmisi.value;
//   }
//   if (selectedType.value == "Transmisi") {
//     // data_transmisi.value.forEach((item)=>{

//     // })
//     cekAset = data_transmisi.value;
//     return data_transmisi.value;
//   }
//   if (
//     selectedType.value == "Pembangkit" ||
//     selectedType.value == "Gardu Induk"
//   ) {
//     return [];
//   }
// });
//ini batas filter tipe

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

async function loadMultiPolygonData() {
  // assign area based on selected provinsi
  multipolygon_provinsi.value = provinsi.value.filter(
    (x) => x.name === selected_provinsi.value.name
  );
}

async function getDataGarduInduk() {
  // await axios
  //   .get(base_url + "/assets-main-generator/" + selected_provinsi.value.id)
  //   .then((response) => {
  //     data_gardu_induk.value = response?.data ? response.data : null;
  //   })
  //   .catch((error) => {
  //     console.error(error);
  //   });

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

  //   const data = json_gi.filter(
  //     (x) => x.provinsi_id === selected_provinsi.value.id
  //   );

  //   for (let i = 0; i < data.length; i++) {
  //     const newformat = {
  //       position: latLng(
  //         data[i].geometry.coordinates[1],
  //         data[i].geometry.coordinates[0]
  //       ),
  //       nama: data[i].nama_aset,
  //     };
  //     marker_gi.push(newformat);
  //   }

  //   data_gardu_induk.value = marker_gi;
}

async function getDataPembangkit() {
  // await axios
  //   .get(base_url + "/assets-main-generator/" + selected_provinsi.value.id)
  //   .then((response) => {
  //     data_pembangkit.value = response?.data ? response.data : null;
  //   })
  //   .catch((error) => {
  //     console.error(error);
  //   });

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

  // const data = json_kit.filter(
  //   (x) => x.provinsi_id === selected_provinsi.value.id
  // );

  // for (let i = 0; i < data.length; i++) {
  //   const newformat = {
  //     position: latLng(
  //       data[i].geometry.coordinates[1],
  //       data[i].geometry.coordinates[0]
  //     ),
  //     nama: data[i].nama_aset,
  //   };
  //   marker_kit.push(newformat);
  // }

  // data_pembangkit.value = marker_kit;
}

async function getDataTransmisi() {
  // await axios
  //   .get(base_url + "/assets-main-generator/" + selected_provinsi.value.id)
  //   .then((response) => {
  //     data_transmisi.value = response?.data ? response.data : null;
  //   })
  //   .catch((error) => {
  //     console.error(error);
  //   });

  // let marker_TI = [];

  await axios
    .get(base_url + "/aset_transmisi/" + selected_provinsi.value.id)
    .then((response) => {
      let data = response.data;
      // for (let i = 0; i < data.length; i++) {
      //   const item = data[i];
      //   const coordinates = item.geometry.coordinates;

      //   const flattenedCoordinates = coordinates.flat().map((coord) => ({
      //     lat: coord[1], // Latitude
      //     lng: coord[0], // Longitude
      //   }));

      //   const newformat = {
      //     coordinates: flattenedCoordinates,
      //     nama: item.nama_aset, //ini apa ?
      //     nama_tipe: item.nama_tipe,
      //   };
      //   marker_TI.push(newformat);
      // }
      data_transmisi.value = data;

      // data_transmisi.value.forEach((item) => {
      //   if (item && item.nama_aset) {
      //     console.log("Asset Name:", item.nama_aset);
      //   } else {
      //     console.error("Item or `nama_aset` not found:", item);
      //   }
      // });
    });
  // console.log(toRaw(data_transmisi.value));

  // data_transmisi.value = json_trans.filter(
  //   (x) => x.provinsi_id === selected_provinsi.value.id
  // );
}

async function ready() {
  // when map obj ready, start execute function here
  // refresh map tiles / remove grey background
  map.value.leafletObject.invalidateSize();
}
</script>

<style scoped></style>
