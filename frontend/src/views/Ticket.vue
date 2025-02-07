<template>
  <div>
    <h2 class="mb-2">{{ isEditMode ? "Edit Ticket" : "Create Ticket" }}</h2>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">
          Title
        </label>
        <input
          id="title"
          v-model="ticket.title"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg"
        />
      </div>

      <div class="mb-4">
        <label
          for="description"
          class="block text-sm font-medium text-gray-700"
        >
          Description
        </label>
        <textarea
          id="description"
          v-model="ticket.description"
          rows="4"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg"
        ></textarea>
      </div>

      <!-- Conditionally show the status field only if in edit mode -->
      <div v-if="isEditMode" class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700">
          Status
        </label>
        <select
          id="status"
          v-model="ticket.status"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg"
        >
          <option value="open">Open</option>
          <option value="closed">Closed</option>
          <option value="in_progress">In Progress</option>
        </select>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="px-6 py-2 bg-blue-500 text-white rounded-lg cursor-pointer"
        >
          {{ isEditMode ? "Update Ticket" : "Create Ticket" }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router"; // Import useRouter
import { useTicketsStore } from "@/stores/tickets";

const route = useRoute();
const router = useRouter(); // Initialize the router
const ticketsStore = useTicketsStore();

const isEditMode = route.params.id ? true : false;

const ticket = ref({
  title: "",
  description: "",
  status: "open",
});

onMounted(() => {
  if (isEditMode) {
    ticketsStore.getTicket(route.params.id).then((data) => {

      ticket.value = { ...data.data };
    });
  }
});

const submitForm = async () => {
  let response;
  if (isEditMode) {
    response = await ticketsStore.updateExistingTicket(
      route.params.id,
      ticket.value
    );
  } else {
    response = await ticketsStore.createNewTicket(ticket.value);
  }
  if (response.status === 200 || response.status === 201) {
    router.push("/tickets");
  }
};
</script>
