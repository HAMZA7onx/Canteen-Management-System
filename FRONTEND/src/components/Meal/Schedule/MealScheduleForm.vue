<template>
  <div>
    <div>
      <label for="mealName" class="block font-medium mb-2">Meal Name</label>
      <select
        id="mealName"
        v-model="mealSchedule.meal_name_id"
        class="w-full border border-gray-300 rounded-md px-3 py-2"
        @change="clearSelectedMealMenus"
      >
        <option value="">Select a meal name</option>
        <option v-for="mealName in mealNames" :key="mealName.id" :value="mealName.id">
          {{ mealName.name }}
        </option>
      </select>
    </div>

    <div v-if="mealSchedule.meal_name_id">
      <h3 class="text-lg font-medium mb-2">Select Meal Menus</h3>
      <div class="flex items-center mb-2">
        <select
          v-model="newMealMenu"
          class="border border-gray-300 rounded-md px-3 py-2 mr-2"
        >
          <option value="">Select a meal menu</option>
          <option v-for="mealMenu in availableMealMenus" :key="mealMenu.id" :value="mealMenu.id">
            {{ mealMenu.menu_name }}
          </option>
        </select>
        <button
          class="bg-blue-500 text-white px-4 py-2 rounded-md"
          @click="addMealMenu"
        >
          Add
        </button>
      </div>
      <div class="grid grid-cols-3 gap-4">
        <div
          v-for="(selectedMealMenuId, index) in mealSchedule.meal_menu_ids"
          :key="index"
          class="bg-gray-100 p-4 rounded-md flex justify-between items-center"
        >
          <span>{{ getMealMenuName(selectedMealMenuId) }}</span>
          <button
            class="text-red-500 hover:text-red-700"
            @click="removeMealMenu(index)"
          >
            Remove
          </button>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <label for="date" class="block font-medium mb-2">Date</label>
      <input
        type="date"
        id="date"
        v-model="mealSchedule.date"
        class="w-full border border-gray-300 rounded-md px-3 py-2"
        required
      />
    </div>

    <div class="mt-4 flex">
      <div class="mr-4">
        <label for="startTime" class="block font-medium mb-2">Start Time</label>
        <input
          type="time"
          id="startTime"
          v-model="mealSchedule.start_time"
          class="border border-gray-300 rounded-md px-3 py-2"
          required
        />
      </div>
      <div>
        <label for="endTime" class="block font-medium mb-2">End Time</label>
        <input
          type="time"
          id="endTime"
          v-model="mealSchedule.end_time"
          class="border border-gray-300 rounded-md px-3 py-2"
          required
        />
      </div>
    </div>

    <div v-if="validationError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
      {{ validationError }}
    </div>

    <div v-if="validationErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
      <strong class="font-bold">Validation Errors:</strong>
      <ul>
        <li v-for="(errors, field) in validationErrors" :key="field">
          {{ field }}: {{ errors.join(', ') }}
        </li>
      </ul>
    </div>

    <div class="mt-4 flex justify-end">
      <button
        class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2"
        @click="submitForm"
      >
        {{ isEditMode ? 'Update' : 'Create' }}
      </button>
      <button
        class="bg-gray-500 text-white px-4 py-2 rounded-md"
        @click="$emit('close')"
      >
        Cancel
      </button>
    </div>
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
      newMealMenu: null,
      isEditMode: false,
      validationErrors: null,
      validationError: '',
    };
  },
  computed: {
    ...mapGetters('mealName', ['mealNames']),
    ...mapGetters('mealMenu', ['mealMenus']),
    availableMealMenus() {
      return this.mealMenus.filter(
        (mealMenu) => !this.mealSchedule.meal_menu_ids.includes(mealMenu.id)
      );
    },
  },
  created() {
    this.isEditMode = !!this.mealSchedule.id;
  },
  methods: {
    ...mapActions('mealSchedule', ['createMealSchedule', 'updateMealSchedule']),
    clearSelectedMealMenus() {
      this.mealSchedule.meal_menu_ids = [];
      this.newMealMenu = null;
    },
    addMealMenu() {
      if (this.newMealMenu) {
        this.mealSchedule.meal_menu_ids.push(this.newMealMenu);
        this.newMealMenu = null;
      }
    },
    removeMealMenu(index) {
      this.mealSchedule.meal_menu_ids.splice(index, 1);
    },
    getMealMenuName(mealMenuId) {
      const mealMenu = this.mealMenus.find((menu) => menu.id === mealMenuId);
      return mealMenu ? mealMenu.menu_name : '';
    },
    displayValidationErrors(errors) {
      this.validationErrors = errors;
      // Check for specific validation error
      if (errors.end_time && Array.isArray(errors.end_time) && errors.end_time.includes('The end time field must be a date after start time.')) {
        this.validationError = 'The end time must be after the start time.';
      } else {
        this.validationError = ''; // Reset the validation error message
      }
    },
    submitForm() {
      const formData = {
        meal_name_id: this.mealSchedule.meal_name_id,
        meal_menu_ids: this.mealSchedule.meal_menu_ids,
        date: this.mealSchedule.date,
        start_time: this.mealSchedule.start_time,
        end_time: this.mealSchedule.end_time,
      };

      if (this.isEditMode) {
        this.updateMealSchedule(formData)
          .catch(() => {
            // Reset validation errors and validation error message
            this.validationErrors = null;
            this.validationError = '';
          });
      } else {
        this.createMealSchedule(formData)
          .catch(() => {
            // Reset validation errors and validation error message
            this.validationErrors = null;
            this.validationError = '';
          });
      }

      this.$emit('close');
    },
  },
};
</script>
