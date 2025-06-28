<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { Events } from "leaflet";
import iconRed from "../components/icons/red.png";
import LocationRed from "./icons/LocationRed.svg";
import LocationYellow from "./icons/LocationYellow.svg";

const provinsi = ref([]);
const props = defineProps({
  map: Object, // <-- Add this line to define the prop
});
const api_provinsi = "http://localhost:8001/api/map/all_provinsi";
let mapInstance = ref(null);
//all variabel
let allDataGardu = [];
let allDataPembangkit = [];
let allDataTransmisi = [];
// layer group
let layerGroupGardu = L.layerGroup();
let layerGroupPembangkit = L.layerGroup();
let layerGroupTransmisi = L.layerGroup();
let layerGroupPolygon = L.layerGroup();
//icon
// Icon untuk Pembangkit
const pembangkitIcon = L.icon({
  iconUrl: LocationYellow,
  iconSize: [20, 40], // Ukuran icon
  iconAnchor: [16, 32], // Posisi ikon terhadap titik koordinat
  popupAnchor: [0, -24], // Posisi popup terhadap ikon
});

// Icon untuk Gardu Induk
const garduIndukIcon = L.icon({
  iconUrl: LocationRed,
  iconSize: [20, 40],
  iconAnchor: [16, 32],
  popupAnchor: [0, -24],
});

onMounted(async () => {
  console.log("Provinsi data:", provinsi.value);
  // console.log("ini map instance ", mapInstance);
  // if (!mapInstance) {
  //   return console.error("Map instance is not defined");
  // }
  //fetching data provinsi untuk list dropdown

  const response = await axios.get(api_provinsi);
  provinsi.value = response.data;
  console.log(provinsi);

  // axios
  //   .get(api_provinsi)
  //   .then((response) => {
  //     provinsi.value = response.data;
  //     console.log(provinsi);
  //   })
  //   .catch((error) => {
  //     console.error("ada eror ", error);
  //   });

  console.log("test");
}); //batas on mounted

watch(
  () => props.map,
  (newMap) => {
    console.log("Map prop changed:", newMap); // Verify if this is logged
    if (newMap) {
      // mapInstance = newMap;
      // console.log("Map instance updated:", mapInstance.value);
      mapInstance.value = newMap;
      console.log("Map instance updated:", mapInstance.value);

      // console.log("ini map instance yang ada propnya", mapInstance);
      const selectProvinsi = document.getElementById("pilih-provinsi");
      selectProvinsi.addEventListener("change", async (event) => {
        let provinsiId = event.target.value;
        console.log("ini provinsi id", provinsiId);

        allDataGardu = [];
        allDataPembangkit = [];
        allDataTransmisi = [];

        //menunggu objek map instance dikirim dari parrentnya dulu
        if (mapInstance.value) {
          await mapInstance.value.removeLayer(layerGroupGardu);
          await mapInstance.value.removeLayer(layerGroupPembangkit);
          await mapInstance.value.removeLayer(layerGroupTransmisi);
          await mapInstance.value.removeLayer(layerGroupPolygon);

          layerGroupGardu.clearLayers();
          layerGroupPembangkit.clearLayers();
          layerGroupTransmisi.clearLayers();
          layerGroupPolygon.clearLayers();

          layerGroupGardu = new L.layerGroup();
          layerGroupPembangkit = new L.layerGroup();
          layerGroupTransmisi = new L.layerGroup();
          layerGroupPolygon = new L.layerGroup();

          if (provinsiId) {
            ambilProvinsi(provinsiId);

            try {
              // Fetch Gardu Induk
              let api_gardu_induk = `http://localhost:8001/api/map/aset_gardu_induk/${provinsiId}`;
              let responseGardu = await axios.get(api_gardu_induk);
              allDataGardu = responseGardu.data;
              console.log("Gardu Induk:", allDataGardu.length);

              if (allDataGardu.length === 0) {
                console.log("no data");
              } else {
                console.log("cek", allDataGardu);
                addMarkers(allDataGardu, garduIndukIcon, "Gardu_Induk");
              }

              // Fetch Pembangkit
              let api_pembangkit = `http://localhost:8001/api/map/aset_pembangkit/${provinsiId}`;
              let responsePembangkit = await axios.get(api_pembangkit);
              allDataPembangkit = responsePembangkit.data;
              // console.log("Pembangkit:", allDataPembangkit);
              if (allDataPembangkit.length === 0) {
                console.log("no data");
              } else {
                console.log("cek", allDataPembangkit);
                addMarkers(allDataPembangkit, pembangkitIcon, "Pembangkit");
              }

              // Fetch Transmisi
              let api_transmisi = `http://localhost:8001/api/map/aset_transmisi/${provinsiId}`;
              let responseTransmisi = await axios.get(api_transmisi);
              allDataTransmisi = responseTransmisi.data;
              // console.log("Transmisi:", allDataTransmisi);
              if (allDataTransmisi.length === 0) {
                console.log("no data");
              } else {
                console.log("cek", allDataTransmisi);
                addPolylines(allDataTransmisi);
              }
              //batas
            } catch (error) {
              console.error("Error fetching data:", error);
            }
            // //Gardu Induk
            // let api_gardu_induk = http://localhost:8001/api/map/aset_gardu_induk/${provinsiId};
            // axios.get(api_gardu_induk).then((response) => {
            //   let data = response?.data ? response.data : [];
            //   allDataGardu = data;
            //   console.log("ini gardu induk", allDataGardu);

            //   addMarkers(allDataGardu, garduIndukIcon, "Gardu_Induk");
            // });

            // //Pembangkit
            // let api_pembangkit = http://localhost:8001/api/map/aset_pembangkit/${provinsiId};
            // axios.get(api_pembangkit).then((response) => {
            //   console.log("ini response", response);
            //   let data = response?.data ? response.data : null;
            //   allDataPembangkit = data;
            //   console.log("ini pembangkit", allDataPembangkit);

            //   addMarkers(allDataPembangkit, pembangkitIcon, "Pembangkit");
            // });

            // //Transmisi
            // let api_transmisi = http://localhost:8001/api/map/aset_transmisi/${provinsiId};

            // // console.log(gardu_induk);
            // axios.get(api_transmisi).then((response) => {
            //   let data = response?.data ? response.data : null;
            //   allDataTransmisi = data;
            //   console.log("ini Transmisi", allDataTransmisi);

            //   addPolylines(allDataTransmisi);
            // });
          }
        }
      }); //batas dropdown provinsi

      //dropdown tipe
      const selectTipe = document.getElementById("pilih-tipe");
      selectTipe.addEventListener("change", (event) => {
        let tipe = event.target.value;
        if (mapInstance.value) buatTipe(tipe);
      }); //batas dropdown tipe
    }
  }
); //batas watch function

//Function tipe
function buatTipe(tipe) {
  if (mapInstance.value) {
    mapInstance.value.removeLayer(layerGroupGardu);
    mapInstance.value.removeLayer(layerGroupPembangkit);
    mapInstance.value.removeLayer(layerGroupTransmisi);
    layerGroupGardu.clearLayers();
    layerGroupPembangkit.clearLayers();
    layerGroupTransmisi.clearLayers();
    layerGroupPolygon.clearLayers();
    console.log("ini tipe", tipe);

    if (tipe) {
      if (tipe === "Gardu Induk") {
        // mapInstance.removeLayer(layerGroupGardu);
        // mapInstance.removeLayer(layerGroupPembangkit);
        // mapInstance.removeLayer(layerGroupTransmisi);
        addMarkers(allDataGardu, garduIndukIcon, "Gardu_Induk");
      } else if (tipe === "Pembangkit") {
        // mapInstance.removeLayer(layerGroupGardu);
        // mapInstance.removeLayer(layerGroupPembangkit);
        // mapInstance.removeLayer(layerGroupTransmisi);
        addMarkers(allDataPembangkit, pembangkitIcon, "Pembangkit");
      } else if (tipe === "Transmisi") {
        // mapInstance.removeLayer(layerGroupGardu);
        // mapInstance.removeLayer(layerGroupPembangkit);
        // mapInstance.removeLayer(layerGroupTransmisi);
        addPolylines(allDataTransmisi);
      }
    } else {
      // mapInstance.removeLayer(layerGroupGardu);
      // mapInstance.removeLayer(layerGroupPembangkit);
      // mapInstance.removeLayer(layerGroupTransmisi);
      addMarkers(allDataGardu, garduIndukIcon, "Gardu_Induk");
      addMarkers(allDataPembangkit, pembangkitIcon, "Pembangkit");
      addPolylines(allDataTransmisi);
    }
  }
}
// Function to fetch and process data
function ambilProvinsi(id_provinsi) {
  console.log("ini provinsi id yang diambil", id_provinsi);

  axios
    .get(api_provinsi)
    .then((response) => {
      let data = response.data;
      let provinsi = data.find((x) => x.id == id_provinsi);
      console.log("ini provinsi pilihan", provinsi);
      let area = provinsi.area;

      if (!provinsi) {
        console.log("error");
      }
      console.log("ini area diambil dari provinsi", area);
      ambilPolygon(area);
    })
    .catch((error) => {
      console.error("Error fetching the JSON:", error);
    });
}

function ambilPolygon(area) {
  let coordinates = area.coordinates.map((polygon) => {
    return polygon.map((ring) => {
      return ring.map((coord) => [coord[1], coord[0]]);
    });
  });

  let multipolygon = L.polygon(coordinates, {
    color: "blue",
    fillColor: "#3388ff",
    fillOpacity: 0.2,
  }).addTo(layerGroupPolygon);

  layerGroupPolygon.addTo(mapInstance.value);
  mapInstance.value.fitBounds(multipolygon.getBounds());
}

//rumus marker
function addMarkers(data, icon, layerName) {
  data.forEach((item) => {
    // Mengambil koordinat dari objek geometry
    let latLng = [item.geometry.coordinates[1], item.geometry.coordinates[0]];

    // Menambahkan marker ke layerGroup sesuai dengan layerName
    if (layerName === "Gardu_Induk") {
      L.marker(latLng, { icon: icon }).addTo(layerGroupGardu);
    } else if (layerName === "Pembangkit") {
      L.marker(latLng, { icon: icon }).addTo(layerGroupPembangkit);
    }
  });

  // Menambahkan layerGroup ke peta
  if (layerName === "Gardu_Induk") {
    layerGroupGardu.addTo(mapInstance.value);
  } else if (layerName === "Pembangkit") {
    layerGroupPembangkit.addTo(mapInstance.value);
  }
}

function addPolylines(data) {
  data.forEach((item) => {
    if (item.geometry.type === "MultiLineString") {
      let coordinates = item.geometry.coordinates.map((line) =>
        line.map((coord) => [coord[1], coord[0]])
      );

      coordinates.forEach((line) => {
        L.polyline(line, {
          color: "black",
          opacity: 0.5,
          lineCap: "butt",
          lineJoin: "butt", // Style of the line's corners
        }).addTo(layerGroupTransmisi);
      });
    }
  });
  layerGroupTransmisi.addTo(mapInstance.value);
}
</script>

<template>
  <div class="container space-x-10">
    <select id="pilih-provinsi">
      <option value="">Pilih Provinsi</option>
      <option v-for="prov in provinsi" :key="prov.id" :value="prov.id">
        {{ prov.name }}
      </option>
    </select>
    <select id="pilih-tipe">
      <option value="">Pilih Tipe</option>
      <option value="Gardu Induk">Gardu Induk</option>
      <option value="Pembangkit">Pembangkit</option>
      <option value="Transmisi">Transmisi</option>
    </select>
  </div>
</template>

<style lang="scss" scoped></style>
