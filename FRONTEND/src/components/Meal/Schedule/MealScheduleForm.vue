<template>
    <div class="">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="relative">
          <label for="mealMenu" class="block text-gray-700 font-medium mb-1">Meal Menu</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <select
              id="mealMenu"
              v-model="mealSchedule.meal_menu_id"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            >
              <option v-for="mealMenu in mealMenus" :key="mealMenu.id" :value="mealMenu.id">
                {{ mealMenu.menu_name }}
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
  
        <div class="relative">
          <label for="mealName" class="block text-gray-700 font-medium mb-1">Meal Name</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <select
              id="mealName"
              v-model="mealSchedule.meal_name_id"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            >
              <option v-for="mealName in mealNames" :key="mealName.id" :value="mealName.id">
                {{ mealName.name }}
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
  
        <div class="relative">
          <label for="date" class="block text-gray-700 font-medium mb-1">Date</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <input
              type="date"
              id="date"
              v-model="mealSchedule.date"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="relative">
          <label for="startTime" class="block text-gray-700 font-medium mb-1">Start Time</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <input
              type="time"
              id="startTime"
              v-model="mealSchedule.start_time"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="relative">
          <label for="endTime" class="block text-gray-700 font-medium mb-1">End Time</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <input
              type="time"
              id="endTime"
              v-model="mealSchedule.end_time"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
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
    name: 'MealScheduleForm',
    props: {
      mealSchedule: {
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
      ...mapGetters('mealMenu', ['mealMenus']),
      ...mapGetters('mealName', ['mealNames']),
    },
    created() {
      this.isEditMode = !!this.mealSchedule.id;
      this.fetchMealMenus();
      this.fetchMealNames();
    },
    methods: {
      ...mapActions('mealMenu', ['fetchMealMenus']),
      ...mapActions('mealName', ['fetchMealNames']),
      submitForm() {
        const formData = {
          id: this.mealSchedule.id,
          meal_menu_id: this.mealSchedule.meal_menu_id,
          meal_name_id: this.mealSchedule.meal_name_id,
          date: this.mealSchedule.date,
          start_time: this.mealSchedule.start_time,
          end_time: this.mealSchedule.end_time,
        };

        if (this.isEditMode) {
          this.$emit('update:mealSchedule', formData);
        } else {
          this.$emit('update:mealSchedule', formData);
        }
      },
    },
  };
</script>