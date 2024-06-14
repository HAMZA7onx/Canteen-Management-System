<template>
  <div class="">
    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="relative">
        <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="text"
            id="name"
            v-model="user.name"
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
            v-model="user.email"
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
        <label for="phone_number" class="block text-gray-700 font-medium mb-1">Phone Number</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="text"
            id="phone_number"
            v-model="user.phone_number"
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          />
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="relative">
        <label for="gender" class="block text-gray-700 font-medium mb-1">Gender</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <select
            id="gender"
            v-model="user.gender"
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          >
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <label for="category_id" class="block text-gray-700 font-medium mb-1">User Category</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <select
            id="category_id"
            v-model="user.category_id"
            required
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          >
            <option value="">Select Category</option>
            <option v-for="category in userCategories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
      </div>
    </form>
  </div>
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

      console.log('Form Data:', formData); // Log the formData object

      if (this.isEditMode) {
        this.updateUser(formData)
          .then(() => {
            // this.$emit('update:user', null);
          })
          .catch((error) => {
            console.error('Error updating user:', error);
          });
      } else {
        this.createUser(formData)
          .then(() => {
            this.$emit('update:user', null);
          })
          .catch((error) => {
            console.error('Error creating user:', error);
          });
      }
    }

  },
};
</script>
