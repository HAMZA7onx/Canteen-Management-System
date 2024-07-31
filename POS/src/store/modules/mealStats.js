import MealStatsService from '@/services/mealStats.service';

const state = {
  dailyMeals: [],
  isLoading: false,
  error: null,
};

const getters = {
  getDailyMeals: (state) => state.dailyMeals,
  getIsLoading: (state) => state.isLoading,
  getError: (state) => state.error,
};

const actions = {
  async fetchDailyMealStats({ commit }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const data = await MealStatsService.getDailyStats();
      console.log('data: ', data);
      commit('SET_DAILY_MEALS', data);
    } catch (error) {
      commit('SET_ERROR', error.message || 'An error occurred while fetching meal stats');
    } finally {
      commit('SET_LOADING', false);
    }
  },
};

const mutations = {
  SET_DAILY_MEALS(state, meals) {
    state.dailyMeals = meals;
  },
  SET_LOADING(state, isLoading) {
    state.isLoading = isLoading;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
