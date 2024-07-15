<template>
  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 transition-colors duration-300">
    <div class="mb-6">
      <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Assigned Permissions</h3>
      <ul class="space-y-2">
        <li v-for="permission in fetchedAssignedPermissions" :key="permission.id" class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
          <span class="text-gray-800 dark:text-gray-200">{{ permission.name }}</span>
          <button
            @click="removePermission(permission)"
            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
          >
            Remove
          </button>
        </li>
      </ul>
    </div>
    <div>
      <label for="permissions" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Available Permissions</label>
      <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
        <select
          v-model="selectedPermission"
          id="permissions"
          class="flex-grow bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors duration-300"
        >
          <option value="">Select a permission</option>
          <option v-for="permission in availablePermissions" :key="permission.id" :value="permission.id">
            {{ permission.name }}
          </option>
        </select>
        <button
          @click="assignPermission"
          class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-6 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
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
