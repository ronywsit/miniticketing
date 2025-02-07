<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold text-center text-gray-900">Login</h2>
      <form @submit.prevent="handleLogin">
        <!-- Email Field -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            type="email"
            required
            @blur="validateEmail"
            class="w-full p-3 mt-1 border rounded-md focus:ring focus:ring-indigo-300"
            :class="{ 'border-red-500': emailError }"
          />
          <p v-if="emailError" class="text-red-500 text-sm mt-1">
            {{ emailError }}
          </p>
        </div>

        <!-- Password Field -->
        <div>
          <label class="block mt-4 text-sm font-medium text-gray-700"
            >Password</label
          >
          <input
            v-model="password"
            type="password"
            required
            @blur="validatePassword"
            class="w-full p-3 mt-1 border rounded-md focus:ring focus:ring-indigo-300"
            :class="{ 'border-red-500': passwordError }"
          />
          <p v-if="passwordError" class="text-red-500 text-sm mt-1">
            {{ passwordError }}
          </p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="!isFormValid"
          class="w-full mt-6 p-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { login } from "../api";

const email = ref("");
const password = ref("");

const emailError = ref("");
const passwordError = ref("");

const router = useRouter();
const authStore = useAuthStore();

const validateEmail = () => {
  if (!email.value) {
    emailError.value = "Email is required.";
  } else if (
    !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email.value)
  ) {
    emailError.value = "Invalid email format.";
  } else {
    emailError.value = "";
  }
};

const validatePassword = () => {
  if (!password.value) {
    passwordError.value = "Password is required.";
  } else if (password.value.length < 6) {
    passwordError.value = "Password must be at least 6 characters long.";
  } else {
    passwordError.value = "";
  }
};

const isFormValid = computed(() => {
  return (
    !emailError.value && !passwordError.value && email.value && password.value
  );
});

const handleLogin = async () => {
  validateEmail();
  validatePassword();

  if (!isFormValid.value) return;
  const response = await login({
    email: email.value,
    password: password.value,
  });
  if (response.status === 200) {
    authStore.login(response.data);
    router.push("/");
  }
};
</script>
