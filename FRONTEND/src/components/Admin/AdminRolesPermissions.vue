<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6">Manage Roles and Permissions</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
      <div>
        <h2 class="text-lg font-bold mb-4">Permissions</h2>
        <div class="mb-4">
          <ul class="space-y-2">
            <li
              v-for="permission in assignedPermissions"
              :key="permission.id"
              class="flex justify-between items-center bg-gray-100 rounded-md px-4 py-2"
            >
              <span>{{ permission.name }}</span>
              <button
                @click="removePermission(permission)"
                class="text-red-500 hover:text-red-700 focus:outline-none"
              >
                Remove
              </button>
            </li>
          </ul>
        </div>
        <div class="flex items-center">
          <select
            v-model="selectedPermission"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
          >
            <option value="">Select a permission</option>
            <option v-for="permission in availablePermissions.permissions" :key="permission.id" :value="permission.id">{{ permission.name }}</option>
          </select>
          <button
            @click="assignPermission"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          >
            Assign Permission
          </button>
        </div>
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
      selectedPermission: '',
      fetchedAssignedRoles: [],
      fetchedAssignedPermissions: [],
    };
  },
  computed: {
    ...mapGetters('admin', ['assignedRoles', 'assignedPermissions', 'availableRoles', 'availablePermissions']),
  },
  created() {
    this.fetchAdminRolesPermissions();
    this.fetchAvailableRolesPermissions();
  },
  mounted() {
    // console.log('Available Permissions:', this.availablePermissions);
    console.log('Assigned Roles:', this.fetchedAssignedRoles);
  },
  methods: {
    ...mapActions('admin', [
      'fetchAdminRoles',
      'fetchAdminPermissions',
      'fetchAvailableRoles',
      'fetchAvailablePermissions',
      'assignRole',
      'removeRole',
      'assignPermission',
      'removePermission',
    ]),
    fetchAdminRolesPermissions() {
      this.fetchAdminRoles(this.admin.id)
        .then((response) => {
          this.fetchedAssignedRoles = response;
        })
        .catch((error) => {
          console.error('Error fetching admin roles:', error);
        });

      this.fetchAdminPermissions(this.admin.id)
        .then((response) => {
          this.fetchedAssignedPermissions = response.data;
        })
        .catch((error) => {
          console.error('Error fetching admin permissions:', error);
        });
    },
    fetchAvailableRolesPermissions() {
      this.fetchAvailableRoles();
      this.fetchAvailablePermissions();
    },
    assignRole() {
      if (this.selectedRole) {
        this.$store.dispatch('admin/assignRole', { adminId: this.admin.id, roleId: this.selectedRole })
          .then(() => {
            this.selectedRole = '';
            this.fetchAdminRolesPermissions(); // Fetch updated assigned roles and permissions
          })
          .catch((error) => {
            console.error('Error assigning role:', error);
          });
      }
    },
    removeRole(role) {
      this.$store.dispatch('admin/removeRole', { adminId: this.admin.id, roleId: role.id })
        .then(() => {
          this.fetchAdminRolesPermissions(); // Fetch updated assigned roles and permissions
        })
        .catch((error) => {
          console.error('Error removing role:', error);
        });
    },
    assignPermission() {
      if (this.selectedPermission) {
        this.$store.dispatch('admin/assignPermission', { adminId: this.admin.id, permissionId: this.selectedPermission })
          .then(() => {
            this.selectedPermission = '';
          })
          .catch((error) => {
            console.error('Error assigning permission:', error);
          });
      }
    },
    removePermission(permission) {
      this.$store.dispatch('admin/removePermission', { adminId: this.admin.id, permissionId: permission.id })
        .catch((error) => {
          console.error('Error removing permission:', error);
        });
    },
  },
};
</script>
