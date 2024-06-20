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
        throw error; // Rethrow the error to be caught in the component
      });
  },

 updateMealSchedule({ commit }, { id, ...mealSchedule }) {
  return MealScheduleService.updateMealSchedule(id, mealSchedule)
    .then((response) => {
      commit('UPDATE_MEAL_SCHEDULE', response.data);
    })
    .catch((error) => {
      console.error('Error updating meal schedule:', error);
      throw error; // Rethrow the error to be caught in the component
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
};

const mutations = {
  SET_MEAL_SCHEDULES(state, mealSchedules) {
    state.mealSchedules = mealSchedules;
  },
  ADD_MEAL_SCHEDULE(state, mealSchedule) {
    state.mealSchedules.push(mealSchedule);
  },
  UPDATE_MEAL_SCHEDULE(state, updatedMealSchedule) {
    state.mealSchedules = state.mealSchedules.map((mealSchedule) =>
      mealSchedule.id === updatedMealSchedule.id ? updatedMealSchedule : mealSchedule
    );
  },
  DELETE_MEAL_SCHEDULE(state, mealScheduleId) {
    state.mealSchedules = state.mealSchedules.filter((mealSchedule) => mealSchedule.id !== mealScheduleId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
