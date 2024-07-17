<template>
  <div class="">
    <div class="">
      <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">Assign RFID to User</h2>

      <!-- Search Section -->
      <div class="mb-6">
        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search User:</label>
        <div class="relative">
          <input
            id="search"
            v-model="searchQuery"
            type="text"
            class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-300"
            placeholder="Search by name or email"
            @input="debouncedSearch"
            @keydown.down.prevent="navigateResults('down')"
            @keydown.up.prevent="navigateResults('up')"
            @keydown.enter.prevent="selectHighlightedUser"
          />
          <div v-if="isLoading" class="absolute right-3 top-2">
            <span class="loading-spinner"></span>
          </div>
        </div>
        <div v-if="filteredUsers.length > 0" class="mt-2 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm max-h-60 overflow-y-auto">
          <div
            v-for="(user, index) in filteredUsers"
            :key="user.id"
            :class="['px-4 py-2 cursor-pointer hover:bg-purple-50 dark:hover:bg-purple-900 transition-colors duration-150', { 'bg-purple-100 dark:bg-purple-800': index === highlightedIndex }]"
            @click="selectUser(user)"
            @mouseover="highlightedIndex = index"
          >
            <div class="font-medium text-gray-800 dark:text-gray-200">{{ user.name }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</div>
          </div>
        </div>
        <div v-else-if="searchQuery && !isLoading" class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
          No users found
        </div>
      </div>

      <!-- Regular Select Dropdown -->
      <div class="mb-6">
        <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Or Select User:</label>
        <select
          id="user"
          v-model="selectedUser"
          class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-300"
        >
          <option value="">Select a user</option>
          <option v-for="user in eligibleUsers" :key="user.id" :value="user">
            {{ user.name }} ({{ user.email }})
          </option>
        </select>
      </div>

      <!-- Selected User Display -->
      <div v-if="selectedUser" class="mb-6 p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
        <h3 class="font-medium text-purple-800 dark:text-purple-200 mb-2">Selected User:</h3>
        <div class="text-purple-700 dark:text-purple-300">{{ selectedUser.name }}</div>
        <div class="text-sm text-purple-600 dark:text-purple-400">{{ selectedUser.email }}</div>
      </div>

      <!-- Assign Button -->
      <button
        type="button"
        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
        @click="assignRfidToUser"
        :disabled="!selectedUser"
      >
        Assign RFID
      </button>
    </div>
  </div>
</template>


<script>
import { mapGetters } from 'vuex';
import debounce from 'lodash/debounce';

export default {
  props: {
    badge: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      selectedUser: null,
      searchQuery: '',
      isLoading: false,
      highlightedIndex: -1,
    };
  },
  computed: {
    ...mapGetters('badge', ['eligibleUsers']),
    filteredUsers() {
      if (this.searchQuery.trim() === '') {
        return [];
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
    this.debouncedSearch = debounce(this.performSearch, 300);
  },
  methods: {
    performSearch() {
      this.isLoading = true;
      // Simulate API call delay
      setTimeout(() => {
        this.isLoading = false;
        this.highlightedIndex = -1;
      }, 300);
    },
    selectUser(user) {
      this.selectedUser = user;
      this.searchQuery = '';
    },
    navigateResults(direction) {
      if (direction === 'down') {
        this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filteredUsers.length - 1);
      } else {
        this.highlightedIndex = Math.max(this.highlightedIndex - 1, -1);
      }
    },
    selectHighlightedUser() {
      if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredUsers.length) {
        this.selectUser(this.filteredUsers[this.highlightedIndex]);
      }
    },
    assignRfidToUser() {
      if (!this.selectedUser) return;
      
      this.$store.dispatch('badge/assignRfidToUser', {
        badgeId: this.badge.id,
        userId: this.selectedUser.id,
      })
        .then(response => {
          this.$emit('update-success', response);
        })
        .catch(error => {
          console.error('Error assigning RFID to user:', error);
          // Handle the error here (e.g., display an error message)
        });
    },
  },
};
</script>

<style scoped>
.loading-spinner {
  /* Add your loading spinner styles here */
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 2px solid #ccc;
  border-top-color: #333;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>