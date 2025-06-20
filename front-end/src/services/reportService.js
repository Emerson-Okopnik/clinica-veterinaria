import api from "./api"

export const reportService = {
  getDashboardStats() {
    return api.get("/reports/dashboard-stats")
  },

  getAppointmentsByPeriod(dateRange) {
    return api.get("/reports/appointments-by-period", {
      params: {
        start_date: dateRange.start,
        end_date: dateRange.end,
      },
    })
  },

  getAppointmentsByVeterinarian(dateRange) {
    return api.get("/reports/appointments-by-veterinarian", {
      params: {
        start_date: dateRange.start,
        end_date: dateRange.end,
      },
    })
  },

  getPetsByClient() {
    return api.get("/reports/pets-by-client")
  },
}
