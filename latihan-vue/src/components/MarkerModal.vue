<template>
  <div
    class="bg-white p-4 w-[240px] h-[150px] relative text-[13px] rounded-md flex flex-col"
  >
    <button class="absolute right-0 top-0 my-2 mx-4" @click="$emit('close')">x</button>

    <span v-if="marker.nama_tipe === 'Pembangkit'">
      <div class="flex justify-start items-center m-3 space-x-2">
        <span class=""><IconPembangkit class="w-[35px] h-auto" /></span>
        <span class="font-bold">Pembangkit</span>
      </div>
    </span>

    <span v-if="marker.nama_tipe === 'Gardu Induk'">
      <div class="flex justify-start items-center m-3 space-x-2">
        <span class=""><icon-gardu-induk class="w-[35px] h-auto" /></span>
        <span class="font-bold">Gardu Induk</span>
      </div>
    </span>

    <div class="ml-3">
      <h1>{{ marker.nama_aset }}</h1>
      <h1>{{ marker.position }}</h1>
    </div>
  </div>
</template>

<script setup>
//component
import IconPembangkit from "./icons/IconPembangkit.vue";
import IconGarduInduk from "./icons/IconGarduInduk.vue";
import IconLine from "./icons/IconLine.vue";
import IconPolygon from "./icons/IconPolygon.vue";

//library
import { defineEmits, computed } from "vue";

const props = defineProps({
  marker: null,
});

const formattedLatLng = computed(() => {
  if (props.marker && props.marker.position) {
    const { lat, lng } = props.marker.position;
    return `kordinat (${lat}, ${lng})`;
  }
  return "";
});
</script>

<style scoped></style>
