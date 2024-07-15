<template>
  <div class="bg-gradient-to-br from-purple-600 to-indigo-800 p-1 rounded-3xl shadow-2xl">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 backdrop-blur-lg bg-opacity-90 dark:bg-opacity-90">
      <h2 class="text-4xl font-extrabold mb-8 text-center bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent animate-pulse">
        Manage Permissions
      </h2>

      <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Assigned Permissions</h3>
        <ul class="space-y-3">
          <li
            v-for="permission in fetchedAssignedPermissions"
            :key="permission.id"
            class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 rounded-xl px-6 py-4 transition-all duration-300 hover:shadow-lg hover:scale-105"
          >
            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ permission.name }}</span>
            <button
              @click="removePermission(permission)"
              class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transform hover:rotate-12"
            >
              Remove
            </button>
          </li>
        </ul>
      </div>

      <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
        <select
          v-model="selectedPermission"
          class="w-full sm:w-2/3 px-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-300 text-gray-800 dark:text-gray-200 appearance-none"
        >
          <option value="">Select a permission</option>
          <option v-for="permission in availablePermissions" :key="permission.id" :value="permission.id">
            {{ permission.name }}
          </option>
        </select>
        <button
          @click="assignPermission"
          class="w-full sm:w-1/3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          Assign Permission
        </button>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'RolePermissions',
  props: {
    role: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      selectedPermission: '',
      fetchedAssignedPermissions: [],
    };
  },
  computed: {
    ...mapGetters('role', ['assignedPermissions', 'availablePermissions']),
  },
  mounted() {
    this.fetchRolePermissions();
    this.fetchAvailablePermissions();
    console.log('testing');
  },
  methods: {
    ...mapActions('role', ['fetchRolePermissions', 'assignPermission', 'removePermission', 'fetchAvailablePermissions']),
    fetchRolePermissions() {
      this.$store
        .dispatch('role/fetchRolePermissions', this.role.id)
        .then((response) => {
          this.fetchedAssignedPermissions = response;
        })
        .catch((error) => {
          console.error('Error fetching role permissions:', error);
        });
    },
    fetchAvailablePermissions() {
      this.$store
        .dispatch('role/fetchAvailablePermissions')
        .then(() => {
          console.log('Available Permissions:', this.availablePermissions);
        })
        .catch((error) => {
          console.error('Error fetching available permissions:', error);
        });
    },
    assignPermission() {
      if (this.selectedPermission) {
        this.$store
          .dispatch('role/assignPermission', {
            roleId: this.role.id,
            permissionId: this.selectedPermission,
          })
          .then(() => {
            this.selectedPermission = '';
            this.fetchRolePermissions();
          })
          .catch((error) => {
            console.error('Error assigning permission:', error);
          });
      }
    },
    removePermission(permission) {
      this.$store
        .dispatch('role/removePermission', {
          roleId: this.role.id,
          permissionId: permission.id,
        })
        .then(() => {
          this.fetchRolePermissions();
        })
        .catch((error) => {
          console.error('Error removing permission:', error);
        });
    },
  },
};
</script>
