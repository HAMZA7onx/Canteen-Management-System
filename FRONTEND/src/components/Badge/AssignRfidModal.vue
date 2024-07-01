<template>
    <div class="bg-white rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Assign RFID to User</h2>
      <div class="mb-4">
        <label for="search" class="block font-bold mb-2">Search User:</label>
        <input
          id="search"
          v-model="searchQuery"
          type="text"
          class="border border-gray-300 rounded-md px-3 py-2 w-full"
          placeholder="Search by name or email"
        />
      </div>
      <div class="mb-4">
        <label for="user" class="block font-bold mb-2">Assign to User:</label>
        <select
          id="user"
          v-model="selectedUser"
          class="border border-gray-300 rounded-md px-3 py-2 w-full"
        >
          <option value="">Select a user</option>
          <option
            v-for="user in filteredUsers"
            :key="user.id"
            :value="user.id"
          >
            {{ user.name }} ({{ user.email }})
          </option>
        </select>
      </div>
      <button
        type="button"
        class="bg-blue-500 text-white px-4 py-2 rounded-md"
        @click="assignRfidToUser(selectedUser)"
      >
        Assign RFID
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      badge: {
        type: Object,
        required: true,
      },
      eligibleUsers: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        selectedUser: null,
        searchQuery: '',
      };
    },
    computed: {
        filteredUsers() {
        if (this.searchQuery.trim() === '') {
          return this.eligibleUsers;
        }
        const query = this.searchQuery.trim().toLowerCase();
        return this.eligibleUsers.filter(user => {
          const name = user.name.toLowerCase();
          const email = user.email.toLowerCase();
          return name.includes(query) || email.includes(query);
        });
      },
    },
    created() {
    console.log('Eligible Users:', this.eligibleUsers);
  },
    methods: {
      assignRfidToUser(userId) {
        this.$store.dispatch('badge/assignRfidToUser', {
          badgeId: this.badge.id,
          userId: userId,
        })
          .then(response => {
            this.$emit('update-success', response.data);
          })
          .catch(error => {
            console.error('Error assigning RFID to user:', error);
            this.$emit('update-error', error);
          });
      },
    },
  };
  </script>
  