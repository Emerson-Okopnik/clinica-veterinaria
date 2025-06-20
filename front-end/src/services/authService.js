import api from "./api"

export const authService = {
  login(credentials) {
    return api.post("/login", credentials)
  },

  register(userData) {
    return api.post("/register", userData)
  },

  logout() {
    return api.post("/logout")
  },

  me() {
    return api.get("/me")
  },

  refresh() {
    return api.post("/refresh")
  },
}
