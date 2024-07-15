<template>
  <div class="bg-gradient-to-br from-purple-600 to-indigo-800 p-1 rounded-3xl shadow-2xl">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 backdrop-blur-lg bg-opacity-90 dark:bg-opacity-90">
      <h2 class="text-4xl font-extrabold mb-8 text-center bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent animate-pulse">
        Manage Roles
      </h2>

      <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Assigned Roles</h3>
        <ul class="space-y-3">
          <li
            v-for="role in fetchedAssignedRoles"
            :key="role.id"
            class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 rounded-xl px-6 py-4 transition-all duration-300 hover:shadow-lg hover:scale-105"
          >
            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ role.name }}</span>
            <button
              @click="removeRole(role)"
              class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none transition-all duration-300 transform hover:rotate-12"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </li>
        </ul>
      </div>

      <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
        <select
          v-model="selectedRole"
          class="w-full sm:w-2/3 px-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-300 text-gray-800 dark:text-gray-200 appearance-none"
        >
          <option value="">Select a role</option>
          <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
        <button
          @click="assignRole"
          class="w-full sm:w-1/3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          Assign Role
        </button>
      </div>
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
    };
  },
  computed: {
    ...mapGetters('admin', ['assignedRoles', 'availableRoles']),
  },
  created() {
    this.fetchAdminRolesPermissions();
    this.fetchAvailableRoles();
  },
  mounted() {
    console.log('Assigned Roles:', this.fetchedAssignedRoles);
  },
  methods: {
    ...mapActions('admin', ['fetchAdminRoles', 'fetchAvailableRoles', 'assignRole', 'removeRole']),
    fetchAdminRolesPermissions() {
      this.fetchAdminRoles(this.admin.id)
        .then((response) => {
          this.fetchedAssignedRoles = response;
        })
        .catch((error) => {
          console.error('Error fetching admin roles:', error);
        });
    },
    assignRole() {
      if (this.selectedRole) {
        this.$store
          .dispatch('admin/assignRole', { adminId: this.admin.id, roleId: this.selectedRole })
          .then(() => {
            this.selectedRole = '';
            this.fetchAdminRolesPermissions(); // Fetch updated assigned roles
          })
          .catch((error) => {
            console.error('Error assigning role:', error);
          });
      }
    },
    removeRole(role) {
      this.$store
        .dispatch('admin/removeRole', { adminId: this.admin.id, roleId: role.id })
        .then(() => {
          this.fetchAdminRolesPermissions(); // Fetch updated assigned roles
        })
        .catch((error) => {
          console.error('Error removing role:', error);
        });
    },
  },
};
</script>
