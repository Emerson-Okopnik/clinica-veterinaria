<template>
  <div class="p-6">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Consultas</h1>
        <p class="mt-2 text-sm text-gray-700">
          Gerencie todas as consultas agendadas.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button
          @click="showCreateModal = true"
          type="button"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
        >
          Nova Consulta
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Data</label>
        <input
          v-model="selectedDate"
          type="date"
          @change="loadAppointments()"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Veterinário</label>
        <select
          v-model="selectedVeterinarian"
          @change="loadAppointments()"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">Todos os veterinários</option>
          <option v-for="vet in veterinarians" :key="vet.id" :value="vet.id">
            {{ vet.name }}
          </option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select
          v-model="selectedStatus"
          @change="loadAppointments()"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">Todos os status</option>
          <option value="scheduled">Agendada</option>
          <option value="in_progress">Em Andamento</option>
          <option value="completed">Concluída</option>
          <option value="cancelled">Cancelada</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Pet/Cliente</label>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar por pet ou cliente..."
          @input="debouncedSearch"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        />
      </div>
      <div class="flex items-end">
        <button
          @click="clearFilters"
          class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
        >
          Limpar Filtros
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="mt-8 text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      <p class="mt-2 text-sm text-gray-500">Carregando consultas...</p>
    </div>

    <!-- Appointments Table -->
    <div v-else-if="appointments.length > 0" class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data/Hora
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Pet/Cliente
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Veterinário
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Motivo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Preço
                  </th>
                  <th class="relative px-6 py-3">
                    <span class="sr-only">Ações</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="appointment in appointments" :key="appointment.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDateTime(appointment.scheduled_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ appointment.pet?.name || 'Pet não encontrado' }}</div>
                    <div class="text-sm text-gray-500">{{ appointment.client?.name || appointment.pet?.client?.name || 'Cliente não encontrado' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ appointment.veterinarian?.name || 'Veterinário não encontrado' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                    <div class="truncate" :title="appointment.reason">
                      {{ appointment.reason }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(appointment.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ getStatusText(appointment.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ appointment.price ? `R$ ${formatPrice(appointment.price)}` : '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <router-link
                        :to="`/appointments/${appointment.id}`"
                        class="text-indigo-600 hover:text-indigo-900"
                        title="Ver detalhes"
                      >
                        Ver
                      </router-link>
                      <button
                        @click="editAppointment(appointment)"
                        class="text-indigo-600 hover:text-indigo-900"
                        title="Editar consulta"
                      >
                        Editar
                      </button>
                      <button
                        v-if="appointment.status === 'scheduled'"
                        @click="updateStatus(appointment, 'in_progress')"
                        class="text-green-600 hover:text-green-900"
                        title="Iniciar consulta"
                      >
                        Iniciar
                      </button>
                      <button
                        v-if="appointment.status === 'in_progress'"
                        @click="updateStatus(appointment, 'completed')"
                        class="text-blue-600 hover:text-blue-900"
                        title="Concluir consulta"
                      >
                        Concluir
                      </button>
                      <button
                        v-if="['scheduled', 'in_progress'].includes(appointment.status)"
                        @click="cancelAppointment(appointment)"
                        class="text-red-600 hover:text-red-900"
                        title="Cancelar consulta"
                      >
                        Cancelar
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma consulta encontrada</h3>
      <p class="mt-1 text-sm text-gray-500">
        {{ hasFilters ? 'Tente ajustar os filtros de busca.' : 'Comece agendando uma nova consulta.' }}
      </p>
      <div class="mt-6">
        <button
          v-if="hasFilters"
          @click="clearFilters"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Limpar Filtros
        </button>
        <button
          v-else
          @click="showCreateModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Agendar Primeira Consulta
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.total > pagination.per_page" class="mt-8">
      <nav class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="loadAppointments(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Anterior
          </button>
          <button
            @click="loadAppointments(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Próximo
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Mostrando
              <span class="font-medium">{{ pagination.from || 0 }}</span>
              até
              <span class="font-medium">{{ pagination.to || 0 }}</span>
              de
              <span class="font-medium">{{ pagination.total || 0 }}</span>
              resultados
            </p>
          </div>
        </div>
      </nav>
    </div>

    <!-- Create/Edit Modal -->
    <AppointmentModal
      v-if="showCreateModal || showEditModal"
      :appointment="selectedAppointment"
      :is-edit="showEditModal"
      :pets="pets"
      :veterinarians="veterinarians"
      @close="closeModal"
      @saved="handleAppointmentSaved"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { appointmentService } from '../services/appointmentService'
import { petService } from '../services/petService'
import { useToastStore } from '../stores/toast'
import AppointmentModal from '../components/AppointmentModal.vue'
import { useDebounceFn } from '@vueuse/core'
import api from '../services/api'

const toastStore = useToastStore()

const appointments = ref([])
const pets = ref([])
const veterinarians = ref([])
const pagination = ref({})
const loading = ref(false)
const showDebug = ref(process.env.NODE_ENV === 'development')

const selectedDate = ref('')
const selectedVeterinarian = ref('')
const selectedStatus = ref('')
const searchQuery = ref('')

const showCreateModal = ref(false)
const showEditModal = ref(false)
const selectedAppointment = ref(null)

const hasFilters = computed(() => {
  return selectedDate.value || selectedVeterinarian.value || selectedStatus.value || searchQuery.value.trim()
})

// Define showCreateModal and showEditModal outside of any conditional block
const openCreateModal = () => {
  showCreateModal.value = true;
}

const loadAppointments = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: 15
    }
    
    // Só adiciona os filtros se tiverem valor
    if (selectedDate.value) {
      params.date = selectedDate.value
    }
    
    if (selectedVeterinarian.value) {
      params.veterinarian_id = selectedVeterinarian.value
    }
    
    if (selectedStatus.value) {
      params.status = selectedStatus.value
    }
    
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim()
    }
    
    console.log('Loading appointments with params:', params)
    
    const response = await appointmentService.getAppointments(params)
    console.log('Appointments response:', response.data)
    
    appointments.value = response.data.data || []
    pagination.value = response.data.meta || response.data
    
    console.log('Appointments loaded:', appointments.value.length)
  } catch (error) {
    console.error('Error loading appointments:', error)
    toastStore.error('Erro ao carregar consultas: ' + (error.response?.data?.message || error.message))
  } finally {
    loading.value = false
  }
}

const loadPets = async () => {
  try {
    const response = await petService.getPets({ per_page: 1000 })
    pets.value = response.data.data || []
    console.log('Pets loaded for appointments:', pets.value.length)
  } catch (error) {
    console.error('Error loading pets:', error)
    toastStore.error('Erro ao carregar pets')
  }
}

const loadVeterinarians = async () => {
  try {
    const response = await api.get('/veterinarians')
    veterinarians.value = response.data || []
    console.log('Veterinarians loaded:', veterinarians.value.length)
  } catch (error) {
    console.error('Error loading veterinarians:', error)
    toastStore.error('Erro ao carregar veterinários')
  }
}

const debouncedSearch = useDebounceFn(() => {
  loadAppointments()
}, 500)

const formatDateTime = (dateTime) => {
  if (!dateTime) return 'Data não informada'
  try {
    return new Date(dateTime).toLocaleString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (error) {
    return 'Data inválida'
  }
}

const formatPrice = (price) => {
  if (!price) return '0,00'
  return parseFloat(price).toFixed(2).replace('.', ',')
}

const getStatusClass = (status) => {
  const classes = {
    scheduled: 'bg-yellow-100 text-yellow-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    scheduled: 'Agendada',
    in_progress: 'Em Andamento',
    completed: 'Concluída',
    cancelled: 'Cancelada'
  }
  return texts[status] || status
}

const editAppointment = (appointment) => {
  selectedAppointment.value = appointment
  showEditModal.value = true
}

const updateStatus = async (appointment, newStatus) => {
  try {
    await appointmentService.updateAppointment(appointment.id, { status: newStatus })
    toastStore.success(`Consulta ${getStatusText(newStatus).toLowerCase()} com sucesso!`)
    loadAppointments()
  } catch (error) {
    toastStore.error('Erro ao atualizar status da consulta')
  }
}

const cancelAppointment = async (appointment) => {
  if (confirm(`Tem certeza que deseja cancelar a consulta de ${appointment.pet?.name || 'este pet'}?`)) {
    try {
      await appointmentService.updateAppointment(appointment.id, { status: 'cancelled' })
      toastStore.success('Consulta cancelada com sucesso!')
      loadAppointments()
    } catch (error) {
      toastStore.error('Erro ao cancelar consulta')
    }
  }
}

const clearFilters = () => {
  selectedDate.value = ''
  selectedVeterinarian.value = ''
  selectedStatus.value = ''
  searchQuery.value = ''
  loadAppointments()
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  selectedAppointment.value = null
}

const handleAppointmentSaved = () => {
  closeModal()
  loadAppointments()
}

onMounted(async () => {
  console.log('Appointments view mounted, loading data...')
  try {
    await Promise.all([
      loadAppointments(),
      loadPets(),
      loadVeterinarians()
    ])
  } catch (error) {
    console.error('Error in onMounted:', error)
  }
})
</script>
