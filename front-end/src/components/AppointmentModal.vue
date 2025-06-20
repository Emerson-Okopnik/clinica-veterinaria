<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ isEdit ? 'Editar Consulta' : 'Nova Consulta' }}
        </h3>
        
        <form @submit.prevent="handleSubmit">
          <div class="space-y-4 max-h-96 overflow-y-auto">
            <div>
              <label class="block text-sm font-medium text-gray-700">Pet *</label>
              <select
                v-model="form.pet_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecione um pet</option>
                <option v-for="pet in pets" :key="pet.id" :value="pet.id">
                  {{ pet.name }} - {{ pet.client?.name || 'Cliente não encontrado' }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Veterinário *</label>
              <select
                v-model="form.veterinarian_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">Selecione um veterinário</option>
                <option v-for="vet in veterinarians" :key="vet.id" :value="vet.id">
                  {{ vet.name }} {{ vet.crmv ? `- CRMV: ${vet.crmv}` : '' }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Data e Hora *</label>
              <input
                v-model="form.scheduled_at"
                type="datetime-local"
                required
                :min="minDateTime"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Motivo da Consulta *</label>
              <textarea
                v-model="form.reason"
                required
                rows="3"
                maxlength="500"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Descreva o motivo da consulta..."
              ></textarea>
              <p class="mt-1 text-xs text-gray-500">{{ form.reason.length }}/500 caracteres</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Duração (minutos)</label>
              <select
                v-model="form.duration"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="30">30 minutos</option>
                <option value="60">1 hora</option>
                <option value="90">1h 30min</option>
                <option value="120">2 horas</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Preço (R$)</label>
              <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                placeholder="0,00"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Observações</label>
              <textarea
                v-model="form.notes"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Observações adicionais..."
              ></textarea>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
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
import { ref, onMounted, watch, computed } from 'vue'
import { appointmentService } from '../services/appointmentService'
import { useToastStore } from '../stores/toast'

const props = defineProps({
  appointment: Object,
  isEdit: Boolean,
  pets: Array,
  veterinarians: Array
})

const emit = defineEmits(['close', 'saved'])

const toastStore = useToastStore()
const loading = ref(false)

const now = new Date()
now.setMinutes(now.getMinutes() - now.getTimezoneOffset())
const minDateTime = computed(() => {
  return now.toISOString().slice(0, 16)
})

const form = ref({
  pet_id: '',
  veterinarian_id: '',
  scheduled_at: '',
  reason: '',
  duration: 60,
  price: '',
  notes: ''
})



watch(() => props.appointment, (appointment) => {
  if (appointment) {
    form.value = {
      ...appointment,
      scheduled_at: appointment.scheduled_at ? appointment.scheduled_at.slice(0, 16) : '',
      price: appointment.price || '',
      duration: appointment.duration || 60
    }
  } else {
    // Reset form for new appointment
    form.value = {
      pet_id: '',
      veterinarian_id: '',
      scheduled_at: '',
      reason: '',
      duration: 60,
      price: '',
      notes: ''
    }
  }
}, { immediate: true })

const handleSubmit = async () => {
  loading.value = true
  try {
    const submitData = { ...form.value }
    
    // Remove empty price
    if (!submitData.price) {
      delete submitData.price
    }
    
    if (props.isEdit) {
      await appointmentService.updateAppointment(props.appointment.id, submitData)
      toastStore.success('Consulta atualizada com sucesso!')
    } else {
      await appointmentService.createAppointment(submitData)
      toastStore.success('Consulta agendada com sucesso!')
    }
    emit('saved')
  } catch (error) {
    console.error('Error saving appointment:', error)
    if (error.response?.data?.error === 'Time slot already booked') {
      toastStore.error('Este horário já está ocupado para este veterinário!')
    } else if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      toastStore.error('Erro de validação: ' + errors.join(', '))
    } else {
      toastStore.error('Erro ao salvar consulta: ' + (error.response?.data?.message || error.message))
    }
  } finally {
    loading.value = false
  }
}
</script>
