import api from "./api"

export const petService = {
  getPets(params = {}) {
    return api.get("/pets", { params })
  },

  getPet(id) {
    return api.get(`/pets/${id}`)
  },

  createPet(data) {
    return api.post("/pets", data)
  },

  updatePet(id, data) {
    return api.put(`/pets/${id}`, data)
  },

  deletePet(id) {
    return api.delete(`/pets/${id}`)
  },
}
