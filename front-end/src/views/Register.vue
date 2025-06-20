<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sistema Veterinário
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Criar nova conta no sistema
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="space-y-4">
          <!-- Nome -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Nome Completo *
            </label>
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Digite seu nome completo"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email *
            </label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Digite seu email"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
          </div>

          <!-- Senha -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Senha *
            </label>
            <div class="mt-1 relative">
              <input
                id="password"
                v-model="form.password"
                name="password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Digite sua senha"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.415-1.414M14.12 14.12l1.415 1.415M14.12 14.12L15.536 15.536M14.12 14.12l1.414 1.414" />
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
            <p class="mt-1 text-xs text-gray-500">Mínimo de 6 caracteres</p>
          </div>

          <!-- Confirmar Senha -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirmar Senha *
            </label>
            <div class="mt-1 relative">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                name="password_confirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                required
                class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                placeholder="Confirme sua senha"
              />
              <button
                type="button"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="showPasswordConfirmation" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.415-1.414M14.12 14.12l1.415 1.415M14.12 14.12L15.536 15.536M14.12 14.12l1.414 1.414" />
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <p v-if="passwordMismatch" class="mt-1 text-sm text-red-600">As senhas não coincidem</p>
          </div>

          <!-- Função/Cargo -->
          <div>
            <label for="role" class="block text-sm font-medium text-gray-700">
              Função *
            </label>
            <select
              id="role"
              v-model="form.role"
              name="role"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="">Selecione sua função</option>
              <option value="admin">Administrador</option>
              <option value="veterinarian">Veterinário</option>
              <option value="receptionist">Recepcionista</option>
            </select>
            <p v-if="errors.role" class="mt-1 text-sm text-red-600">{{ errors.role[0] }}</p>
          </div>

          <!-- Telefone -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">
              Telefone
            </label>
            <input
              id="phone"
              v-model="form.phone"
              name="phone"
              type="tel"
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="(11) 99999-9999"
            />
            <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone[0] }}</p>
          </div>

          <!-- CRMV (apenas para veterinários) -->
          <div v-if="form.role === 'veterinarian'">
            <label for="crmv" class="block text-sm font-medium text-gray-700">
              CRMV
            </label>
            <input
              id="crmv"
              v-model="form.crmv"
              name="crmv"
              type="text"
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Ex: CRMV-SP 12345"
            />
            <p v-if="errors.crmv" class="mt-1 text-sm text-red-600">{{ errors.crmv[0] }}</p>
            <p class="mt-1 text-xs text-gray-500">Número do registro no Conselho Regional de Medicina Veterinária</p>
          </div>
        </div>

        <!-- Termos e Condições -->
        <div class="flex items-center">
          <input
            id="terms"
            v-model="acceptTerms"
            name="terms"
            type="checkbox"
            required
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          />
          <label for="terms" class="ml-2 block text-sm text-gray-900">
            Eu aceito os 
            <a href="#" class="text-indigo-600 hover:text-indigo-500">termos de uso</a>
            e 
            <a href="#" class="text-indigo-600 hover:text-indigo-500">política de privacidade</a>
          </label>
        </div>

        <!-- Botão de Registro -->
        <div>
          <button
            type="submit"
            :disabled="loading || !isFormValid"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading">Criando conta...</span>
            <span v-else>Criar Conta</span>
          </button>
        </div>

        <!-- Link para Login -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
              Faça login aqui
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToastStore } from '../stores/toast'

const router = useRouter()
const authStore = useAuthStore()
const toastStore = useToastStore()

const loading = ref(false)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const acceptTerms = ref(false)
const errors = ref({})

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
  phone: '',
  crmv: ''
})

const passwordMismatch = computed(() => {
  return form.value.password && form.value.password_confirmation && 
         form.value.password !== form.value.password_confirmation
})

const isFormValid = computed(() => {
  return form.value.name &&
         form.value.email &&
         form.value.password &&
         form.value.password_confirmation &&
         form.value.role &&
         !passwordMismatch.value &&
         acceptTerms.value &&
         form.value.password.length >= 6
})

const handleRegister = async () => {
  if (!isFormValid.value) {
    toastStore.error('Por favor, preencha todos os campos obrigatórios')
    return
  }

  loading.value = true
  errors.value = {}

  try {
    const response = await authStore.register(form.value)
    toastStore.success('Conta criada com sucesso! Você já está logado.')
    router.push('/dashboard')
  } catch (error) {
    console.error('Registration error:', error)
    
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
      const errorMessages = Object.values(error.response.data.errors).flat()
      toastStore.error('Erro de validação: ' + errorMessages.join(', '))
    } else if (error.response?.data?.message) {
      toastStore.error(error.response.data.message)
    } else {
      toastStore.error('Erro ao criar conta. Tente novamente.')
    }
  } finally {
    loading.value = false
  }
}
</script>
