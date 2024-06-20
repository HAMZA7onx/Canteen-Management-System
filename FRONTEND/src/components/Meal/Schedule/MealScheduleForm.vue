<template>
  <div>
    <!-- Meal Name Select -->
    <div>
      <label for="mealName" class="block font-medium mb-2">Meal Name</label>
      <select
        id="mealName"
        v-model="mealSchedule.meal_name_id"
        class="w-full border border-gray-300 rounded-md px-3 py-2"
        @change="clearSelectedMealMenus"
      >
        <option value="">Select a meal name</option>
        <option v-for="mealName in mealNames" :key="mealName.id" :value="mealName.id" :selected="mealName.id === mealSchedule.meal_name_id">
          {{ mealName.name }}
        </option>
      </select>
    </div>

    <!-- Meal Menus Select -->
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
          v-for="(selectedMealMenu, index) in mealSchedule.meal_menus"
          :key="index"
          class="bg-gray-100 p-4 rounded-md flex justify-between items-center"
        >
          <span>{{ selectedMealMenu.menu_name }}</span>
          <button
            class="text-red-500 hover:text-red-700"
            @click="removeMealMenu(index)"
          >
            Remove
          </button>
        </div>
      </div>
    </div>

    <!-- Date Input -->
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

    <!-- Start Time and End Time Inputs -->
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

    <!-- Validation Errors (Server) -->
    <div v-if="validationErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
      <strong class="font-bold">Validation Errors:</strong>
      <ul>
        <li v-if="typeof validationErrors === 'string'">{{ validationErrors }}</li>
        <li v-else-if="typeof validationErrors === 'object'">
          <ul>
            <li v-for="(errors, field) in validationErrors" :key="field">
              {{ field }}: {{ Array.isArray(errors) ? errors.join(', ') : errors }}
            </li>
          </ul>
        </li>
      </ul>
    </div>

    <!-- Form Buttons -->
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
    };
  },
  computed: {
    ...mapGetters('mealName', ['mealNames']),
    ...mapGetters('mealMenu', ['mealMenus']),
    availableMealMenus() {
      const selectedMealMenuIds = [
        ...new Set([
          ...(this.mealSchedule.meal_menu_ids || []),
          ...(this.mealSchedule.meal_menus?.map((mealMenu) => mealMenu.id) || []),
        ]),
      ];
      return this.mealMenus.filter((mealMenu) => !selectedMealMenuIds.includes(mealMenu.id));
    },
    selectedMealMenuIds() {
      return [
        ...(this.mealSchedule.meal_menu_ids || []),
        ...(this.mealSchedule.meal_menus?.map((mealMenu) => mealMenu.id) || []),
      ];
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
        const newMealMenuObject = this.mealMenus.find((menu) => menu.id === this.newMealMenu);
        if (newMealMenuObject) {
          this.mealSchedule.meal_menus.push(newMealMenuObject);
          this.newMealMenu = null;
        }
      }
    },
    removeMealMenu(index) {
      this.mealSchedule.meal_menus.splice(index, 1);
    },
    getMealMenuName(mealMenuId) {
      const mealMenu = this.mealMenus.find((menu) => menu.id === mealMenuId);
      return mealMenu ? mealMenu.menu_name : '';
    },
    submitForm() {
      const formData = {
        meal_name_id: this.mealSchedule.meal_name_id,
        meal_menu_ids: this.selectedMealMenuIds,
        date: this.mealSchedule.date,
        start_time: this.mealSchedule.start_time,
        end_time: this.mealSchedule.end_time,
      };

      // Reset validation errors
      this.validationErrors = null;

      // Check for empty required fields
      if (!formData.meal_name_id) {
        this.validationErrors = 'Please select a meal name.';
      } else if (!formData.meal_menu_ids.length) {
        this.validationErrors = 'Please select at least one meal menu.';
      } else if (!formData.date) {
        this.validationErrors = 'Please enter a date.';
      } else if (!formData.start_time) {
        this.validationErrors = 'Please enter a start time.';
      } else if (!formData.end_time) {
        this.validationErrors = 'Please enter an end time.';
      }

      // If there are no empty required fields, proceed with the form submission
      if (!this.validationErrors) {
        if (this.isEditMode) {
          this.updateMealSchedule({ ...formData, id: this.mealSchedule.id })
            .then(() => {
              this.$emit('close');
            })
            .catch((error) => {
              this.handleValidationErrors(error);
            });
        } else {
          this.createMealSchedule(formData)
            .then(() => {
              this.$emit('close');
            })
            .catch((error) => {
              this.handleValidationErrors(error);
            });
        }
      }
    },
    handleValidationErrors(error) {
      if (error.response) {
        // Handle the case when the server returns a JSON response
        const responseData = error.response.data;
        if (typeof responseData === 'object') {
          const errors = responseData.errors;
          if (errors) {
            this.validationErrors = errors;
          } else {
            console.error('Error:', error);
          }
        } else {
          // Handle the case when the server returns a plain text error message
          this.validationErrors = responseData;
        }
      } else {
        // Handle the case when the server returns a plain text error message
        this.validationErrors = error.message;
      }
    },
  },
  watch: {
    mealSchedule: {
      handler(newValue, oldValue) {
        if (newValue.id !== oldValue.id) {
          this.isEditMode = !!newValue.id;
        }
      },
      deep: true,
    },
  },
};
</script>
