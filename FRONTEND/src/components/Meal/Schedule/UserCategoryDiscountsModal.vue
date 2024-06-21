<template>
  <div>
    <div v-if="userCategories.length">
      <div v-for="category in userCategories" :key="category.id" class="mb-4">
        <label :for="`category-${category.id}`" class="block font-medium mb-2">{{ category.name }}</label>
        <input
          :id="`category-${category.id}`"
          v-model.number="localCategoryDiscounts[category.id]"
          type="number"
          min="0"
          max="100"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        />
      </div>
    </div>
    <div class="mt-4 flex justify-end">
      <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" @click="updateCategoryDiscounts">
        Save
      </button>
      <button class="bg-gray-500 text-white px-4 py-2 rounded-md" @click="closeModal">
        Cancel
      </button>
    </div>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'UserCategoryDiscountsModal',
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    mealSchedule: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localCategoryDiscounts: {},
    };
  },
  computed: {
    ...mapGetters('userCategory', ['userCategories']),
  },
  created() {
    this.fetchUserCategories();
  },
  mounted() {
    this.fetchCategoryDiscounts(this.mealSchedule.id);
  },
  methods: {
    ...mapActions('userCategory', ['fetchUserCategories']),
    ...mapActions('mealSchedule', ['fetchCategoryDiscounts', 'updateCategoryDiscounts']),
    closeModal() {
      this.$emit('close');
    },
    updateCategoryDiscounts() {
      const mealScheduleId = this.mealSchedule.id;
      const discounts = Object.entries(this.localCategoryDiscounts).map(([categoryId, discount]) => ({
        category_id: categoryId.toString(),
        meal_discount: discount.toString(),
      }));

      const requestPayload = { discounts };

      this.updateCategoryDiscounts({ mealScheduleId, requestPayload })
        .then((response) => {
          this.localCategoryDiscounts = { ...response.data }; // Create a new object
          this.closeModal();
        })
        .catch((error) => {
          console.error('Error updating category discounts:', error);
        });
    },
  },
};
</script>
