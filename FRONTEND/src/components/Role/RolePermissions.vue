<template>
  <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
      <h3 class="text-lg font-medium text-gray-900">Assigned Permissions</h3>
      <ul class="list-disc list-inside">
        <li v-for="permission in fetchedAssignedPermissions" :key="permission.id" class="flex justify-between items-center">
          <span>{{ permission.name }}</span>
          <button
            @click="removePermission(permission)"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          >
            Remove
          </button>
        </li>
      </ul>
    </div>
    <div>
      <label for="permissions" class="block text-gray-700 font-bold mb-2">Available Permissions</label>
      <div class="flex">
        <select
          v-model="selectedPermission"
          id="permissions"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2"
        >
          <option value="">Select a permission</option>
          <option v-for="permission in availablePermissions" :key="permission.id" :value="permission.id">
            {{ permission.name }}
          </option>
        </select>
        <button
          @click="assignPermission"
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
