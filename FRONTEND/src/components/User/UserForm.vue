<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div class="relative group">
      <input
        type="text"
        id="name"
        v-model="user.name"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer pl-4 pr-10"
        placeholder=" "
      />
      <label for="name" class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400">
        Nom
      </label>
      <div class="absolute right-3 top-3 text-gray-400 dark:text-gray-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
    </div>

    <div class="relative group">
      <input
        type="email"
        id="email"
        v-model="user.email"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer pl-4 pr-10"
        placeholder=" "
      />
      <label for="email" class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400">
        Email
      </label>
      <div class="absolute right-3 top-3 text-gray-400 dark:text-gray-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
        </svg>
      </div>
    </div>

    <div class="relative group">
      <input
        type="text"
        id="phone_number"
        v-model="user.phone_number"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer pl-4 pr-10"
        placeholder=" "
      />
      <label for="phone_number" class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400">
        Numéro de téléphone
      </label>
      <div class="absolute right-3 top-3 text-gray-400 dark:text-gray-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
      </div>
    </div>

    <div class="relative group">
      <select
        id="gender"
        v-model="user.gender"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 appearance-none"
      >
        <option value="">Sélectionnez le genre</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>
      <label for="gender" class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-focus:text-purple-600 dark:peer-focus:text-purple-400">
        Genre
      </label>
      <div class="absolute right-3 top-3 text-gray-400 dark:text-gray-500 pointer-events-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>

    <div class="relative group">
      <select
        id="category_id"
        v-model="user.category_id"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 appearance-none"
      >
        <option value="">Choisir une catégorie</option>
        <option v-for="category in userCategories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>
      <label for="category_id" class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-focus:text-purple-600 dark:peer-focus:text-purple-400">
        Catégorie de collaborateur
      </label>
      <div class="absolute right-3 top-3 text-gray-400 dark:text-gray-500 pointer-events-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>

    <div class="flex justify-end">
      <button
        type="submit"
        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800"
      >
        {{ isEditMode ? 'Modifier' : 'Créer' }}
      </button>
    </div>
  </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
name: 'UserForm',
props: {
  user: {
    type: Object,
    required: true,
  },
},
data() {
  return {
    isEditMode: false,
  };
},
computed: {
  ...mapGetters('userCategory', ['userCategories']),
},
created() {
  this.isEditMode = !!this.user.id;
  this.fetchUserCategories();
},
methods: {
  ...mapActions('user', ['createUser', 'updateUser']),
  ...mapActions('userCategory', ['fetchUserCategories']),
  submitForm() {
    const formData = {
      id: this.user.id,
      category_id: this.user.category_id,
      name: this.user.name,
      email: this.user.email,
      phone_number: this.user.phone_number,
      gender: this.user.gender,
    };

    console.log('Form Data:', formData);

    if (this.isEditMode) {
      this.$emit('update:user', formData);
    } else {
      this.$emit('create:user', formData);
    }
  }
},
};
</script>