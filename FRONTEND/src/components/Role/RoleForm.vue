<template>
  <form @submit.prevent="submitForm" class="space-y-8">
    <div class="relative">
      <input
        type="text"
        id="name"
        v-model="role.name"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer"
        placeholder=" "
      />
      <label
        for="name"
        class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400"
      >
        Nom de role
      </label>
    </div>
    
    <!-- Add error message display -->
    <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
      {{ errorMessage }}
    </div>
     
    <button
      type="submit"
      class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800"
    >
      {{ isEditMode ? 'Modifier le Role' : 'Créer le Role' }}
    </button>
  </form>
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
      errorMessage: '', // Add error message state
    };
  },
  created() {
    this.isEditMode = !!this.role.id;
  },
  methods: {
    ...mapActions('role', ['createRole', 'updateRole']),
    async submitForm() {
      try {
        this.errorMessage = ''; // Clear previous error message
        if (this.isEditMode) {
          const updatedRole = await this.updateRole(this.role);
          this.$emit('role-updated', updatedRole);
        } else {
          const createdRole = await this.createRole(this.role);
          this.$emit('role-created', createdRole);
        }
      } catch (error) {
          console.error(`Error ${this.isEditMode ? 'updating' : 'creating'} role:`, error);
          if (error.response && error.response.data && error.response.data.errors && error.response.data.errors.name) {
            this.errorMessage = error.response.data.errors.name[0];
          } else {
            this.errorMessage = 'Le nom déjà existe';
          }
          this.$emit('form-error', error);
        }
    },
  },
};
</script>
