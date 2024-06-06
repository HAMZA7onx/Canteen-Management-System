<template>
    <div>
      <h2>Admin Roles</h2>
      <ul>
        <li v-for="role in adminRoles" :key="role.id">{{ role.name }}</li>
      </ul>
  
      <h2>Admin Permissions</h2>
      <ul>
        <li v-for="permission in adminPermissions" :key="permission.id">{{ permission.name }}</li>
      </ul>
    </div>
</template>
  
  <script>
  import AdminService from '@/services/admin.service';
  
  export default {
    name: 'AdminDetails',
    data() {
      return {
        adminId: 1,
        adminRoles: [],
        adminPermissions: [],
      };
    },
    created() {
      this.fetchAdminRoles();
      this.fetchAdminPermissions();
    },
    methods: {
      fetchAdminRoles() {
        AdminService.getAdminRoles(this.adminId)
          .then((response) => {
            this.adminRoles = response.data;
          })
          .catch((error) => {
            console.error('Error fetching admin roles:', error);
          });
      },
      fetchAdminPermissions() {
        AdminService.getAdminPermissions(this.adminId)
          .then((response) => {
            this.adminPermissions = response.data;
          })
          .catch((error) => {
            console.error('Error fetching admin permissions:', error);
          });
      },
    },
  };
  </script>
  