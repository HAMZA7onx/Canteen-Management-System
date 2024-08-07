<template>
  <form @submit.prevent="submitForm" class="space-y-6">
  
    
    <div class="relative group">
      <input
        type="text"
        id="name"
        v-model="admin.name"
        required
        :class="['w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 transition-all duration-300 outline-none peer', 
                 errors && errors.name ? 'border-red-500' : 'border-transparent focus:border-indigo-500 dark:focus:border-indigo-400']"
        placeholder=" "
      />
      <label for="name" class="absolute left-4 top-2 text-gray-500 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Nom
      </label>
      <div class="absolute right-3 top-2 transition-all duration-300 group-hover:scale-110">
        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <p v-if="errors && errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
    </div>

    <div class="relative group">
      <input
        type="email"
        id="email"
        v-model="admin.email"
        required
        :class="['w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 transition-all duration-300 outline-none peer', 
                 errors && errors.email ? 'border-red-500' : 'border-transparent focus:border-indigo-500 dark:focus:border-indigo-400']"
        placeholder=" "
      />
      <label for="email" class="absolute left-4 top-2 text-gray-500 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Email
      </label>
      <div class="absolute right-3 top-2 transition-all duration-300 group-hover:scale-110">
        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
        </svg>
      </div>
      <p v-if="errors && errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
    </div>

    <div class="relative group">
      <input
        type="password"
        id="password"
        v-model="admin.password"
        :required="!isEditMode"
        :class="['w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 transition-all duration-300 outline-none peer', 
                 errors && errors.password ? 'border-red-500' : 'border-transparent focus:border-indigo-500 dark:focus:border-indigo-400']"
        placeholder=" "
      />
      <label for="password" class="absolute left-4 top-2 text-gray-500 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
        Password
      </label>
      <div class="absolute right-3 top-2 transition-all duration-300 group-hover:scale-110">
        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
      </div>
      <p v-if="errors && errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
    </div>

    <div class="flex justify-end">
      <button
        type="submit"
        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
      >
        {{ isEditMode ? 'Modifier' : 'Créer' }}
      </button>
    </div>
  </form>
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
      errors: null,
    };
  },
  created() {
    this.isEditMode = !!this.admin.id;
  },
  methods: {
    ...mapActions('admin', ['createAdmin', 'updateAdmin']),
    submitForm() {
      this.errors = null;
      const action = this.isEditMode ? this.updateAdmin : this.createAdmin;
      
      action(this.admin)
        .then((response) => {
          this.$emit(this.isEditMode ? 'admin-updated' : 'admin-created', response);
        })
        .catch((error) => {
          if (error.errors) {
            this.errors = error.errors;
          } else {
            console.error('Unexpected error:', error);
            this.errors = { general: ['An unexpected error occurred.'] };
          }
        });
    }
  },
};
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

.popup-enter-active {
  animation: fadeIn 0.3s ease-out;
}

.popup-leave-active {
  animation: fadeIn 0.3s ease-in reverse;
}
</style>
