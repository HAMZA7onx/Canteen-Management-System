<template>
  <div class="">
    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="relative">
        <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="text"
            id="name"
            v-model="admin.name"
            required
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          />
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="relative">
        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="email"
            id="email"
            v-model="admin.email"
            required
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          />
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="relative">
        <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="password"
            id="password"
            v-model="admin.password"
            :required="!isEditMode"
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          />
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
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
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'AdminForm',
  props: {
    admin: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      isEditMode: false,
    };
  },
  created() {
    this.isEditMode = !!this.admin.id;
  },
  methods: {
    ...mapActions('admin', ['createAdmin', 'updateAdmin']),
    submitForm() {
      if (this.isEditMode) {
        this.updateAdmin(this.admin)
          .then(() => {
            this.$emit('update:admin', null);
          })
          .catch((error) => {
            console.error('Error updating admin:', error);
          });
      } else {
        this.createAdmin(this.admin)
          .then(() => {
            this.$emit('update:admin', null);
          })
          .catch((error) => {
            console.error('Error creating admin:', error);
          });
      }
    },
  },
};
</script>
