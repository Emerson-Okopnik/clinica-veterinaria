<template>
  <nav class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50">
    <div class="flex flex-col h-full">
      <!-- Logo -->
      <div class="flex items-center justify-center h-16 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">VetSystem</h1>
      </div>

      <!-- Navigation Menu -->
      <div class="flex-1 overflow-y-auto">
        <nav class="px-4 py-4 space-y-2">
          <router-link
            to="/dashboard"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100"
            :class="{ 'bg-gray-100 text-gray-900': $route.path === '/dashboard' }"
          >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10"></path>
            </svg>
            Dashboard
          </router-link>

          <router-link
            to="/clients"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100"
            :class="{ 'bg-gray-100 text-gray-900': $route.path.startsWith('/clients') }"
          >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Clientes
          </router-link>

          <router-link
            to="/pets"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100"
            :class="{ 'bg-gray-100 text-gray-900': $route.path.startsWith('/pets') }"
          >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Pets
          </router-link>

          <router-link
            to="/appointments"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100"
            :class="{ 'bg-gray-100 text-gray-900': $route.path.startsWith('/appointments') }"
          >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Consultas
          </router-link>

          <router-link
            to="/reports"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-md hover:bg-gray-100"
            :class="{ 'bg-gray-100 text-gray-900': $route.path.startsWith('/reports') }"
          >
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Relatórios
          </router-link>
        </nav>
      </div>

      <!-- User Menu -->
      <div class="border-t border-gray-200 p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
              <span class="text-sm font-medium text-gray-700">
                {{ user?.name?.charAt(0) }}
              </span>
            </div>
          </div>
          <div class="ml-3 flex-1">
            <p class="text-sm font-medium text-gray-900">{{ user?.name }}</p>
            <p class="text-xs text-gray-500">{{ user?.role }}</p>
          </div>
          <button
            @click="handleLogout"
            class="ml-3 text-gray-400 hover:text-gray-600"
            title="Sair"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToastStore } from '../stores/toast'

const router = useRouter()
const authStore = useAuthStore()
const toastStore = useToastStore()

const user = computed(() => authStore.user)

const handleLogout = async () => {
  try {
    await authStore.logout()
    toastStore.success('Logout realizado com sucesso!')
    router.push('/login')
  } catch (error) {
    console.error('Logout error:', error)
  }
}
</script>
