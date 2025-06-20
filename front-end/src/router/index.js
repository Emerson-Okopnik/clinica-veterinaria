import { createRouter, createWebHistory } from "vue-router"
import { useAuthStore } from "../stores/auth"

const routes = [
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
    meta: { requiresGuest: true },
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/Register.vue"),
    meta: { requiresGuest: true },
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: () => import("../views/Dashboard.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/clients",
    name: "Clients",
    component: () => import("../views/Clients.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/clients/:id",
    name: "ClientDetail",
    component: () => import("../views/ClientDetail.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/pets",
    name: "Pets",
    component: () => import("../views/Pets.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/appointments",
    name: "Appointments",
    component: () => import("../views/Appointments.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/reports",
    name: "Reports",
    component: () => import("../views/Reports.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/",
    redirect: "/dashboard",
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next("/login")
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next("/dashboard")
  } else {
    next()
  }
})

export default router
