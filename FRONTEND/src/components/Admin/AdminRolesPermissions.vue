<template>
    <div>
      <h1>Manage Roles and Permissions</h1>
      <div>
        <h2>Roles</h2>
        <ul>
          <li v-for="role in assignedRoles" :key="role.id">
            {{ role.name }}
            <button @click="removeRole(role)">Remove</button>
          </li>
        </ul>
        <select v-model="selectedRole">
          <option value="">Select a role</option>
          <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
        <button @click="assignRole">Assign Role</button>
      </div>
      <div>
        <h2>Permissions</h2>
        <ul>
          <li v-for="permission in assignedPermissions" :key="permission.id">
            {{ permission.name }}
            <button @click="removePermission(permission)">Remove</button>
          </li>
        </ul>
        <select v-model="selectedPermission">
          <option value="">Select a permission</option>
          <option v-for="permission in availablePermissions" :key="permission.id" :value="permission.id">{{ permission.name }}</option>
        </select>
        <button @click="assignPermission">Assign Permission</button>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  
  export default {
    name: 'AdminRolesPermissions',
    data() {
      return {
        adminId: null,
        selectedRole: '',
        selectedPermission: '',
      };
    },
    computed: {
      ...mapGetters('admin', ['assignedRoles', 'assignedPermissions', 'availableRoles', 'availablePermissions']),
    },
    mounted() {
      this.adminId = this.$route.params.id;
      this.fetchAdminRolesPermissions();
      this.fetchAvailableRolesPermissions();
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
        this.fetchAdminRoles(this.adminId);
        this.fetchAdminPermissions(this.adminId);
      },
      fetchAvailableRolesPermissions() {
        this.fetchAvailableRoles();
        this.fetchAvailablePermissions();
      },
    },
  };
  </script>
  