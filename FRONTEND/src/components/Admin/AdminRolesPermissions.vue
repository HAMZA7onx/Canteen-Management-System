<template>
  <div class="mb-8">
    <div class="mb-8 bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
      <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Fonctionnalités</h3>
      <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
        <li>• Afficher les rôles actuellement attribués à l'administrateur</li>
        <li>• Ajouter de nouveaux rôles à l'administrateur</li>
        <li>• Supprimer des rôles existants de l'administrateur</li>
        <li>• Mise à jour en temps réel des rôles affichés</li>
      </ul>
    </div>

    <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Rôles assignés</h3>
    <div v-if="isLoadingRoles" class="flex justify-center items-center h-32">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    <div v-else class="max-h-60 overflow-auto pr-2 mb-4">
      <transition-group name="list" tag="ul" class="space-y-2">
        <li
          v-for="role in fetchedAssignedRoles"
          :key="role.id"
          class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2 transition-all duration-300 hover:shadow-md hover:scale-102"
        >
          <span class="text-gray-800 dark:text-gray-200 text-sm">{{ role.name }}</span>
          <button
            @click="removeRole(role)"
            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none transition-all duration-300 transform hover:rotate-12"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </li>
      </transition-group>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch space-y-4 sm:space-y-0 sm:space-x-4">
      <div class="relative flex-grow">
        <select
          v-model="selectedRole"
          class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-300 text-gray-800 dark:text-gray-200 appearance-none"
        >
          <option value="">Sélectionnez un rôle</option>
          <option v-for="role in availableRolesFiltered" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
          </svg>
        </div>
      </div>
      <button
        @click="assignRole"
        :disabled="!selectedRole || isAssigning"
        class="flex-shrink-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="!isAssigning">Assign Role</span>
        <span v-else class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Assigning...
        </span>
      </button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'AdminRolesPermissions',
  props: {
    admin: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      selectedRole: '',
      fetchedAssignedRoles: [],
      isAssigning: false,
      isLoadingRoles: true,
    };
  },
  computed: {
    ...mapGetters('admin', ['assignedRoles', 'availableRoles']),
    availableRolesFiltered() {
      return this.availableRoles.filter(role =>
        role.name !== 'SUPER_ADMIN' &&
        !this.fetchedAssignedRoles.some(assigned => assigned.id === role.id)
      );
    },
  },

  created() {
    this.fetchAdminRolesPermissions();
    this.fetchAvailableRoles();
  },
  methods: {
    ...mapActions('admin', ['fetchAdminRoles', 'fetchAvailableRoles', 'assignRole', 'removeRole']),
    ...mapActions('auth', ['refreshPermissions']),
    async fetchAdminRolesPermissions() {
      this.isLoadingRoles = true;
      try {
        const response = await this.fetchAdminRoles(this.admin.id);
        this.fetchedAssignedRoles = response;
      } catch (error) {
        console.error('Error fetching admin roles:', error);
      } finally {
        this.isLoadingRoles = false;
      }
    },
    async assignRole() {
      if (this.selectedRole) {
        this.isAssigning = true;
        try {
          await this.$store.dispatch('admin/assignRole', { adminId: this.admin.id, roleId: this.selectedRole });
          this.selectedRole = '';
          await this.fetchAdminRolesPermissions();
          await this.$store.dispatch('auth/refreshPermissions');
        } catch (error) {
          console.error('Error assigning role:', error);
        } finally {
          this.isAssigning = false;
        }
      }
    },
    async removeRole(role) {
      try {
        await this.$store.dispatch('admin/removeRole', { adminId: this.admin.id, roleId: role.id });
        await this.fetchAdminRolesPermissions();
        await this.$store.dispatch('auth/refreshPermissions');
      } catch (error) {
        console.error('Error removing role:', error);
      }
    },
  },
};
</script>

<style scoped>
.list-enter-active, .list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from, .list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
