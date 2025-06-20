import api from "./api"

export const appointmentService = {
  getAppointments(params = {}) {
    return api.get("/appointments", { params })
  },

  getAppointment(id) {
    return api.get(`/appointments/${id}`)
  },

  createAppointment(data) {
    return api.post("/appointments", data)
  },

  updateAppointment(id, data) {
    return api.put(`/appointments/${id}`, data)
  },

  deleteAppointment(id) {
    return api.delete(`/appointments/${id}`)
  },
}
