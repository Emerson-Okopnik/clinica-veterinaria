<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ isEdit ? 'Editar Cliente' : 'Novo Cliente' }}
        </h3>
        
        <form @submit.prevent="handleSubmit">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nome *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="form.email"
                type="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Telefone *</label>
              <input
                v-model="form.phone"
                type="tel"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">CPF</label>
              <input
                v-model="form.cpf"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Endereço</label>
              <textarea
                v-model="form.address"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
              <input
                v-model="form.birth_date"
                type="date"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Observações</label>
              <textarea
                v-model="form.notes"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              ></textarea>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ loading ? 'Salvando...' : 'Salvar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { clientService } from '../services/clientService'
import { useToastStore } from '../stores/toast'
import { onMounted, watch } from 'vue';

const props = defineProps({
  client: Object,
  isEdit: Boolean
})

const emit = defineEmits(['close', 'saved'])

const toastStore = useToastStore()
const loading = ref(false)

const form = ref({
  name: '',
  email: '',
  phone: '',
  cpf: '',
  address: '',
  birth_date: '',
  notes: ''
})

onMounted(() => {
  watch(() => props.client, (client) => {
    if (client) {
      form.value = { ...client }
    }
  }, { immediate: true })
})

const handleSubmit = async () => {
  loading.value = true
  try {
    if (props.isEdit) {
      await clientService.updateClient(props.client.id, form.value)
      toastStore.success('Cliente atualizado com sucesso!')
    } else {
      await clientService.createClient(form.value)
      toastStore.success('Cliente criado com sucesso!')
    }
    emit('saved')
  } catch (error) {
    toastStore.error('Erro ao salvar cliente')
  } finally {
    loading.value = false
  }
}
</script>
