<template>
  <div>
    <h2 class="text-xl font-bold mb-4">User Category Discounts</h2>
    <div v-if="mealSchedule && mealSchedule.category_discounts">
      <div v-for="categoryDiscount in mealSchedule.category_discounts" :key="categoryDiscount.id" class="mb-4">
        <div class="flex items-center">
          <span class="mr-4">{{ getCategoryName(categoryDiscount.category_id) }}:</span>
          <input
            v-model.number="categoryDiscount.meal_discount"
            type="number"
            min="0"
            max="100"
            class="border border-gray-300 rounded-md px-2 py-1"
            @input="updateCategoryDiscount(categoryDiscount)"
          />
        </div>
      </div>
    </div>
    <div v-else-if="mealSchedule">
      No user categories found.
    </div>
    <div v-else>
      Loading...
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
  computed: {
    ...mapGetters('userCategory', ['userCategories']),
  },
  created() {
    this.fetchUserCategories();
  },
  methods: {
    ...mapActions('userCategory', ['fetchUserCategories']),
    ...mapActions('mealSchedule', ['updateCategoryDiscount']),
    getCategoryName(categoryId) {
      const category = this.userCategories.find((c) => c.id === categoryId);
      return category ? category.name : '';
    },
    updateCategoryDiscount(categoryDiscount) {
      this.updateCategoryDiscount({
        mealScheduleId: this.mealSchedule.id,
        categoryDiscount,
      });
    },
  },
};
</script>
