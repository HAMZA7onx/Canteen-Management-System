<template>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
          <input
            type="text"
            id="name"
            v-model="reactiveUser.name"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div>
          <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input
            type="email"
            id="email"
            v-model="reactiveUser.email"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div>
          <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
          <select
            id="category"
            v-model="reactiveUser.category_id"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          >
            <option value="">Select Category</option>
            <option v-for="category in userCategories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div>
          <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number</label>
          <input
            type="text"
            id="phone_number"
            v-model="reactiveUser.phone_number"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div>
          <label for="gender" class="block text-gray-700 font-bold mb-2">Gender</label>
          <select
            id="gender"
            v-model="reactiveUser.gender"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          >
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
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
        reactiveUser: null,
      };
    },
    computed: {
      ...mapGetters('userCategory', ['userCategories']),
    },
    created() {
        console.log('from userForm.vue: ',this.user.name);
      this.isEditMode = !!this.user.id;
      this.reactiveUser = { ...this.user };
      this.fetchUserCategories();
    },
    methods: {
      ...mapActions('user', ['createUser', 'updateUser']),
      ...mapActions('userCategory', ['fetchUserCategories']),
      submitForm() {
        if (this.isEditMode) {
          this.updateUser(this.reactiveUser)
            .then(() => {
              this.$emit('update-user', null);
            })
            .catch((error) => {
              console.error('Error updating user:', error);
            });
        } else {
          this.createUser(this.reactiveUser)
            .then(() => {
              this.$emit('create-user', null);
            })
            .catch((error) => {
              console.error('Error creating user:', error);
            });
        }
      },
    },
  };
  </script>
  