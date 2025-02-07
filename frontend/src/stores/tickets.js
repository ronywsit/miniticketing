// src/stores/tickets.js
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { tickets, create, show, update, destroy } from "../api";

export const useTicketsStore = defineStore("tickets", () => {
  const ticketsData = ref([]);
  const perPage = ref(10);
  const page = ref(1);
  const totalTickets = ref(0);
  const search = ref("");
  const status = ref("");

  const totalPages = computed(() =>
    Math.ceil(totalTickets.value / perPage.value)
  );

  const fetchTickets = async () => {
    const response = await tickets({
      page: page.value,
      per_page: perPage.value,
      search: search.value,
      status: status.value,
    });
    ticketsData.value = response.data.data;
    totalTickets.value = response.data.total;
  };

  const createNewTicket = async (ticketData) => {
    try {
      const response = await create(ticketData);
      return response;
    } catch (error) {
      return error;
    }
  };

  const getTicket = async (ticketId) => {
    try {
      const response = await show(ticketId);
      return response;
    } catch (error) {
      return error;
    }
  };

  const updateExistingTicket = async (ticketId, ticketData) => {
    try {
      const response = await update(ticketId, ticketData);
      return response;
    } catch (error) {
      return error;
    }
  };

  const destroyById = async (ticketId) => {
    try {
      const response = await destroy(ticketId);
      return response;
    } catch (error) {
      return error;
    }
  };

  return {
    ticketsData,
    perPage,
    page,
    search,
    status,
    totalTickets,
    totalPages,
    fetchTickets,
    createNewTicket,
    getTicket,
    updateExistingTicket,
    destroyById,
  };
});
