<template>
  <div class="container mx-auto p-4">
    <div class="mt-4 mb-4">
      <router-link
        to="/tickets/create"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none"
        >Create</router-link
      >
    </div>
    <div class="flex justify-between items-center mb-4">
      <!-- Search Box -->
      <div class="flex items-center space-x-2">
        <input
          v-model="search"
          type="text"
          placeholder="Search tickets..."
          class="px-3 py-2 border border-gray-300 rounded-lg"
          @input="fetchTickets"
        />
      </div>

      <!-- Status Filter -->
      <div class="flex items-center space-x-2">
        <label for="statusFilter" class="font-medium text-gray-700"
          >Status:</label
        >
        <select
          id="statusFilter"
          v-model="status"
          @change="fetchTickets"
          class="px-3 h-8 border border-gray-300 rounded-lg"
        >
          <option value="">All</option>
          <option value="open">Open</option>
          <option value="closed">Closed</option>
          <option value="in_progress">In Progress</option>
        </select>
      </div>
    </div>

    <table class="min-w-full shadow responsive-table divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
            Title
          </th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
            Description
          </th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
            Status
          </th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
            Created At
          </th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
            Action
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr class="hover:none" v-for="ticket in ticketsData" :key="ticket.id">
          <td class="ps-6 pe-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ ticket.title }}
          </td>
          <td class="ps-6 pe-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ ticket.description }}
          </td>
          <td class="ps-6 pe-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ ticket.status }}
          </td>
          <td class="ps-6 pe-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ ticket.created_at }}
          </td>
          <td class="ps-6 pe-4 py-4 whitespace-nowrap text-sm text-gray-600">
            <div class="flex flex-row gap-2">
              <router-link
                :to="`/tickets/${ticket.id}/edit`"
                class="px-4 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md transition duration-300"
              >
                Edit
              </router-link>
              <button
                @click="confirmDelete(ticket.id)"
                class="px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-md transition duration-300 cursor-pointer"
              >
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="flex justify-between items-center mt-4 mb-4">
      <!-- Per Page Selection -->
      <div class="flex items-center space-x-2">
        <label for="perPage" class="font-medium text-gray-700">Per page:</label>
        <select
          id="perPage"
          v-model="perPage"
          @change="fetchTickets"
          class="px-3 h-8 ms-0 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-0 focus:ring-0"
        >
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
        </select>
      </div>

      <div class="flex flex-row flex-nowrap gap-2 items-center">
        <div class="text-gray-600">
          Showing {{ (page - 1) * perPage + 1 }} -
          {{ Math.min(page * perPage, totalTickets) }} of
          {{ totalTickets }} tickets
        </div>
        <nav aria-label="pagination">
          <ul class="inline-flex -space-x-px text-sm">
            <li>
              <a
                href="#"
                @click.prevent="prevPage"
                :disabled="page === 1"
                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50"
              >
                Previous
              </a>
            </li>

            <li
              v-for="n in totalPagesDisplayed"
              :key="n"
              :class="{
                'bg-blue-500 text-white': n === page,
                'bg-gray-200 text-gray-700': n !== page && n !== '...',
                'text-gray-500': n === '...',
              }"
            >
              <a
                href="#"
                @click.prevent="goToPage(n)"
                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
              >
                {{ n }}
              </a>
            </li>

            <li>
              <a
                href="#"
                @click.prevent="nextPage"
                :disabled="page === totalPages"
                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50"
              >
                Next
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, watch, onMounted } from "vue";
import { useTicketsStore } from "../stores/tickets";

const ticketsStore = useTicketsStore();

// Search and Status model
const search = computed({
  get: () => ticketsStore.search,
  set: (value) => {
    ticketsStore.search = value;
    fetchTickets(); // Trigger fetch when search is updated
  },
});

const status = computed({
  get: () => ticketsStore.status,
  set: (value) => {
    ticketsStore.status = value;
    fetchTickets(); // Trigger fetch when status is updated
  },
});

// Pagination and Fetch tickets
const ticketsData = computed(() => ticketsStore.ticketsData);
const totalPages = computed(() => ticketsStore.totalPages);
const totalTickets = computed(() => ticketsStore.totalTickets);
const page = computed(() => ticketsStore.page);
const perPage = computed({
  get: () => ticketsStore.perPage,
  set: (value) => {
    ticketsStore.perPage = value;
    ticketsStore.page = 1; // Reset page to 1 when per page changes
    fetchTickets(); // Fetch tickets after page or per page change
  },
});

// Fetch tickets based on search and status
const fetchTickets = () => {
  ticketsStore.fetchTickets({
    search: ticketsStore.search,
    status: ticketsStore.status,
    page: ticketsStore.page,
    perPage: ticketsStore.perPage,
  });
};

// Automatically call fetchTickets on page load
onMounted(() => {
  fetchTickets(); // Trigger fetch when the component is mounted
});

const totalPagesDisplayed = computed(() => {
  const pages = [];
  const currentPage = page.value;
  const total = totalPages.value;

  if (total <= 5) {
    for (let i = 1; i <= total; i++) {
      pages.push(i);
    }
  } else {
    const start = Math.max(1, currentPage - 2);
    const end = Math.min(total, currentPage + 2);
    for (let i = start; i <= end; i++) {
      pages.push(i);
    }

    if (currentPage > 3) pages.unshift("...");
    if (currentPage < total - 2) pages.push("...");
  }

  return pages;
});

const prevPage = () => {
  if (ticketsStore.page > 1) {
    ticketsStore.page--;
    fetchTickets(); // Fetch tickets on page change
  }
};

const nextPage = () => {
  if (ticketsStore.page < ticketsStore.totalPages) {
    ticketsStore.page++;
    fetchTickets(); // Fetch tickets on page change
  }
};

const goToPage = (pageNumber) => {
  if (pageNumber !== "...") {
    ticketsStore.page = pageNumber;
    fetchTickets(); // Fetch tickets on page change
  }
};
const confirmDelete = async (id) => {
  if (confirm("Are you sure you want to delete this?")) {
    const response = await ticketsStore.destroyById(id);
    if (response.status === 200) {
      fetchTickets();
    }
  }
};
</script>
