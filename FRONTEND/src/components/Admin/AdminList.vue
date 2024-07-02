<template>
  <div class="bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-gray-800 dark:to-indigo-900 min-h-screen py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Descriptive Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8 transition-colors duration-300 border border-gray-300">
        <h1 class="text-2xl sm:text-3xl font-bold text-indigo-800 dark:text-indigo-300 mb-4">Admin Management Dashboard</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mb-4">
          Efficiently manage administrator accounts, control access levels, and maintain system security. Create, edit, and oversee admin roles to ensure smooth operation and robust governance.
        </p>
        <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
          <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
          </svg>
          Create and manage admin accounts with ease
        </div>
      </div>

      <!-- Admin Actions -->
      <div class="mb-6">
        <button
          class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition-colors duration-300"
          @click="openCreateAdminModal"
        >
          Create Admin
        </button>
      </div>

      <!-- Admin List -->
      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto transition-colors duration-300">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="admin in admins" :key="admin.id">
              <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ admin.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ admin.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  class="bg-yellow-500 hover:bg-yellow-700 dark:bg-yellow-600 dark:hover:bg-yellow-800 text-white font-bold py-2 px-4 rounded mr-2 transition-colors duration-300"
                  @click="openEditAdminModal(admin)"
                >
                  <font-awesome-icon icon="edit" />
                </button>
                <button
                  class="bg-red-500 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2 transition-colors duration-300"
                  @click="deleteAdmin(admin)"
                >
                  <font-awesome-icon icon="trash" />
                </button>
                <button
                  class="bg-green-500 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition-colors duration-300"
                  @click="openManageRolesPermissionsModal(admin)"
                >
                  Manage Roles
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Create Admin Modal -->
      <Overlay v-if="showCreateAdminModal">
        <Modal
          :show="showCreateAdminModal"
          @close="closeCreateAdminModal"
          title="Create Admin"
        >
          <AdminForm :admin="{}" @update:admin="createAdmin" />
        </Modal>
      </Overlay>

      <!-- Edit Admin Modal -->
      <Overlay v-if="showEditAdminModal">
        <Modal
          :show="showEditAdminModal"
          @close="closeEditAdminModal"
          title="Edit Admin"
        >
          <AdminForm :admin="selectedAdmin" @update:admin="updateAdmin" />
        </Modal>
      </Overlay>

      <!-- Manage Roles/Permissions Modal -->
      <Overlay v-if="showManageRolesPermissionsModal">
        <Modal
          :show="showManageRolesPermissionsModal"
          @close="closeManageRolesPermissionsModal"
          title="Manage Roles/Permissions"
        >
          <AdminRolesPermissions :admin="selectedAdmin" />
        </Modal>
      </Overlay>

      <!-- Delete Confirmation Modal -->
      <div class="fixed z-10 inset-0 overflow-y-auto" v-if="showDeleteConfirmation">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
          </div>
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          <div
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
          >
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Delete Admin
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Are you sure you want to delete {{ adminToDelete.name }}? This action cannot be undone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDeleteAdmin"
              >
                Delete
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-300"
                @click="showDeleteConfirmation = false"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import AdminForm from '@/components/Admin/AdminForm.vue';
import AdminRolesPermissions from '@/components/Admin/AdminRolesPermissions.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';

export default {
  name: 'AdminList',
  components: {
    AdminForm,
    AdminRolesPermissions,
    Modal,
    Overlay,
  },
  data() {
    return {
      showCreateAdminModal: false,
      showEditAdminModal: false,
      showManageRolesPermissionsModal: false,
      showDeleteConfirmation: false,
      selectedAdmin: null,
      adminToDelete: null,
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
      this.adminToDelete = admin;
      this.showDeleteConfirmation = true;
    },
    confirmDeleteAdmin() {
      this.$store
        .dispatch('admin/deleteAdmin', this.adminToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.adminToDelete = null;
        })
        .catch((error) => {
          console.error('Error deleting admin:', error);
          this.showDeleteConfirmation = false;
          this.adminToDelete = null;
        });
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
