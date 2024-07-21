<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Descriptive Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 mb-4">Centre d'Orchestration des Rôles</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Bienvenue au centre névralgique du contrôle d'accès de votre système. Ici, vous pouvez sculpter l'équilibre parfait entre les permissions et les responsabilités, en vous assurant que chaque rôle joue sa partition en harmonie avec les besoins de votre organisation.
        </p>
        <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
          Créez des rôles avec précision, attribuez des permissions en toute confiance
        </div>
      </div>

      <!-- Role Actions and Search -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
        <button
          class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300 mb-4 sm:mb-0"
          @click="openCreateRoleModal"
        >
          <span class="mr-2">+</span> Créer un Nouveau Rôle
        </button>
        <div class="relative w-full sm:w-64">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher..."
            class="w-full px-4 py-2 rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
          />
          <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>

      <!-- Role List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <loading-wheel v-if="isLoading" />
        <div v-else-if="error" class="p-4 text-red-600 dark:text-red-400">
          {{ error }}
          <button @click="loadRoles" class="ml-2 underline">Réessayer</button>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hidden sm:table-cell" @click="sortBy('updated_at')">
                  Last Updated
                  <span v-if="sortKey === 'updated_at'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <template v-for="role in paginatedRoles" :key="role.id">
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ role.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 hidden sm:table-cell">{{ formatDate(role.updated_at) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2 sm:hidden">
                      <button @click="toggleExpandRow(role.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-200">
                        <font-awesome-icon :icon="expandedRows.includes(role.id) ? 'chevron-up' : 'chevron-down'" />
                      </button>
                    </div>
                    <div class="hidden sm:flex space-x-2">
                      <button
                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-300"
                        @click="openEditRoleModal(role)"
                      >
                        <font-awesome-icon icon="edit" />
                      </button>
                      <button
                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300"
                        @click="deleteRole(role)"
                      >
                        <font-awesome-icon icon="trash" />
                      </button>
                      <button
                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 transition-colors duration-300"
                        @click="openManagePermissionsModal(role)"
                      >
                        <font-awesome-icon icon="key" /> Permissions
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="expandedRows.includes(role.id)" class="bg-gray-50 dark:bg-gray-700 sm:hidden">
                  <td colspan="3" class="px-6 py-4">
                    <div class="flex flex-col space-y-2">
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Last Updated:</span> {{ formatDate(role.updated_at) }}
                      </div>
                      <button @click="openEditRoleModal(role)" class="flex items-center justify-center w-full bg-blue-500 hover:bg-blue-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="edit" class="mr-2" />
                        Edit
                      </button>
                      <button @click="deleteRole(role)" class="flex items-center justify-center w-full bg-red-500 hover:bg-red-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="trash" class="mr-2" />
                        Delete
                      </button>
                      <button @click="openManagePermissionsModal(role)" class="flex items-center justify-center w-full bg-green-500 hover:bg-green-600 text-white px-2 py-2 rounded-md transition-colors duration-300">
                        <font-awesome-icon icon="key" class="mr-2" />
                        Manage Permissions
                      </button>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
              Previous
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing
                <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                to
                <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredRoles.length) }}</span>
                of
                <span class="font-medium">{{ filteredRoles.length }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <span class="sr-only">Previous</span>
                  <font-awesome-icon icon="chevron-left" class="h-5 w-5" />
                </button>
                <button v-for="page in displayedPages" :key="page" @click="goToPage(page)" :class="['relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium', currentPage === page ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700']">
                  {{ page }}
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <span class="sr-only">Next</span>
                  <font-awesome-icon icon="chevron-right" class="h-5 w-5" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <!-- Modals -->
      <Overlay v-if="showCreateRoleModal">
        <Modal :show="showCreateRoleModal" @close="closeCreateRoleModal" title="Créer un Nouveau Rôle">
          <RoleForm :role="{}" @role-created="onRoleCreated" />
        </Modal>
      </Overlay>

      <Overlay v-if="showEditRoleModal">
        <Modal :show="showEditRoleModal" @close="closeEditRoleModal" title="Affiner les Détails du Rôle">
          <RoleForm :role="selectedRole" @role-updated="onRoleUpdated" />
        </Modal>
      </Overlay>

      <Overlay v-if="showManagePermissionsModal">
        <Modal :show="showManagePermissionsModal" @close="closeManagePermissionsModal" title="Orchestrer les Permissions">
          <RolePermissions :role="selectedRole" />
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
              Supprimer le rôle
            </h3>
            <div class="mt-2">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Êtes-vous sûr de vouloir supprimer {{ roleToDelete.name }} ? Cette action ne peut pas être annulée.
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button
          type="button"
          class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
          @click="confirmDeleteRole"
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
import RoleForm from '@/components/Role/RoleForm.vue';
import RolePermissions from '@/components/Role/RolePermissions.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import Toast from '@/components/shared/Toast.vue';

export default {
  name: 'RoleList',
  components: {
    RoleForm,
    RolePermissions,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
 
  data() {
    return {
      showCreateRoleModal: false,
      showEditRoleModal: false,
      showManagePermissionsModal: false,
      showDeleteConfirmation: false,
      selectedRole: null,
      roleToDelete: null,
      isLoading: true,
      error: null,
      expandedRows: [],
      showToast: false,
      toastMessage: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      sortKey: 'updated_at',
      sortOrder: 'desc',
    };
  },
  computed: {
    ...mapGetters('role', ['roles']),
    filteredRoles() {
      return this.roles
        .filter(role =>
          role.name.toLowerCase().includes(this.searchQuery.toLowerCase())
        )
        .sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
    },

    paginatedRoles() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredRoles.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredRoles.length / this.itemsPerPage);
    },
    displayedPages() {
      const range = 2;
      const pages = [];
      for (let i = Math.max(1, this.currentPage - range); i <= Math.min(this.totalPages, this.currentPage + range); i++) {
        pages.push(i);
      }
      return pages;
    },
  },
  created() {
    this.loadRoles();
  },
  methods: {
    ...mapActions('role', ['fetchRoles', 'createRole', 'deleteRole']),
    async loadRoles() {
      this.isLoading = true;
      this.error = null;
      try {
        await this.fetchRoles();
      } catch (error) {
        console.error('Error fetching roles:', error);
        this.error = 'Failed to load roles. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    openCreateRoleModal() {
      this.showCreateRoleModal = true;
    },
    closeCreateRoleModal() {
      this.showCreateRoleModal = false;
    },
    openEditRoleModal(role) {
      this.selectedRole = { ...role };
      this.showEditRoleModal = true;
    },
    closeEditRoleModal() {
      this.showEditRoleModal = false;
      this.selectedRole = null;
    },
    updateRole(updatedRole) {
      this.$store
        .dispatch('role/updateRole', updatedRole)
        .then(() => {
          this.closeEditRoleModal();
          this.loadRoles();
        })
        .catch((error) => {
          console.error('Error updating role:', error);
        });
    },
    deleteRole(role) {
      this.roleToDelete = role;
      this.showDeleteConfirmation = true;
    },
    confirmDeleteRole() {
      this.$store
        .dispatch('role/deleteRole', this.roleToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.roleToDelete = null;
          this.showSuccessToast('Role deleted successfully!');
          this.loadRoles();
        })
        .catch((error) => {
          console.error('Error deleting role:', error);
          this.showDeleteConfirmation = false;
          this.roleToDelete = null;
        });
    },
    openManagePermissionsModal(role) {
      this.selectedRole = { ...role };
      this.showManagePermissionsModal = true;
    },
    closeManagePermissionsModal() {
      this.showManagePermissionsModal = false;
      this.selectedRole = null;
    },
    toggleExpandRow(roleId) {
      const index = this.expandedRows.indexOf(roleId);
      if (index > -1) {
        this.expandedRows.splice(index, 1);
      } else {
        this.expandedRows.push(roleId);
      }
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
    onRoleCreated(createdRole) {
      this.closeCreateRoleModal();
      this.loadRoles();
      this.showSuccessToast('Role created successfully!');
    },
    onRoleUpdated(updatedRole) {
      this.closeEditRoleModal();
      this.loadRoles();
      this.showSuccessToast('Role updated successfully!');
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
    goToPage(page) {
      this.currentPage = page;
    },
    sortBy(key) {
      if (this.sortKey === key) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortKey = key;
        this.sortOrder = 'asc';
      }
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString(undefined, options);
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
