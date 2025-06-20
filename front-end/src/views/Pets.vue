<template>
  <div class="p-6">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Pets</h1>
        <p class="mt-2 text-sm text-gray-700">
          Lista de todos os pets cadastrados no sistema.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button
          @click="showCreateModal = true"
          type="button"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
        >
          Novo Pet
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar pets..."
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          @input="debouncedSearch"
        />
      </div>
      <div>
        <select
          v-model="selectedSpecies"
          @change="loadPets()"
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">Todas as espécies</option>
          <option value="Cão">Cão</option>
          <option value="Gato">Gato</option>
          <option value="Pássaro">Pássaro</option>
          <option value="Coelho">Coelho</option>
          <option value="Outros">Outros</option>
        </select>
      </div>
      <div>
        <select
          v-model="selectedClient"
          @change="loadPets()"
          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
          <option value="">Todos os clientes</option>
          <option v-for="client in clients" :key="client.id" :value="client.id">
            {{ client.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="mt-8 text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      <p class="mt-2 text-sm text-gray-500">Carregando pets...</p>
    </div>

    <!-- Pets Grid -->
    <div v-else-if="pets.length > 0" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="pet in pets"
        :key="pet.id"
        class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow"
      >
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                <span class="text-xl font-medium text-gray-700">
                  {{ pet.name.charAt(0) }}
                </span>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-lg font-medium text-gray-900">{{ pet.name }}</h3>
              <p class="text-sm text-gray-500">{{ pet.species }} - {{ pet.breed || 'SRD' }}</p>
              <p class="text-sm text-gray-500">{{ pet.client?.name || 'Cliente não encontrado' }}</p>
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
            <div>
              <span class="text-gray-500">Peso:</span>
              <span class="ml-1 font-medium">{{ pet.weight || 'N/A' }} kg</span>
            </div>
            <div>
              <span class="text-gray-500">Cor:</span>
              <span class="ml-1 font-medium">{{ pet.color || 'N/A' }}</span>
            </div>
          </div>

          <div class="mt-6 flex justify-between">
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
              <button
                @click="deletePet(pet)"
                class="text-red-600 hover:text-red-900 text-sm font-medium"
              >
                Excluir
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum pet encontrado</h3>
      <p class="mt-1 text-sm text-gray-500">
        {{ hasFilters ? 'Tente ajustar os filtros de busca.' : 'Comece cadastrando um novo pet.' }}
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
          Cadastrar Primeiro Pet
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.total > pagination.per_page" class="mt-8">
      <nav class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="loadPets(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Anterior
          </button>
          <button
            @click="loadPets(pagination.current_page + 1)"
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
    <PetModal
      v-if="showCreateModal || showEditModal"
      :pet="selectedPet"
      :is-edit="showEditModal"
      :clients="clients"
      @close="closeModal"
      @saved="handlePetSaved"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { petService } from '../services/petService'
import { clientService } from '../services/clientService'
import { useToastStore } from '../stores/toast'
import PetModal from '../components/PetModal.vue'
import { useDebounceFn } from '@vueuse/core'

const toastStore = useToastStore()

const pets = ref([])
const clients = ref([])
const pagination = ref({})
const searchQuery = ref('')
const selectedSpecies = ref('')
const selectedClient = ref('')
const loading = ref(false)
const showDebug = ref(process.env.NODE_ENV === 'development') // Só mostra em desenvolvimento

const showCreateModal = ref(false)
const showEditModal = ref(false)
const selectedPet = ref(null)

const hasFilters = computed(() => {
  return searchQuery.value.trim() || selectedSpecies.value || selectedClient.value
})

const loadPets = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: 15
    }
    
    // Só adiciona os filtros se tiverem valor
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim()
    }
    
    if (selectedSpecies.value) {
      params.species = selectedSpecies.value
    }
    
    if (selectedClient.value) {
      params.client_id = selectedClient.value
    }
    
    console.log('Loading pets with params:', params)
    
    const response = await petService.getPets(params)
    console.log('Pets response:', response.data)
    
    pets.value = response.data.data || []
    pagination.value = response.data.meta || response.data
    
    console.log('Pets loaded:', pets.value.length)
  } catch (error) {
    console.error('Error loading pets:', error)
    toastStore.error('Erro ao carregar pets: ' + (error.response?.data?.message || error.message))
  } finally {
    loading.value = false
  }
}

const loadClients = async () => {
  try {
    const response = await clientService.getClients({ per_page: 1000 })
    clients.value = response.data.data || []
    console.log('Clients loaded:', clients.value.length)
  } catch (error) {
    console.error('Error loading clients:', error)
    toastStore.error('Erro ao carregar clientes')
  }
}

const debouncedSearch = useDebounceFn(() => {
  loadPets()
}, 500)

const editPet = (pet) => {
  selectedPet.value = pet
  showEditModal.value = true
}

const deletePet = async (pet) => {
  if (confirm(`Tem certeza que deseja excluir o pet ${pet.name}?`)) {
    try {
      await petService.deletePet(pet.id)
      toastStore.success('Pet excluído com sucesso!')
      loadPets()
    } catch (error) {
      toastStore.error('Erro ao excluir pet')
    }
  }
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedSpecies.value = ''
  selectedClient.value = ''
  loadPets()
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  selectedPet.value = null
}

const handlePetSaved = () => {
  closeModal()
  loadPets()
}

onMounted(async () => {
  console.log('Pets view mounted, loading data...')
  try {
    await Promise.all([loadPets(), loadClients()])
  } catch (error) {
    console.error('Error in onMounted:', error)
  }
})
</script>
