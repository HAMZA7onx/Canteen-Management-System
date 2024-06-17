<template>
    <div class="">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="relative">
          <label for="menu_name" class="block text-gray-700 font-medium mb-1">Menu Name</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <input
              type="text"
              id="menu_name"
              v-model="mealMenu.menu_name"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="relative">
          <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <textarea
              id="description"
              v-model="mealMenu.description"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            ></textarea>
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="relative">
          <label for="price" class="block text-gray-
  700 font-medium mb-1">Price</label>
        <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
          <input
            type="number"
            id="price"
            v-model="mealMenu.price"
            required
            class="w-full px-4 py-2 rounded-lg focus:outline-none"
          />
          <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
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
export default {
  name: 'MealMenuForm',
  props: {
    mealMenu: {
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
    this.isEditMode = !!this.mealMenu.id;
  },
  methods: {
    submitForm() {
      const formData = {
        id: this.mealMenu.id,
        menu_name: this.mealMenu.menu_name,
        description: this.mealMenu.description,
        price: this.mealMenu.price,
      };

      if (this.isEditMode) {
        this.$emit('update:mealMenu', formData);
      } else {
        this.$emit('update:mealMenu', formData);
      }
    },
  },
};
</script>
