import { defineStore } from "pinia"
import { authService } from "../services/authService"

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("token"),
    isAuthenticated: false,
  }),

  getters: {
    isAdmin: (state) => state.user?.role === "admin",
    isVeterinarian: (state) => state.user?.role === "veterinarian",
    isReceptionist: (state) => state.user?.role === "receptionist",
  },

  actions: {
    async login(credentials) {
      try {
        const response = await authService.login(credentials)
        const { user, token } = response.data

        this.user = user
        this.token = token
        this.isAuthenticated = true

        localStorage.setItem("token", token)
        localStorage.setItem("user", JSON.stringify(user))

        return response
      } catch (error) {
        this.clearAuth()
        throw error
      }
    },

    async register(userData) {
      try {
        const response = await authService.register(userData)
        const { user, token } = response.data

        this.user = user
        this.token = token
        this.isAuthenticated = true

        localStorage.setItem("token", token)
        localStorage.setItem("user", JSON.stringify(user))

        return response
      } catch (error) {
        this.clearAuth()
        throw error
      }
    },

    async logout() {
      try {
        await authService.logout()
      } catch (error) {
        console.error("Logout error:", error)
      } finally {
        this.clearAuth()
      }
    },

    async checkAuth() {
      const token = localStorage.getItem("token")
      const user = localStorage.getItem("user")

      if (token && user) {
        try {
          this.token = token
          this.user = JSON.parse(user)
          this.isAuthenticated = true

          // Verify token is still valid
          await authService.me()
        } catch (error) {
          this.clearAuth()
        }
      }
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
      localStorage.removeItem("token")
      localStorage.removeItem("user")
    },
  },
})
