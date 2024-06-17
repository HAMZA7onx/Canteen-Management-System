<template>
  <div class="container mx-auto px-4">
    <div class="mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateBadgeModal"
      >
        Create Badge
      </button>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              User
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              RFID
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="badge in badges" :key="badge.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ badge.user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ badge.rfid }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ badge.status }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditBadgeModal(badge)"
              >
                Edit status
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal
      v-if="showCreateBadgeModal"
      :show="showCreateBadgeModal"
      title="Create Badge"
      @close="closeCreateBadgeModal"
    >
      <badge-form :badge="newBadge" @create="createBadge" @close="closeCreateBadgeModal" />
    </modal>
    <modal
      v-if="showEditBadgeModal"
      :show="showEditBadgeModal"
      title="Edit Badge"
      @close="closeEditBadgeModal"
    >
      <badge-form :badge="selectedBadge" @update="updateBadge" @close="closeEditBadgeModal" />
    </modal>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import BadgeForm from '@/components/Badge/BadgeForm.vue'
import Modal from '@/components/shared/Modal.vue'

export default {
  components: {
    BadgeForm,
    Modal
  },
  data() {
    return {
      showCreateBadgeModal: false,
      showEditBadgeModal: false,
      selectedBadge: null,
      newBadge: {
        user_id: '',
        rfid: '',
        status: 'active'
      }
    }
  },
  computed: {
    ...mapState('badge', ['badges'])
  },
  created() {
    this.fetchBadges()
  },
  methods: {
    ...mapActions('badge', ['fetchBadges', 'createBadge', 'updateBadge', 'deleteBadge']),
    openCreateBadgeModal() {
      this.showCreateBadgeModal = true
    },
    closeCreateBadgeModal() {
      this.showCreateBadgeModal = false
      this.newBadge = {
        user_id: '',
        rfid: '',
        status: 'active'
      }
    },
    openEditBadgeModal(badge) {
      this.selectedBadge = { ...badge }
      this.showEditBadgeModal = true
    },
    closeEditBadgeModal() {
      this.showEditBadgeModal = false
      this.selectedBadge = null
    }
  }
}
</script>
