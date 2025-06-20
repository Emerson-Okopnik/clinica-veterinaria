<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <Navbar v-if="isAuthenticated" />
    <main :class="{ 'ml-64': isAuthenticated }">
      <router-view />
    </main>
    <Toast />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from './stores/auth'
import Navbar from './components/Navbar.vue'
import Toast from './components/Toast.vue'

const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)

const isAuthChecked = ref(false)

onMounted(() => {
  authStore.checkAuth().finally(() => {
    isAuthChecked.value = true
  })
})
</script>

<style>
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

#app {
  font-family: 'Inter', sans-serif;
}
</style>
