<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Badges</h2>
    <div class="mb-4">
      <button
        class="bg-blue-500 text-white px-4 py-2 rounded-md"
        @click="showImportModal = true"
      >
        Import RFIDs
      </button>
    </div>
    <div
      v-if="showImportModal"
      class="fixed inset-0 z-50 flex items-center justify-center"
    >
      <Overlay />
      <Modal
        :show="showImportModal"
        title="Import RFIDs"
        @close="showImportModal = false"
      >
        <ImportRfidsForm @import-success="handleImportSuccess" />
      </Modal>
    </div>
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center"
    >
      <Overlay class="modal-overlay" />
      <Modal
        :show="showEditModal"
        title="Edit Badge"
        @close="showEditModal = false"
        class="modal-content"
      >
        <EditBadgeModal
          :badge="selectedBadge"
          :users="eligibleUsers"
          @update-success="handleUpdateSuccess"
          @update-error="handleUpdateError"
        />
      </Modal>
    </div>
    <table class="w-full table-auto">
      <thead>
        <tr>
          <th class="px-4 py-2">RFID</th>
          <th class="px-4 py-2">User</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="badge in badges" :key="badge.id" class="hover:bg-gray-100">
          <td class="border px-4 py-2">{{ badge.rfid }}</td>
          <td class="border px-4 py-2">{{ getUserName(badge) }}</td>
          <td class="border px-4 py-2">{{ badge.status }}</td>
          <td class="border px-4 py-2">
            <button
              class="bg-blue-500 text-white px-2 py-1 rounded-md mr-2"
              @click="editBadge(badge)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 text-white px-2 py-1 rounded-md"
              @click="deleteBadge(badge)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import ImportRfidsForm from '@/components/Badge/ImportRfidsForm.vue';
import EditBadgeModal from '@/components/Badge/EditBadgeModal.vue';

export default {
  components: {
    Modal,
    Overlay,
    ImportRfidsForm,
    EditBadgeModal,
  },
  data() {
    return {
      showImportModal: false,
      showEditModal: false,
      selectedBadge: null,
      eligibleUsers: [],
    };
  },
  computed: {
    ...mapGetters('badge', ['badges']),
    getUserName() {
      return (badge) => {
        if (badge.user) {
          return badge.user.name;
        } else if (badge.userId) {
          // Find the user name based on the userId
          const user = this.users.find(u => u.id === badge.userId);
          return user ? user.name : 'Unassigned';
        } else {
          return 'Unassigned';
        }
      };
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
    // Update the badge in the badges array
    const index = this.badges.findIndex(badge => badge.id === updatedBadge.id);
    if (index !== -1) {
      this.$set(this.badges, index, updatedBadge);
    }
  } else {
    console.error('Invalid updated badge data:', updatedBadge);
  }
},

    handleUpdateError(error) {
      this.showEditModal = false;
      this.selectedBadge = null;
      console.error('Error updating badge status:', error);
      // Display an error message to the user
    },
    fetchEligibleUsers() {
      // Fetch users with all RFIDs lost
      this.$store.dispatch('badge/fetchUsersWithAllRfidsLost')
        .then(usersWithAllRfidsLost => {
          // Fetch users without any RFIDs
          this.$store.dispatch('badge/fetchUsersWithoutRfids')
            .then(usersWithoutRfids => {
              // Combine the two arrays into one
              this.eligibleUsers = [...usersWithAllRfidsLost.data, ...usersWithoutRfids.data];
            })
            .catch(error => {
              console.error('Error fetching eligible users:', error);
            });
        })
        .catch(error => {
          console.error('Error fetching eligible users:', error);
        });
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
