<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ isEdit ? 'Editar Pet' : 'Novo Pet' }}
        </h3>
        
        <form @submit.prevent="handleSubmit">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Cliente *</label>
              <select
                v-model="form.client_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecione um cliente</option>
                <option v-for="client in clients" :key="client.id" :value="client.id">
                  {{ client.name }}
                </option>
              </select>
            </div>

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
              <label class="block text-sm font-medium text-gray-700">Espécie *</label>
              <select
                v-model="form.species"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecione uma espécie</option>
                <option value="Cão">Cão</option>
                <option value="Gato">Gato</option>
                <option value="Pássaro">Pássaro</option>
                <option value="Coelho">Coelho</option>
                <option value="Outros">Outros</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Raça</label>
              <input
                v-model="form.breed"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Sexo *</label>
              <select
                v-model="form.gender"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecione o sexo</option>
                <option value="male">Macho</option>
                <option value="female">Fêmea</option>
              </select>
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
              <label class="block text-sm font-medium text-gray-700">Peso (kg)</label>
              <input
                v-model="form.weight"
                type="number"
                step="0.1"
                min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Cor</label>
              <input
                v-model="form.color"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Microchip</label>
              <input
                v-model="form.microchip"
                type="text"
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
import { ref, onMounted, watch } from 'vue'
import { petService } from '../services/petService'
import { useToastStore } from '../stores/toast'

const props = defineProps({
  pet: Object,
  isEdit: Boolean,
  clients: Array
})

const emit = defineEmits(['close', 'saved'])

const toastStore = useToastStore()
const loading = ref(false)

const form = ref({
  client_id: '',
  name: '',
  species: '',
  breed: '',
  gender: '',
  birth_date: '',
  weight: '',
  color: '',
  microchip: '',
  notes: ''
})

watch(() => props.pet, (pet) => {
    if (pet) {
      form.value = { ...pet }
    }
  }, { immediate: true })

const handleSubmit = async () => {
  loading.value = true
  try {
    if (props.isEdit) {
      await petService.updatePet(props.pet.id, form.value)
      toastStore.success('Pet atualizado com sucesso!')
    } else {
      await petService.createPet(form.value)
      toastStore.success('Pet criado com sucesso!')
    }
    emit('saved')
  } catch (error) {
    toastStore.error('Erro ao salvar pet')
  } finally {
    loading.value = false
  }
}
</script>
