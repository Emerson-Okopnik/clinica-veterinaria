import { defineStore } from "pinia"

export const useToastStore = defineStore("toast", {
  state: () => ({
    toasts: [],
  }),

  actions: {
    addToast(toast) {
      const id = Date.now()
      this.toasts.push({ ...toast, id })

      setTimeout(() => {
        this.removeToast(id)
      }, toast.duration || 5000)
    },

    removeToast(id) {
      const index = this.toasts.findIndex((toast) => toast.id === id)
      if (index > -1) {
        this.toasts.splice(index, 1)
      }
    },

    success(message) {
      this.addToast({
        type: "success",
        message,
        duration: 3000,
      })
    },

    error(message) {
      this.addToast({
        type: "error",
        message,
        duration: 5000,
      })
    },

    info(message) {
      this.addToast({
        type: "info",
        message,
        duration: 4000,
      })
    },
  },
})
