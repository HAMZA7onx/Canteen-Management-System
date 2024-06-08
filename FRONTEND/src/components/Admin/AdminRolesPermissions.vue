<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-6">Manage Roles</h2>

    <div class="mb-6">
      <h3 class="text-lg font-medium mb-2">Assigned Roles</h3>
      <ul class="space-y-2">
        <li
          v-for="role in fetchedAssignedRoles"
          :key="role.id"
          class="flex justify-between items-center bg-gray-100 rounded-md px-4 py-2 transition duration-300 hover:bg-gray-200"
        >
          <span class="text-gray-700">{{ role.name }}</span>
          <button
            @click="removeRole(role)"
            class="text-red-500 hover:text-red-700 focus:outline-none transition duration-300"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
          </button>
        </li>
      </ul>
    </div>

    <div class="flex items-center">
      <select
        v-model="selectedRole"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2 transition duration-300"
      >
        <option value="">Select a role</option>
        <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.name }}</option>
      </select>
      <button
        @click="assignRole"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
      >
        Assign Role
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
    };
  },
  computed: {
    ...mapGetters('admin', ['assignedRoles', 'availableRoles']),
  },
  created() {
    this.fetchAdminRolesPermissions();
    this.fetchAvailableRoles();
  },
  // mounted() {
  //   console.log('Assigned Roles:', this.fetchedAssignedRoles);
  // },
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
