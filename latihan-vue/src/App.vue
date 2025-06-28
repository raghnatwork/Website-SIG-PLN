<script setup>
import { RouterLink, RouterView } from "vue-router";
import SidebarVue from "./components/backup/Sidebar.vue";
// import HelloWorld from "./components/HelloWorld.vue";
// import SidebarVue from "./components/Sidebar.vue";
</script>

<template>
  <!-- <header>
    <div class="wrapper">
      <HelloWorld msg="You did it!" />

      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
      </nav>
    </div>
  </header> -->

  <div class="container flex h-screen w-screen">
    <!-- <div class="sidebar z-50"><SidebarVue /></div> -->
    <main class="h-screen w-screen">
      <RouterView />
    </main>
  </div>
</template>

<style lang="scss" scoped>
/* Ensure the sidebar and main content are styled appropriately */

//.flex-1 {
//flex: 1; /* Take up remaining space */
//}

// @media (min-width: 1232px) {
//   .container {
//     max-width: 1232px;
//   }
// }
// .overflow-hidden {
//   overflow: hidden; /* Hide any overflow content */
// }
</style>
