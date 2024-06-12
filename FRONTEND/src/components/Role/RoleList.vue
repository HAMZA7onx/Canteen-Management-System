<template>
  <div class="container mx-auto px-4">
    <div class="mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateRoleModal"
      >
        Create Role
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
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="role in roles" :key="role.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ role.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditRoleModal(role)"
              >
                Edit
              </button>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="deleteRole(role)"
              >
                Delete
              </button>
              <button
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                @click="openManagePermissionsModal(role)"
              >
                Manage Permissions
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Role Modal -->
    <Overlay v-if="showCreateRoleModal">
      <Modal :show="showCreateRoleModal" @close="closeCreateRoleModal" title="Create Role">
        <RoleForm :role="{}" @update:role="createRole" />
      </Modal>
    </Overlay>

    <!-- Edit Role Modal -->
    <Overlay v-if="showEditRoleModal">
      <Modal :show="showEditRoleModal" @close="closeEditRoleModal" title="Edit Role">
        <RoleForm :role="selectedRole" @update:role="updateRole" />
      </Modal>
    </Overlay>

    <!-- Manage Permissions Modal -->
    <Overlay v-if="showManagePermissionsModal">
      <Modal :show="showManagePermissionsModal" @close="closeManagePermissionsModal" title="Manage Permissions">
        <RolePermissions :role="selectedRole" />
      </Modal>
    </Overlay>

    <!-- Delete Confirmation Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto" v-if="showDeleteConfirmation">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-headline"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-red-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                  Delete Role
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete {{ roleToDelete.name }}? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="confirmDeleteRole"
            >
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="showDeleteConfirmation = false"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  import RoleForm from '@/components/Role/RoleForm.vue';
  import RolePermissions from '@/components/Role/RolePermissions.vue';
  import Modal from '@/components/shared/Modal.vue';
  import Overlay from '@/components/shared/Overlay.vue';
  
  export default {
    name: 'RoleList',
    components: {
      RoleForm,
      RolePermissions,
      Modal,
      Overlay,
    },
    data() {
      return {
        showCreateRoleModal: false,
        showEditRoleModal: false,
        showManagePermissionsModal: false,
        showDeleteConfirmation: false,
        selectedRole: null,
        roleToDelete: null,
      };
    },
    computed: {
      ...mapGetters('role', ['roles']),
    },
    created() {
      this.fetchRoles();
    },
    methods: {
      ...mapActions('role', ['fetchRoles', 'createRole', 'deleteRole']),
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
    },
  };
  </script>
  