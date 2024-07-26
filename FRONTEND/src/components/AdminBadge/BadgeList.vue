<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-100 to-cyan-200 dark:from-gray-900 dark:to-cyan-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Section d'en-tête -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-cyan-600 mb-4">Gestion des Badges Administrateurs</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Section de gestion des badges RFID des administrateurs. Importez, attribuez et suivez les badges de vos administrateurs.
        </p>
        <div class="flex items-center text-sm text-teal-600 dark:text-teal-400">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          Simplifiez votre processus de gestion des badges
        </div>
      </div>

      <!-- Boutons d'action et barre de recherche -->
      <div class="mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
        <button
          v-if="$can('importer_badges_administrateurs')"
          class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300"
          @click="showImportModal = true"
        >
          Importer des RFID
        </button>
        <div class="flex-grow flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
          <select
            v-model="searchOption"
            class="w-full sm:w-auto px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="rfid">RFID</option>
            <option value="name">Nom</option>
          </select>
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="`Rechercher par ${searchOption}...`"
            class="w-full sm:flex-grow px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white"
          />
        </div>
      </div>

      <!-- Tableau des badges -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
          <loading-wheel v-if="isLoading" />
          <table v-show="!isLoading" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                  @click="sortByRfid"
                >
                  RFID
                  <span v-if="sortBy === 'rfid'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                  @click="sortByEmail"
                >
                  Nom
                  <span v-if="sortBy === 'email'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                  @click="sortByStatus"
                >
                  Statut
                  <span v-if="sortBy === 'status'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer"
                  @click="sortByUpdatedAt"
                >
                Dernière mise à jour
                  <span v-if="sortBy === 'updated_at'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Détails</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="badge in paginatedBadges" :key="badge.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ badge.rfid }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ getUserName(badge) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(badge.status)">
                    {{ badge.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatDate(badge.updated_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-200 transition-colors duration-300"
                    @click="showDetails(badge)"
                  >
                    Voir les détails
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    v-if="$can('gerer_badges_administrateurs')"
                    class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-200 mr-3 transition-colors duration-300"
                    @click="editBadge(badge)"
                    :disabled="badge.status === 'lost'"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    v-if="$can('gerer_badges_administrateurs')"
                    :class="[
                      'transition-colors duration-300',
                      badge.status === 'available'
                        ? 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200'
                        : 'text-white dark:text-gray-600 cursor-not-allowed'
                    ]"
                    @click="deleteBadge(badge)"
                    :disabled="badge.status !== 'available'"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <ul v-show="!isLoading" class="divide-y divide-gray-200 dark:divide-gray-700 md:hidden">
            <li v-for="badge in paginatedBadges" :key="badge.id" class="py-4 px-4">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ badge.rfid }}</span>
                <button
                  class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-200"
                  @click="toggleBadgeActions(badge)"
                >
                  <font-awesome-icon icon="ellipsis-v" />
                </button>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ getUserName(badge) }}</p>
              <span :class="getStatusClass(badge.status)" class="mt-1 inline-block">
                {{ badge.status }}
              </span>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Last Updated: {{ formatDate(badge.updated_at) }}</p>
              <div v-if="badge.showActions" class="mt-2 space-y-2">
                <button
                  class="w-full text-left text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-200 transition-colors duration-300 flex items-center"
                  @click="showDetails(badge)"
                >
                  <font-awesome-icon icon="info-circle" class="mr-2" />
                  Voir les détails
                </button>
                <button
                  v-if="$can('gerer_badges_administrateurs')"
                  class="w-full text-left text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-200 transition-colors duration-300 flex items-center"
                  @click="editBadge(badge)"
                  :disabled="badge.status === 'lost'"
                >
                  <font-awesome-icon icon="edit" class="mr-2" />
                  Modifier
                </button>
                <button
                  v-if="$can('gerer_badges_administrateurs')"
                  class="w-full text-left transition-colors duration-300 flex items-center"
                  :class="[
                    badge.status === 'available'
                      ? 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200'
                      : 'text-gray-400 cursor-not-allowed'
                  ]"
                  @click="deleteBadge(badge)"
                  :disabled="badge.status !== 'available'"
                >
                  <font-awesome-icon icon="trash" class="mr-2" />
                  Supprimer
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between items-center">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 font-bold text-gray-500 bg-white rounded-full border border-gray-300 hover:bg-gray-100 disabled:opacity-50"
        >
          Previous
        </button>
        <span class="text-gray-700 dark:text-gray-300">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 font-bold text-gray-500 bg-white rounded-full border border-gray-300 hover:bg-gray-100 disabled:opacity-50"
        >
          Next
        </button>
      </div>

      <!-- Composants modaux -->
      <Overlay v-if="showImportModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showImportModal" title="Importer des RFID" @close="showImportModal = false">
            <ImportRfidsForm @import-success="handleImportSuccess" />
          </Modal>
        </div>
      </Overlay>

      <Overlay v-if="showEditModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showEditModal" title="Modifier le badge" @close="showEditModal = false">
            <div v-if="selectedBadge && selectedBadge.status === 'lost'">
              <p class="text-red-500 dark:text-red-400">Aucune opération ne peut être effectuée sur ce RFID, il est marqué comme perdu.</p>
            </div>
            <MarkAsLostModal
              v-else-if="selectedBadge && selectedBadge.status === 'assigned'"
              :badge="selectedBadge"
              @update-success="handleUpdateSuccess"
            />
            <AssignRfidModal
              v-else-if="selectedBadge && selectedBadge.status === 'available'"
              :key="eligibleUsersKey"
              :badge="selectedBadge"
              @update-success="handleUpdateSuccess"
            />
          </Modal>
        </div>
      </Overlay>

      <Overlay v-if="showDetailsModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showDetailsModal" title="Détails du badge" @close="showDetailsModal = false">
            <div v-if="selectedBadge" class="text-gray-700 dark:text-gray-300">
              <p><strong>Créateur :</strong> {{ selectedBadge.creator }}</p>
              <p class="mt-2"><strong>Éditeurs :</strong></p>
              <ul v-if="selectedBadge.editors && selectedBadge.editors.length > 0" class="list-disc list-inside">
                <li v-for="editor in selectedBadge.editors" :key="editor">{{ editor }}</li>
              </ul>
              <p v-else class="text-gray-500 dark:text-gray-400">Aucun éditeur</p>
              <p class="mt-2"><strong>Créé le :</strong> {{ formatDate(selectedBadge.created_at) }}</p>
              <p class="mt-2">
                <strong>Mis à jour le :</strong>
                <span v-if="isUpdatedAtSameAsCreatedAt(selectedBadge)">
                  {{ formatDate(selectedBadge.updated_at) }} (Aucune mise à jour)
                </span>
                <span v-else>
                  {{ formatDate(selectedBadge.updated_at) }}
                </span>
              </p>
            </div>
          </Modal>
        </div>
      </Overlay>

      <!-- Modal de confirmation de suppression -->
      <Overlay v-if="showDeleteConfirmation">
        <div class="modal-container" @click.stop>
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Confirmer la suppression du badge
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous certain de vouloir supprimer le badge "{{ badgeToDelete?.rfid }}" ? Cette action est irréversible et peut affecter l'accès des utilisateurs dans tout le système.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDelete"
              >
                Confirmer la suppression
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-300"
                @click="closeDeleteConfirmation"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </Overlay>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import permissionMixin from '@/mixins/permissionMixin'; 
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import ImportRfidsForm from '@/components/AdminBadge/ImportRfidsForm.vue';
import MarkAsLostModal from '@/components/AdminBadge/MarkAsLostModal.vue';
import AssignRfidModal from '@/components/AdminBadge/AssignRfidModal.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';

export default {
  mixins: [permissionMixin],
  components: {
    Modal,
    Overlay,
    ImportRfidsForm,
    MarkAsLostModal,
    AssignRfidModal,
    LoadingWheel,
  },
  data() {
    return {
      showImportModal: false,
      showEditModal: false,
      showDetailsModal: false,
      showDeleteConfirmation: false,
      selectedBadge: null,
      eligibleUsersKey: 0,
      badgeToDelete: null,
      sortBy: null,
      sortDirection: 'asc',
      isLoading: true,
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      searchType: 'all',
      searchOption: 'rfid',
    };
  },
  computed: {
    ...mapGetters('adminBadge', ['badges']),
    getUserName() {
      return (badge) => {
        if (badge.admin) {
          return badge.admin.name;
        } else if (badge.userId) {
          const user = this.users.find(u => u.id === badge.userId);
          return user ? user.name : 'Unassigned';
        } else {
          return 'Unassigned';
        }
      };
    },
    filteredBadges() {
      return this.badges.filter(badge => 
        badge.rfid.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        this.getUserName(badge).toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    sortedBadges() {
      let sortedBadges = [...this.filteredBadges];

      if (this.sortBy === 'email') {
        sortedBadges.sort((a, b) => {
          const emailA = this.getUserName(a).toLowerCase();
          const emailB = this.getUserName(b).toLowerCase();
          return this.sortDirection === 'asc' ? emailA.localeCompare(emailB) : emailB.localeCompare(emailA);
        });
      } else if (this.sortBy === 'status') {
        sortedBadges.sort((a, b) => {
          const statusA = a.status.toLowerCase();
          const statusB = b.status.toLowerCase();
          return this.sortDirection === 'asc' ? statusA.localeCompare(statusB) : statusB.localeCompare(statusA);
        });
      } else if (this.sortBy === 'updated_at') {
        sortedBadges.sort((a, b) => {
          const dateA = new Date(a.updated_at);
          const dateB = new Date(b.updated_at);
          return this.sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
        });
      }

      return sortedBadges;
    },
    paginatedBadges() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.sortedBadges.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.sortedBadges.length / this.itemsPerPage);
    },
    searchPlaceholder() {
      switch (this.searchType) {
        case 'name':
          return 'Search by name';
        case 'rfid':
          return 'Search by RFID';
        default:
          return 'Search by name or RFID';
      }
    },
    filteredBadges() {
      return this.badges.filter(badge => {
        const name = this.getUserName(badge).toLowerCase();
        const rfid = badge.rfid.toLowerCase();
        const query = this.searchQuery.toLowerCase();

        switch (this.searchType) {
          case 'name':
            return name.includes(query);
          case 'rfid':
            return rfid.includes(query);
          default:
            return name.includes(query) || rfid.includes(query);
        }
      });
    },
  },
  created() {
    this.fetchBadges();
    this.fetchEligibleUsers();
    this.loadBadges();
  },
  methods: {
    ...mapActions('adminBadge', ['fetchBadges', 'deleteBadge', 'updateBadge']),
    editBadge(badge) {
      if (this.$can('gerer_badges_administrateurs')) {
        this.selectedBadge = badge;
        this.showEditModal = true;
      }
    },
    deleteBadge(badge) {
      if (this.$can('gerer_badges_administrateurs') && badge.status === 'available') {
        this.badgeToDelete = badge;
        this.showDeleteConfirmation = true;
      }
    },
    confirmDelete() {
      if (this.$can('gerer_badges_administrateurs') && this.badgeToDelete) {
        this.$store.dispatch('adminBadge/deleteBadge', this.badgeToDelete.id)
          .then(() => {
            console.log('Badge deleted successfully');
            this.loadBadges();
          })
          .catch((error) => {
            console.error('Error deleting badge:', error);
          })
          .finally(() => {
            this.closeDeleteConfirmation();
          });
      }
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false;
      this.badgeToDelete = null;
    },
    handleImportSuccess() {
      this.showImportModal = false;
      this.fetchBadges();
      this.loadBadges();
    },
    handleUpdateSuccess(updatedBadge) {
      this.showEditModal = false;
      this.selectedBadge = null;
      this.loadBadges();

      if (updatedBadge) {
        const index = this.badges.findIndex(badge => badge.id === updatedBadge.id);
        if (index !== -1) {
          this.badges.splice(index, 1, updatedBadge);
        }
      } else {
        console.error('Invalid updated badge data:', updatedBadge);
      }

      this.fetchEligibleUsers();
      this.eligibleUsersKey++;
    },
    fetchEligibleUsers() {
      this.$store.dispatch('adminBadge/fetchEligibleUsers');
      this.eligibleUsersKey++;
    },
    getStatusClass(status) {
      switch (status) {
        case 'assigned':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200';
        case 'available':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200';
        case 'lost':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200';
        default:
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
      }
    },
    showDetails(badge) {
      this.selectedBadge = badge;
      this.showDetailsModal = true;
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    isUpdatedAtSameAsCreatedAt(badge) {
      const createdAt = new Date(badge.created_at);
      const updatedAt = new Date(badge.updated_at);
      return createdAt.getTime() === updatedAt.getTime();
    },
    sortByRfid() {
      this.setSortBy('rfid');
    },
    sortByEmail() {
      this.setSortBy('email');
    },
    sortByStatus() {
      this.setSortBy('status');
    },
    sortByUpdatedAt() {
      this.setSortBy('updated_at');
    },
    setSortBy(field) {
      if (this.sortBy === field) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortBy = field;
        this.sortDirection = 'asc';
      }
    },
    async loadBadges() {
      this.isLoading = true;
      try {
        await this.fetchBadges();
        await this.fetchEligibleUsers();
      } catch (error) {
        console.error('Error fetching badges:', error);
      } finally {
        this.isLoading = false;
      }
    },
    toggleBadgeActions(badge) {
      badge.showActions = !badge.showActions;
      this.$forceUpdate();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>

<style scoped>
.modal-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.modal-overlay {
  z-index: 40;
}

.modal-content {
  z-index: 50;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes slideIn {
  from { transform: translateY(-20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.slide-in {
  animation: slideIn 0.3s ease-in-out;
}
</style>
