<template>
  <div class="bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-gray-800 dark:to-indigo-900 min-h-screen py-4 sm:py-6 md:py-8 px-2 sm:px-4 md:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Descriptive Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 transition-colors duration-300 border border-gray-300">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-indigo-800 dark:text-indigo-300 mb-4">Tableau de bord de gestion des administrateurs</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mb-4">
          Gérez efficacement les comptes administrateurs, contrôlez les niveaux d'accès et maintenez la sécurité du système. Créez, modifiez et supervisez les rôles d'administrateur pour assurer un fonctionnement fluide et une gouvernance robuste.
        </p>
        <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
          <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
          </svg>
          Créez et gérez facilement les comptes administrateurs
        </div>
      </div>

      <!-- Admin Actions -->
      <div class="mb-6">
        <button
          class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition-colors duration-300"
          @click="openCreateAdminModal"
        >
          Créer un administrateur
        </button>
      </div>
 
      <!-- Admin List -->
      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition-colors duration-300">
        <loading-wheel v-if="isLoading" />
        <div v-else-if="error" class="p-4 text-red-600 dark:text-red-400">
          {{ error }}
          <button @click="loadAdmins" class="ml-2 underline">Réessayer</button>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Email</th>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <template v-for="admin in admins" :key="admin.id">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                  <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" :src="admin.avatar || 'https://via.placeholder.com/40'" alt="">
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ admin.name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 sm:hidden">{{ admin.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-4 whitespace-nowrap hidden sm:table-cell">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ admin.email }}</div>
                  </td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2 sm:hidden">
                      <button @click="toggleExpandRow(admin.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-200">
                        <font-awesome-icon :icon="expandedRows.includes(admin.id) ? 'chevron-up' : 'chevron-down'" />
                      </button>
                    </div>
                    <div class="hidden sm:flex space-x-2">
                      <button @click="openEditAdminModal(admin)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-200">
                        <font-awesome-icon icon="edit" />
                      </button>
                      <button @click="deleteAdmin(admin)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-200">
                        <font-awesome-icon icon="trash" />
                      </button>
                      <button @click="openManageRolesPermissionsModal(admin)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 transition-colors duration-200">
                        <font-awesome-icon icon="user-cog" />
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="expandedRows.includes(admin.id)" class="bg-gray-50 dark:bg-gray-700 sm:hidden">
                  <td colspan="3" class="px-3 py-4">
                    <div class="flex flex-col space-y-2">
                      <button @click="openEditAdminModal(admin)" class="flex items-center justify-center w-full bg-blue-500 hover:bg-blue-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="edit" class="mr-2" />
                        Edit
                      </button>
                      <button @click="deleteAdmin(admin)" class="flex items-center justify-center w-full bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="trash" class="mr-2" />
                        Delete
                      </button>
                      <button @click="openManageRolesPermissionsModal(admin)" class="flex items-center justify-center w-full bg-emerald-500 hover:bg-emerald-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="user-cog" class="mr-2" />
                        Manage Roles
                      </button>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Create Admin Modal -->
      <Overlay v-if="showCreateAdminModal">
        <Modal
          :show="showCreateAdminModal"
          @close="closeCreateAdminModal"
          title="Créer un administrateur"
        >
        <AdminForm :admin="{}" @admin-created="onAdminCreated" />
        </Modal>
      </Overlay>

      <!-- Edit Admin Modal -->
      <Overlay v-if="showEditAdminModal">
        <Modal
          :show="showEditAdminModal"
          @close="closeEditAdminModal"
          title="Modifier l'administrateur"
        >
        <AdminForm :admin="selectedAdmin" @admin-updated="onAdminUpdated" />
        </Modal>
      </Overlay>

      <!-- Manage Roles/Permissions Modal -->
      <Overlay v-if="showManageRolesPermissionsModal">
        <Modal
          :show="showManageRolesPermissionsModal"
          @close="closeManageRolesPermissionsModal"
          title="Gérer les rôles/permissions"
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
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
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
                    Supprimer l'administrateur
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous sûr de vouloir supprimer {{ adminToDelete.name }} ? Cette action ne peut pas être annulée.
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
                Supprimer
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-300"
                @click="showDeleteConfirmation = false"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import AdminForm from '@/components/Admin/AdminForm.vue';
import AdminRolesPermissions from '@/components/Admin/AdminRolesPermissions.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import Toast from '@/components/shared/Toast.vue';

export default {
  name: 'AdminList',
  components: {
    AdminForm,
    AdminRolesPermissions,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
  data() {
    return {
      showCreateAdminModal: false,
      showEditAdminModal: false,
      showManageRolesPermissionsModal: false,
      showDeleteConfirmation: false,
      selectedAdmin: null,
      adminToDelete: null,
      isLoading: true,
      error: null,
      expandedRows: [],
      showToast: false,
      toastMessage: '',
    };
  },
  computed: {
    ...mapGetters('admin', ['admins']),
  },
  created() {
    this.loadAdmins();
  },
  methods: {
    ...mapActions('admin', ['fetchAdmins', 'createAdmin', 'deleteAdmin']),
    async loadAdmins() {
      this.isLoading = true;
      this.error = null;
      try {
        await this.fetchAdmins();
      } catch (error) {
        console.error('Error fetching admins:', error);
        this.error = 'Failed to load admins. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
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
    createAdmin(newAdmin) {
      this.$store
        .dispatch('admin/createAdmin', newAdmin)
        .then(() => {
          this.closeCreateAdminModal();
          this.loadAdmins();
          this.showSuccessToast('Admin created successfully!');
        })
        .catch((error) => {
          console.error('Error creating admin:', error);
        });
    },
    updateAdmin(updatedAdmin) {
      this.$store
        .dispatch('admin/updateAdmin', updatedAdmin)
        .then(() => {
          this.closeEditAdminModal();
          this.loadAdmins();
          this.showSuccessToast('Admin updated successfully!');
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
          this.loadAdmins();
          this.showSuccessToast('Admin deleted successfully!');
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
    toggleExpandRow(adminId) {
      const index = this.expandedRows.indexOf(adminId);
      if (index > -1) {
        this.expandedRows.splice(index, 1);
      } else {
        this.expandedRows.push(adminId);
      }
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
    onAdminCreated(createdAdmin) {
      this.closeCreateAdminModal();
      this.loadAdmins();
      this.showSuccessToast('Admin created successfully!');
    },
    onAdminUpdated(updatedAdmin) {
      this.closeEditAdminModal();
      this.loadAdmins();
      this.showSuccessToast('Admin updated successfully!');
    },
  },
};
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.expanded-row {
  animation: fadeIn 0.3s ease-in-out;
}
</style>
