<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6">Manage Roles</h1>

    <div>
      <h2 class="text-lg font-bold mb-4">Roles</h2>

      <div class="mb-4">
        <ul class="space-y-2">
          <li
            v-for="role in fetchedAssignedRoles"
            :key="role.id"
            class="flex justify-between items-center bg-gray-100 rounded-md px-4 py-2"
          >
            <span>{{ role.name }}</span>
            <button
              @click="removeRole(role)"
              class="text-red-500 hover:text-red-700 focus:outline-none"
            >
              Remove
            </button>
          </li>
        </ul>
      </div>

      <div class="flex items-center">
        <select
          v-model="selectedRole"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
        >
          <option value="">Select a role</option>
          <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
        <button
          @click="assignRole"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
        this.$store.dispatch('admin/assignRole', { adminId: this.admin.id, roleId: this.selectedRole })
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
      this.$store.dispatch('admin/removeRole', { adminId: this.admin.id, roleId: role.id })
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
