<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-1/2 max-w-[300px]">
      <select
        v-model="selected_provinsi"
        v-show="!is_loading"
        class="block w-full h-12 mt-5 rounded-md border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 focus:outline-none overflow-auto"
      >
        <option :value="{}">Pilih Provinsi</option>
        <option
          v-for="list in provinsi"
          :key="list.id"
          :value="list"
          class="p-2"
        >
          {{ list.name }}
        </option>
      </select>
      <p v-if="is_loading" class="mt-4 text-gray-500">Loading...</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

let is_loading = ref(false);
let selected_provinsi = ref({});
let provinsi = ref([
  { id: 1, name: "Provinsi 1" },
  { id: 2, name: "Provinsi 2" },
  { id: 3, name: "Provinsi 3" },
  { id: 4, name: "Provinsi 4" },
  { id: 5, name: "Provinsi 5" },
  { id: 6, name: "Provinsi 6" },
  { id: 7, name: "Provinsi 7" },
  { id: 8, name: "Provinsi 8" },
  { id: 9, name: "Provinsi 9" },
  { id: 10, name: "Provinsi 10" },
  { id: 11, name: "Provinsi 11" },
  { id: 12, name: "Provinsi 12" },
  { id: 13, name: "Provinsi 13" },
  { id: 14, name: "Provinsi 14" },
  { id: 15, name: "Provinsi 15" },
]);
</script>

<style scoped>
/* Styling tambahan jika diperlukan */
</style>
