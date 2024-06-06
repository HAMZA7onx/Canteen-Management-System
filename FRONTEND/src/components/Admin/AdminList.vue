<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">ADMINS list:</h1>
      <table class="min-w-full divide-y divide-gray-200 overflow-x-auto">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Title
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="admin in admins" :key="admin.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img class="h-10 w-10 rounded-full" :src="admin.avatar" :alt="admin.name">
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ admin.name }}</div>
                  <div class="text-sm text-gray-500">{{ admin.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ admin.title }}</div>
              <div class="text-sm text-gray-500">{{ admin.department }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="admin.isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
              >
                {{ admin.isActive ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ admin.role }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ admin.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="text-indigo-600 hover:text-indigo-900"
                @click="editAdmin(admin)"
              >
                Edit
              </button>
              <button
                class="ml-2 text-red-600 hover:text-red-900"
                @click="deleteAdmin(admin)"
              >
                Delete
              </button>
              <button
                class="ml-2 text-green-600 hover:text-green-900"
                @click="manageRolesPermissions(admin)"
              >
                Manage Roles/Permissions
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  
  export default {
    name: 'AdminList',
    computed: {
      ...mapGetters('admin', ['admins']),
    },
    methods: {
      ...mapActions('admin', ['fetchAdmins', 'deleteAdmin']),
      editAdmin(admin) {
        this.$router.push({ name: 'AdminEdit', params: { id: admin.id } });
      },
      deleteAdmin(admin) {
        if (confirm(`Are you sure you want to delete ${admin.name}?`)) {
          this.deleteAdmin(admin.id)
            .then(() => {
              // Handle success
            })
            .catch((error) => {
              console.error('Error deleting admin:', error);
            });
        }
      },
      manageRolesPermissions(admin) {
        this.$router.push({ name: 'AdminRolesPermissions', params: { id: admin.id } });
      },
    },
  };
  </script>
  