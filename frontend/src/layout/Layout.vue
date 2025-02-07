<template>
  <div class="flex h-screen">
    <!-- Sidebar (Visible on Medium Screens and Above) -->
    <div
      v-bind:class="{
        'fixed md:relative top-0 left-0 h-full w-64 bg-gray-800 text-white shadow-lg': true,
        'md:block hidden': !isSidebarVisible,
        'md:hidden block': isSidebarVisible,
      }"
    >
      <div class="p-4 text-center">
        <h2 class="text-xl font-bold">Admin Panel</h2>
      </div>
      <ul class="mt-4">
        <li>
          <router-link to="/" class="block py-2 px-4 hover:bg-gray-700"
            >Dashboard</router-link
          >
        </li>
        <li>
          <router-link to="/tickets" class="block py-2 px-4 hover:bg-gray-700"
            >Tickets</router-link
          >
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col w-full md:flex-row md:ml-0">
      <div
        class="fixed top-0 left-0 right-0 bg-gray-800 text-white shadow-md z-10"
      >
        <div class="flex justify-between items-center p-4">
          <div class="flex items-center">
            <button
              @click="toggleSidebar"
              class="text-white p-2 md:hidden flex flex-col items-center space-y-1"
            >
              <span class="w-6 h-1 bg-white"></span>
              <span class="w-6 h-1 bg-white"></span>
              <span class="w-6 h-1 bg-white"></span>
            </button>

            <span class="ml-4 text-lg font-bold">Admin Dashboard</span>
          </div>
          <div class="items-center space-x-4 hidden md:flex">
            <button class="text-white cursor-pointer" @click="logout">Logout</button>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="mt-16 p-4 flex-1">
        <router-view></router-view>
      </div>
    </div>

    <!-- Mobile Sidebar -->
    <div
      v-bind:class="{
        'fixed top-0 left-0 h-full w-64 bg-gray-800 text-white shadow-lg transform -translate-x-full transition-transform duration-300 md:hidden': true,
        'translate-x-0': isSidebarVisible,
      }"
    >
      <div class="p-4 text-center">
        <h2 class="text-xl font-bold">Admin Panel</h2>
      </div>
      <ul class="mt-4">
        <li>
          <router-link to="/" class="block py-2 px-4 hover:bg-gray-700"
            >Dashboard</router-link
          >
        </li>
        <li>
          <router-link to="/tickets" class="block py-2 px-4 hover:bg-gray-700"
            >Tickets</router-link
          >
        </li>
        <li>
          <button class="block py-2 px-4 hover:bg-gray-700 cursor-pointer" @click="logout">
            Logout
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { logoutSession } from "../api";

const authStore = useAuthStore();
const router = useRouter();

const isSidebarVisible = ref(false);
const toggleSidebar = () => {
  isSidebarVisible.value = !isSidebarVisible.value;
};

const logout = async () => {
  const response = await logoutSession();
  if (response.status === 200) {
    authStore.logout();
    router.push("/login");
  }
};
</script>
