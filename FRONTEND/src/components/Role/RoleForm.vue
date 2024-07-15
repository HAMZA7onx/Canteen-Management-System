<template>
  <div class="bg-gradient-to-br from-purple-600 to-indigo-800 p-1 rounded-3xl shadow-2xl">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 backdrop-blur-lg bg-opacity-90 dark:bg-opacity-90">
      <h2 class="text-3xl font-extrabold mb-6 text-center bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
        {{ isEditMode ? 'Update Role' : 'Create New Role' }}
      </h2>
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="relative">
          <input
            type="text"
            id="name"
            v-model="role.name"
            required
            class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer"
            placeholder=" "
          />
          <label 
            for="name" 
            class="absolute left-4 top-3 text-gray-500 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
          >
            Role Name
          </label>
        </div>
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          {{ isEditMode ? 'Update Role' : 'Create Role' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'RoleForm',
  props: {
    role: {
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
    this.isEditMode = !!this.role.id;
  },
  methods: {
    ...mapActions('role', ['createRole', 'updateRole']),
    async submitForm() {
      try {
        if (this.isEditMode) {
          await this.updateRole(this.role);
        } else {
          await this.createRole(this.role);
        }
        this.$emit('update:role', null);
        this.$emit('form-submitted', this.isEditMode ? 'updated' : 'created');
      } catch (error) {
        console.error(`Error ${this.isEditMode ? 'updating' : 'creating'} role:`, error);
        this.$emit('form-error', error);
      }
    },
  },
};
</script>
