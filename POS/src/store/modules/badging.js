import BadgingService from '@/services/badging.service';

const state = {
  lastScannedBadge: null,
  error: null,
  currentMeal: null,
  lastScannedPerson: null,
  discounts: [],
  currentMealBadgeCount: 0
};

const getters = {
  getLastScannedBadge: (state) => state.lastScannedBadge,
  getError: (state) => state.error,
  getCurrentMeal: (state) => state.currentMeal,
  getLastScannedPerson: (state) => state.lastScannedPerson,
  getDiscounts: (state) => state.discounts,
  getCurrentMealBadgeCount: (state) => state.currentMealBadgeCount,
};
 
const actions = {
  async verifyAndScanBadge({ commit }, { rfid, day }) {
    try {
      const result = await BadgingService.scanBadge(rfid, day);
      commit('SET_LAST_SCANNED_BADGE', rfid);
      commit('SET_LAST_SCANNED_PERSON', result.badgeOwner);
      commit('SET_ERROR', null);
      return result;
    } catch (error) {
      commit('SET_ERROR', error.response ? error.response.data.error : error.message);
      if (error.response && error.response.data.error === "Badge not allowed") {
        commit('SET_LAST_SCANNED_PERSON', null);
      } else if (error.response && error.response.data.badgeOwner) {
        commit('SET_LAST_SCANNED_PERSON', error.response.data.badgeOwner);
      }
      throw error;
    }
  },
  
  async fetchCurrentMeal({ commit }) {
    try {
      const meal = await BadgingService.getCurrentMeal();
      commit('SET_CURRENT_MEAL', meal);
    } catch (error) {
      commit('SET_CURRENT_MEAL', null);
      console.error('Error fetching current meal:', error);
    }
  },

  async fetchDiscounts({ commit }, { day, mealId }) {
    try {
      const discounts = await BadgingService.getDiscountsForMeal(day, mealId);
      commit('SET_DISCOUNTS', discounts);
      return discounts;
    } catch (error) {
      console.error('Error fetching discounts:', error);
      commit('SET_DISCOUNTS', []);
      throw error;
    }
  },

  async fetchCurrentMealBadgeCount({ commit }) {
    try {
      const count = await BadgingService.getCurrentMealBadgeCount();
      commit('SET_CURRENT_MEAL_BADGE_COUNT', count);
    } catch (error) {
      console.error('Error fetching current meal badge count:', error);
    }
  },
};

const mutations = {
  SET_LAST_SCANNED_BADGE(state, badge) {
    state.lastScannedBadge = badge;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
  SET_CURRENT_MEAL(state, meal) {
    state.currentMeal = meal;
  },
  SET_LAST_SCANNED_PERSON(state, person) {
    state.lastScannedPerson = person;
  },
  SET_DISCOUNTS(state, discounts) {
    state.discounts = discounts;
  },
  SET_CURRENT_MEAL_BADGE_COUNT(state, count) {
    state.currentMealBadgeCount = count;
  },
  INCREMENT_CURRENT_MEAL_BADGE_COUNT(state) {
    state.currentMealBadgeCount++;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
