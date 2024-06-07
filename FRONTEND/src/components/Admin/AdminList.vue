<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Admins List</h1>

    <div class="mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateAdminModal"
      >
        Create Admin
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
              Name
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Email
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
          <tr v-for="admin in admins" :key="admin.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ admin.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ admin.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditAdminModal(admin)"
              >
                Edit
              </button>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="deleteAdmin(admin)"
              >
                Delete
              </button>
              <button
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                @click="openManageRolesPermissionsModal(admin)"
              >
                Manage Roles/Permissions
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Admin Modal -->
    <Modal
      v-if="showCreateAdminModal"
      :show="showCreateAdminModal"
      @close="closeCreateAdminModal"
      title="Create Admin"
    >
      <AdminForm :admin="{}" @update:admin="createAdmin" />
    </Modal>

    <!-- Edit Admin Modal -->
    <Modal
      v-if="showEditAdminModal"
      :show="showEditAdminModal"
      @close="closeEditAdminModal"
      title="Edit Admin"
    >
      <AdminForm :admin="selectedAdmin" @update:admin="updateAdmin" />
    </Modal>

    <!-- Manage Roles/Permissions Modal -->
    <Modal
      v-if="showManageRolesPermissionsModal"
      :show="showManageRolesPermissionsModal"
      @close="closeManageRolesPermissionsModal"
      title="Manage Roles/Permissions"
    >
      <AdminRolesPermissions :admin="selectedAdmin" />
    </Modal>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import AdminForm from '@/components/Admin/AdminForm.vue';
import AdminRolesPermissions from '@/components/Admin/AdminRolesPermissions.vue';
import Modal from '@/components/shared/Modal.vue';

export default {
  name: 'AdminList',
  components: {
    AdminForm,
    AdminRolesPermissions,
    Modal,
  },
  data() {
    return {
      showCreateAdminModal: false,
      showEditAdminModal: false,
      showManageRolesPermissionsModal: false,
      selectedAdmin: null,
    };
  },
  computed: {
    ...mapGetters('admin', ['admins']),
  },
  created() {
    this.fetchAdmins();
  },
  methods: {
    ...mapActions('admin', ['fetchAdmins', 'createAdmin', 'deleteAdmin']),
    openCreateAdminModal() {
      this.showCreateAdminModal = true;
    },
    closeCreateAdminModal() {
      this.showCreateAdminModal = false;
    },
    openEditAdminModal(admin) {
      this.selectedAdmin = { ...admin };
      this.showEditAdminModal = true;
    },
    closeEditAdminModal() {
      this.showEditAdminModal = false;
      this.selectedAdmin = null;
    },
    updateAdmin(updatedAdmin) {
      this.$store
        .dispatch('admin/updateAdmin', updatedAdmin)
        .then(() => {
          this.closeEditAdminModal();
        })
        .catch((error) => {
          console.error('Error updating admin:', error);
        });
    },
    deleteAdmin(admin) {
      if (confirm(`Are you sure you want to delete ${admin.name}?`)) {
        this.deleteAdmin(admin.id)
          .then(() => {
            // Handle success
          })
          .catch((error) => {
            console.error('Error deleting admin:', error);
          });
      }
    },
    openManageRolesPermissionsModal(admin) {
      this.selectedAdmin = { ...admin };
      this.showManageRolesPermissionsModal = true;
    },
    closeManageRolesPermissionsModal() {
      this.showManageRolesPermissionsModal = false;
      this.selectedAdmin = null;
    },
  },
};
</script>

