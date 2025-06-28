<template>
  <div class="bg-white p-4 w-[auto] relative text-[13px] rounded-md">
    <button class="absolute right-0 top-0 my-2 mx-4"></button>
    <div class="flex justify-start items-center m-3 space-x-2">
      <span class=""><icon-gardu-induk class="w-[35px] h-auto" /></span>
      <span>Gardu Induk</span>
    </div>
    <div class="flex justify-start items-center m-3 space-x-2">
      <span class=""><IconPembangkit class="w-[35px] h-auto" /></span>
      <span>Pembangkit</span>
    </div>
    <div class="flex justify-start items-center m-3 space-x-2">
      <span class=""><IconLine class="w-[35px] h-auto" /></span>
      <span>Transmisi</span>
    </div>
    <div class="flex justify-start items-center m-3 space-x-2">
      <span class=""><IconPolygon class="w-[35px] h-auto" /></span>
      <span>Batas Provinsi</span>
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
import { defineEmits } from "vue";
</script>

<style scoped></style>
