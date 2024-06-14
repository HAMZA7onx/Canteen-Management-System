<template>
    <div class="container mx-auto px-4">
      <h2 class="text-2xl font-bold mb-4">{{ isEditMode ? 'Edit Badge' : 'Create Badge' }}</h2>
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="relative">
          <label for="user" class="block text-gray-700 font-medium mb-1">User</label>
          <div
            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150"
          >
            <select
              id="user"
              v-model="badge.user_id"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            >
              <option value="">Select User</option>
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg
                class="h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </div>
          </div>
        </div>
        <div class="relative">
          <label for="rfid" class="block text-gray-700 font-medium mb-1">RFID</label>
          <div
            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150"
          >
            <input
              type="text"
              id="rfid"
              v-model="badge.rfid"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg
                class="h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>
        </div>
        <div class="relative">
          <label for="status" class="block text-gray-700 font-medium mb-1">Status</label>
          <div
            class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150"
          >
            <select
              id="status"
              v-model="badge.status"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg
                class="h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </div>
          </div>
        </div>
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            {{ isEditMode ? 'Update Badge' : 'Create Badge' }}
          </button>
        </div>
      </form>
    </div>
  </template>
  
  
  <script>
  import { mapActions, mapState } from 'vuex'
  
  export default {
    props: {
      badge: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        isEditMode: false
      }
    },
    computed: {
      ...mapState('badge', ['users'])
    },
    created() {
      this.isEditMode = !!this.badge.id
      this.fetchUsers()
    },
    methods: {
      ...mapActions('badge', ['createBadge', 'updateBadge', 'fetchUsers']),
      submitForm() {
        if (this.isEditMode) {
          this.updateBadge(this.badge)
            .then(() => {
              this.$emit('update')
              this.$emit('close')
            })
            .catch(error => {
              console.error('Error updating badge:', error)
            })
        } else {
          this.createBadge(this.badge)
            .then(() => {
              this.$emit('create')
              this.$emit('close')
            })
            .catch(error => {
              console.error('Error creating badge:', error)
            })
        }
      }
    }
  }
  </script>
  