<template>
  <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label for="category" class="block text-gray-700 font-bold mb-2"
          >User Category Name</label
        >
        <input
          type="text"
          id="category"
          v-model="userCategory.category"
          required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      <div>
        <label for="meal_discount" class="block text-gray-700 font-bold mb-2"
          >Meal Discount (%)</label
        >
        <input
          type="number"
          id="meal_discount"
          v-model.number="userCategory.meal_discount"
          required
          min="0"
          max="100"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
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
import { mapActions } from 'vuex';

export default {
  name: 'UserCategoryForm',
  props: {
    userCategory: {
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
    this.isEditMode = !!this.userCategory.id;
  },
  methods: {
    ...mapActions('userCategory', ['createUserCategory', 'updateUserCategory']),
    submitForm() {
      if (this.isEditMode) {
        this.updateUserCategory(this.userCategory)
          .then(() => {
            this.$emit('update:userCategory', null);
          })
          .catch((error) => {
            console.error('Error updating user category:', error);
          });
      } else {
        this.createUserCategory(this.userCategory)
          .then(() => {
            this.$emit('update:userCategory', null);
          })
          .catch((error) => {
            console.error('Error creating user category:', error);
          });
      }
    },
  },
};
</script>
