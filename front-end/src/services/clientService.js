import api from "./api"

export const clientService = {
  getClients(params = {}) {
    return api.get("/clients", { params })
  },

  getClient(id) {
    return api.get(`/clients/${id}`)
  },

  createClient(data) {
    return api.post("/clients", data)
  },

  updateClient(id, data) {
    return api.put(`/clients/${id}`, data)
  },

  deleteClient(id) {
    return api.delete(`/clients/${id}`)
  },
}
