import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import Login from "@/views/Login.vue";
import Home from "@/views/Home.vue";
import Layout from "@/layout/Layout.vue";
import Tickets from "@/views/Tickets.vue";
import Ticket from "@/views/Ticket.vue";

const routes = [
  { path: "/login", component: Login, meta: { requiresGuest: true } },
  {
    path: "/",
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        component: Home,
      },
    ],
  },
  {
    path: "/tickets",
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        component: Tickets,
      },
    ],
  },
  {
    path: "/tickets/create",
    name: "create-ticket",
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        component: Ticket,
        props: { isEditMode: false },
      },
    ],
  },
  {
    path: "/tickets/:id/edit",
    name: "edit-ticket",
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        component: Ticket,
        props: { isEditMode: true },
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next("/login");
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next("/");
  } else {
    next();
  }
});

export default router;
