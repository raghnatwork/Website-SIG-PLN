import { createRouter, createWebHistory } from "vue-router";
import login from "../views/LoginView.vue";
import Map1View from "@/views/Map1View.vue";
import Map2View from "../views/Map2View.vue";
// import TestView from "../views/backup/TestView.vue";
// import TestMapView from "@/views/backup/TestMapView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "login",
      component: login,
    },
    {
      path: "/about",
      name: "about",
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import("../views/backup/AboutView.vue"),
    },
    {
      path: "/map",
      name: "map",
      component: Map1View,
    },
    // {
    //   path: "/test",
    //   name: "test",
    //   component: TestView,
    // },
    {
      path: "/map2",
      name: "map2",
      component: Map2View,
    },
    // {
    //   path: "/maptest",
    //   name: "maptest",
    //   component: TestMapView,
    // },
  ],
});

// Menambahkan `beforeEach` untuk memeriksa apakah pengguna sudah login
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem("auth_token"); // Pastikan nama token yang digunakan sesuai
  if (to.name !== "login" && !isAuthenticated) {
    next({ name: "login" }); // Arahkan ke halaman login jika belum login
  } else {
    next(); // Lanjutkan ke halaman yang diminta
  }
});

export default router;
