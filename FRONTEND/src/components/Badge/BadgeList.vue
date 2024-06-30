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
    <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay />
      <Modal
        :show="showImportModal"
        title="Import RFIDs"
        @close="showImportModal = false"
      >
        <ImportRfidsForm @import-success="handleImportSuccess" />
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

export default {
  components: {
    Modal,
    Overlay,
    ImportRfidsForm,
  },
  data() {
    return {
      showImportModal: false,
    };
  },
  computed: {
    ...mapGetters('badge', ['badges']),
    getUserName() {
      return (badge) => {
        if (badge.user) {
          return badge.user.name;
        } else {
          return 'Unassigned';
        }
      };
    },
  },
  created() {
    this.fetchBadges();
  },
  methods: {
    ...mapActions('badge', ['fetchBadges', 'deleteBadge']),
    editBadge(badge) {
      // Implement edit badge functionality
      console.log('Edit badge:', badge);
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
  },
};
</script>
