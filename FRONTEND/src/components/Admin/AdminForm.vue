<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6">{{ isEditMode ? 'Edit Admin' : 'Create Admin' }}</h1>
    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
        <input
          type="text"
          id="name"
          v-model="admin.name"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div>
        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
        <input
          type="email"
          id="email"
          v-model="admin.email"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div>
        <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
        <input
          type="password"
          id="password"
          v-model="admin.password"
          :required="!isEditMode"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
