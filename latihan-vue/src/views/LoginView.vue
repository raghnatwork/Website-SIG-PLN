<template>
  <!-- login -->
  <div class="bg-[#273e85] flex justify-center items-center h-screen w-screen">
    <div class="p-8 w-[600px]">
      <a
        href="#"
        class="postion absolute top-0 mt-[70px] text-[20px] font-bold text-white bg-[#273e85] rounded-[10px] h-[90px] w-[370px] flex items-center justify-center space-x-2"
      >
        <div>Aplikasi Georensis</div>
        <div class="">
          <PLN width="90px" />
        </div>
      </a>
      <h1 class="text-2xl font-semibold mb-4 text-white">Login</h1>
      <form @submit.prevent="login">
        <!-- Username Input -->
        <div class="mb-4">
          <label for="username" class="block text-white">Username</label>
          <input
            type="text"
            id="username"
            v-model="username"
            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
            autocomplete="off"
          />
        </div>
        <!-- Password Input -->
        <div class="mb-4">
          <label for="password" class="block text-white">Password</label>
          <input
            type="password"
            id="password"
            v-model="password"
            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
            autocomplete="off"
          />
        </div>

        <!-- Login Button -->
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full"
        >
          Login
        </button>
      </form>
    </div>

    <!-- Background Image -->
    <div class="w-full h-screen hidden lg:block">
      <img
        src="../components/images/bgimage2.jpg"
        alt="Placeholder Image"
        class="object-cover w-full h-full"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; // Import useRouter
import PLN from "@/components/icons/Logo_PLN.vue";

const username = ref("");
const password = ref("");
const apiUrl = "http://localhost:8001/api/login"; // URL API login
const router = useRouter(); // Create an instance of the router

const login = async () => {
  try {
    const response = await axios.post(apiUrl, {
      username: username.value,
      password: password.value,
    });

    // Jika login berhasil
    if (response.data.token) {
      // Simpan token ke localStorage atau vuex
      localStorage.setItem("auth_token", response.data.token);
      alert("Login Berhasil");

      // Arahkan ke halaman map
      router.push("/map");
    } else {
      alert(response.data.message || "Login failed");
    }
  } catch (error) {
    console.error("Error during login:", error);
    alert("Terdapat Masalah Pada Login");
  }
};
</script>
