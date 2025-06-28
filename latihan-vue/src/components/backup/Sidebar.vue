<script setup>
// import MenuIcon from "./icons/MenuIcon.vue";
// import HomeIcon from "./icons/HomeIcon.vue";
import ChevronRight from "../icons/ChevronRight.vue";
import ChevronLeft from "../icons/ChevronLeft.vue";
import Logo_PLN from "../icons/Logo_PLN.vue";
// import ChevronUp from "./icons/ChevronUp.vue";
import MarkQuestion from "../icons/MarkQuestion.vue";
// import DialogueTest from "./DialogueTest.vue";
import LegendaModal from "../LegendaModal.vue";

import { ref, computed } from "vue";

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
</script>

<template>
  <header class="fixed bg-white flex justify-end mb-[100px] w-full p-4">
    <Logo_PLN class="h-[40px] w-[auto] mr-5 border-black" />
  </header>
  <div
    class="absolute z-50 left-[20px] top-28 w-[100px] h-[75vh] rounded-md transition-all duration-500 ease-in-out flex flex-col justify-center items-start"
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
      class="mt-[150px] p-2 border-collapse relative bg-red-400 flex flex-col justify-center items-center"
    >
      <h1 class="text-2xl underline">Customize</h1>
      <select class="m-5 absolute left-0">
        <option value="">test</option>
      </select>
      <div
        id="checkbox group"
        class="flex space-x-3 justify-center items-center mt-[130px]"
      >
        <div class="first-row">
          <div class="flex items-center mb-4">
            <input
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
    <!-- <nav class="mt-[120px] flex flex-col space-y-4 items-center text-md">
      <a
        href=""
        class="hover:bg-[#2e302d] px-[65px] py-1 rounded-lg flex items-center space-x-2"
      >
        <span class=""><HomeIcon /> </span>
        <span>Home</span>
      </a>

      <a
        href=""
        class="hover:bg-[#2e302d] px-[65px] py-1 rounded-lg flex items-center space-x-2"
      >
        <span class=""><HomeIcon /> </span>
        <span>Home</span>
      </a>

      <a
        href=""
        class="hover:bg-[#2e302d] px-[65px] py-1 rounded-lg flex items-center space-x-2"
      >
        <span class=""><HomeIcon /> </span>
        <span>Home</span>
      </a>
    </nav> -->
  </div>
</template>

<style lang="scss" scoped></style>
