import MealScheduleService from '@/services/mealSchedule.service';

const state = {
  mealSchedules: [],
};

const getters = {
  mealSchedules: (state) => state.mealSchedules,
};

const actions = {
  fetchMealSchedules({ commit }) {
    return MealScheduleService.getMealSchedules()
      .then((response) => {
        commit('SET_MEAL_SCHEDULES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching meal schedules:', error);
        throw error;
      });
  },

  createMealSchedule({ commit }, mealSchedule) {
    return MealScheduleService.createMealSchedule(mealSchedule)
      .then((response) => {
        commit('ADD_MEAL_SCHEDULE', response.data);
      })
      .catch((error) => {
        console.error('Error creating meal schedule:', error);
        throw error;
      });
  },

  updateMealSchedule({ commit }, { id, ...mealSchedule }) {
    return MealScheduleService.updateMealSchedule(id, mealSchedule)
      .then((response) => {
        commit('UPDATE_MEAL_SCHEDULE', response.data);
      })
      .catch((error) => {
        console.error('Error updating meal schedule:', error);
        throw error;
      });
  },

  deleteMealSchedule({ commit }, mealScheduleId) {
    return MealScheduleService.deleteMealSchedule(mealScheduleId)
      .then(() => {
        commit('DELETE_MEAL_SCHEDULE', mealScheduleId);
      })
      .catch((error) => {
        console.error('Error deleting meal schedule:', error);
        throw error;
      });
  },

  fetchCategoryDiscounts({ commit, state }, mealScheduleId) {
    const mealSchedule = state.mealSchedules.find((schedule) => schedule.id === mealScheduleId);
    if (mealSchedule && mealSchedule.categoryDiscounts) {
      return Promise.resolve();
    }

    return MealScheduleService.getCategoryDiscounts(mealScheduleId)
      .then((response) => {
        commit('SET_CATEGORY_DISCOUNTS', { mealScheduleId, categoryDiscounts: response.data });
      })
      .catch((error) => {
        console.error('Error fetching category discounts:', error);
        throw error;
      });
  },

  updateCategoryDiscounts({ commit }, { mealScheduleId, requestPayload }) {
    return MealScheduleService.updateCategoryDiscounts(mealScheduleId, requestPayload)
      .then((response) => {
        commit('SET_CATEGORY_DISCOUNTS', { mealScheduleId, categoryDiscounts: response.data });
      })
      .catch((error) => {
        console.error('Error updating category discounts:', error);
        throw error;
      });
  },
  
  
};

const mutations = {
  SET_MEAL_SCHEDULES(state, mealSchedules) {
    state.mealSchedules = mealSchedules;
  },
  ADD_MEAL_SCHEDULE(state, mealSchedule) {
    state.mealSchedules.push(mealSchedule);
  },
  UPDATE_MEAL_SCHEDULE(state, updatedMealSchedule) {
    const index = state.mealSchedules.findIndex((schedule) => schedule.id === updatedMealSchedule.id);
    if (index !== -1) {
      state.mealSchedules.splice(index, 1, updatedMealSchedule);
    }
  },
  DELETE_MEAL_SCHEDULE(state, mealScheduleId) {
    const index = state.mealSchedules.findIndex((schedule) => schedule.id === mealScheduleId);
    if (index !== -1) {
      state.mealSchedules.splice(index, 1);
    }
  },
  SET_CATEGORY_DISCOUNTS(state, { mealScheduleId, categoryDiscounts }) {
    const mealScheduleIndex = state.mealSchedules.findIndex((schedule) => schedule.id === mealScheduleId);
    if (mealScheduleIndex !== -1) {
      state.mealSchedules[mealScheduleIndex].categoryDiscounts = categoryDiscounts;
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
