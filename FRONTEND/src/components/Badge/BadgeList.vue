<template>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Badge Management</h2>

    <div class="mb-6">
      <button
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-300 ease-in-out"
        @click="showImportModal = true"
      >
        Import RFIDs
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full table-auto">
        <thead class="bg-gray-100">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
              @click="sortByRfid"
            >
              RFID
              <span v-if="sortBy === 'rfid'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
            </th>
            <!-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">RFID</th> -->
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
              @click="sortByEmail"
            >
              Email
              <span v-if="sortBy === 'email'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer"
              @click="sortByStatus"
            >
              Status 
              <span v-if="sortBy === 'status'" :class="sortDirection === 'asc' ? 'inline-block rotate-180' : 'inline-block'">&#9660;</span>
            </th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Details</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="badge in sortedBadges" :key="badge.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">{{ badge.rfid }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ getUserName(badge) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(badge.status)">
                {{ badge.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out"
                @click="showDetails(badge)"
              >
                Details
              </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mr-2 transition duration-300 ease-in-out"
                @click="editBadge(badge)"
                :disabled="badge.status === 'lost'"
              >
                <font-awesome-icon icon="edit" />
              </button>
              <button
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out"
                @click="deleteBadge(badge)"
              >
                <font-awesome-icon icon="trash" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal components (unchanged) -->
    <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay />
      <Modal :show="showImportModal" title="Import RFIDs" @close="showImportModal = false">
        <ImportRfidsForm @import-success="handleImportSuccess" />
      </Modal>
    </div>

    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay class="modal-overlay" />
      <Modal :show="showEditModal" title="Edit Badge" @close="showEditModal = false" class="modal-content">
        <div v-if="selectedBadge && selectedBadge.status === 'lost'">
          <p class="text-red-500">No operation can be performed on this RFID, it is marked as lost.</p>
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

    <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay class="modal-overlay" />
      <Modal :show="showDetailsModal" title="Badge Details" @close="showDetailsModal = false" class="modal-content">
        <div v-if="selectedBadge">
          <p><strong>Creator:</strong> {{ selectedBadge.creator }}</p>

          <p><strong>Editors:</strong></p>
          <ul v-if="selectedBadge.editors && selectedBadge.editors.length > 0">
            <li v-for="editor in selectedBadge.editors" :key="editor">{{ editor }}</li>
          </ul>
          <p v-else class="text-gray-500">No editors</p>

          <p><strong>Created At:</strong> {{ formatDate(selectedBadge.created_at) }}</p>

          <p>
            <strong>Updated At:</strong>
            <span v-if="isUpdatedAtSameAsCreatedAt(selectedBadge)">
              {{ formatDate(selectedBadge.updated_at) }} (No updates)
            </span>
            <span v-else>
              {{ formatDate(selectedBadge.updated_at) }}
            </span>
          </p>
        </div>
      </Modal>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import ImportRfidsForm from '@/components/Badge/ImportRfidsForm.vue';
import MarkAsLostModal from '@/components/Badge/MarkAsLostModal.vue';
import AssignRfidModal from '@/components/Badge/AssignRfidModal.vue';

export default {
  components: {
    Modal,
    Overlay,
    ImportRfidsForm,
    MarkAsLostModal,
    AssignRfidModal,
  },
  data() {
    return {
      showImportModal: false,
      showEditModal: false,
      showDetailsModal: false,
      selectedBadge: null,
      eligibleUsersKey: 0,
      sortBy: null,
      sortDirection: 'asc',
    };
  },
  computed: {
    ...mapGetters('badge', ['badges']),
    getUserName() {
      return (badge) => {
        if (badge.user) {
          return badge.user.email;
        } else if (badge.userId) {
          const user = this.users.find(u => u.id === badge.userId);
          return user ? user.email : 'Unassigned';
        } else {
          return 'Unassigned';
        }
      };
    },
    sortedBadges() {
      let sortedBadges = [...this.badges];

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
      }

      return sortedBadges;
    },
  },
  created() {
    this.fetchBadges();
    this.fetchEligibleUsers();
  },
  methods: {
    ...mapActions('badge', ['fetchBadges', 'deleteBadge', 'updateBadge']),
    editBadge(badge) {
      this.selectedBadge = badge;
      this.showEditModal = true;
    },
    deleteBadge(badge) {
      this.deleteBadge(badge.id)
        .then(() => {
          console.log('Badge deleted successfully');
        })
        .catch((error) => {
          console.error('Error deleting badge:', error);
        });
    },
    handleImportSuccess() {
      this.showImportModal = false;
      this.fetchBadges();
    },
    handleUpdateSuccess(updatedBadge) {
      this.showEditModal = false;
      this.selectedBadge = null;

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
      this.$store.dispatch('badge/fetchEligibleUsers');
      this.eligibleUsersKey++;
    },
    getStatusClass(status) {
      switch (status) {
        case 'assigned':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
        case 'available':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800';
        case 'lost':
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
        default:
          return 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800';
      }
    },
    showDetails(badge) {
      this.selectedBadge = badge;
      this.showDetailsModal = true;
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString();
    },
    isUpdatedAtSameAsCreatedAt(badge) {
      const createdAt = new Date(badge.created_at);
      const updatedAt = new Date(badge.updated_at);
      return createdAt.getTime() === updatedAt.getTime();
    },
    sortByEmail() {
      if (this.sortBy === 'email') {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortBy = 'email';
        this.sortDirection = 'asc';
      }
    },
    sortByStatus() {
      if (this.sortBy === 'status') {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortBy = 'status';
        this.sortDirection = 'asc';
      }
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  z-index: 40;
}

.modal-content {
  z-index: 50;
}
</style>
