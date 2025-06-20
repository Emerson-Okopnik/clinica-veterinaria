<template>
  <div class="p-6">
    <div class="mb-6">
      <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
          <li>
            <router-link to="/clients" class="text-gray-400 hover:text-gray-500">
              Clientes
            </router-link>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
              <span class="ml-4 text-sm font-medium text-gray-500">{{ client?.name }}</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>

    <div v-if="client" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <!-- Client Info -->
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Informações do Cliente
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Detalhes pessoais e informações de contato.
        </p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Nome completo</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.name }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.email || 'Não informado' }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Telefone</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.phone }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">CPF</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.cpf || 'Não informado' }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Endereço</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.address || 'Não informado' }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Data de nascimento</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ client.birth_date ? formatDate(client.birth_date) : 'Não informado' }}
            </dd>
          </div>
          <div v-if="client.notes" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Observações</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.notes }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Pets Section -->
    <div class="mt-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h2 class="text-lg font-medium text-gray-900">Pets</h2>
          <p class="mt-1 text-sm text-gray-700">Lista de pets do cliente.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            @click="showAddPetModal = true"
            type="button"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
          >
            Adicionar Pet
          </button>
        </div>
      </div>

      <div v-if="client?.pets?.length > 0" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="pet in client.pets"
          :key="pet.id"
          class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow"
        >
          <div class="p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                  <span class="text-lg font-medium text-gray-700">
                    {{ pet.name.charAt(0) }}
                  </span>
                </div>
              </div>
              <div class="ml-4 flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ pet.name }}</h3>
                <p class="text-sm text-gray-500">{{ pet.species }} - {{ pet.breed || 'SRD' }}</p>
              </div>
            </div>
            
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Idade:</span>
                <span class="ml-1 font-medium">{{ pet.age || 'N/A' }} anos</span>
              </div>
              <div>
                <span class="text-gray-500">Sexo:</span>
                <span class="ml-1 font-medium">{{ pet.gender === 'male' ? 'Macho' : 'Fêmea' }}</span>
              </div>
            </div>

            <div class="mt-4 flex justify-between">
              <router-link
                :to="`/pets/${pet.id}`"
                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
              >
                Ver Detalhes
              </router-link>
              <div class="space-x-2">
                <button
                  @click="editPet(pet)"
                  class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                >
                  Editar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="mt-6 text-center py-12 bg-white rounded-lg shadow">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum pet cadastrado</h3>
        <p class="mt-1 text-sm text-gray-500">Comece adicionando um pet para este cliente.</p>
      </div>
    </div>

    <!-- Recent Appointments -->
    <div class="mt-8">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Consultas Recentes</h2>
      <div v-if="recentAppointments.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="appointment in recentAppointments" :key="appointment.id" class="px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">
                      {{ appointment.pet.name.charAt(0) }}
                    </span>
                  </div>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-900">
                    {{ appointment.pet.name }} - {{ appointment.reason }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ formatDateTime(appointment.scheduled_at) }} - Dr. {{ appointment.veterinarian.name }}
                  </p>
                </div>
              </div>
              <div class="flex items-center">
                <span :class="getStatusClass(appointment.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusText(appointment.status) }}
                </span>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div v-else class="text-center py-8 bg-white rounded-lg shadow">
        <p class="text-gray-500">Nenhuma consulta encontrada.</p>
      </div>
    </div>

    <!-- Add Pet Modal -->
    <PetModal
      v-if="showAddPetModal || showEditPetModal"
      :pet="selectedPet"
      :is-edit="showEditPetModal"
      :clients="[client]"
      @close="closePetModal"
      @saved="handlePetSaved"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { clientService } from '../services/clientService'
import { appointmentService } from '../services/appointmentService'
import { useToastStore } from '../stores/toast'
import PetModal from '../components/PetModal.vue'

const route = useRoute()
const toastStore = useToastStore()

const client = ref(null)
const recentAppointments = ref([])
const loading = ref(false)

const showAddPetModal = ref(false)
const showEditPetModal = ref(false)
const selectedPet = ref(null)

const loadClient = async () => {
  loading.value = true
  try {
    const response = await clientService.getClient(route.params.id)
    client.value = response.data
  } catch (error) {
    toastStore.error('Erro ao carregar cliente')
  } finally {
    loading.value = false
  }
}

const loadRecentAppointments = async () => {
  try {
    const response = await appointmentService.getAppointments({
      client_id: route.params.id,
      per_page: 10
    })
    recentAppointments.value = response.data.data
  } catch (error) {
    console.error('Error loading appointments:', error)
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR')
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('pt-BR')
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

const editPet = (pet) => {
  selectedPet.value = pet
  showEditPetModal.value = true
}

const closePetModal = () => {
  showAddPetModal.value = false
  showEditPetModal.value = false
  selectedPet.value = null
}

const handlePetSaved = () => {
  closePetModal()
  loadClient()
}

onMounted(() => {
  loadClient()
  loadRecentAppointments()
})
</script>
