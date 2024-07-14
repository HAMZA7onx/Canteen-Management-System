import BadgingService from '@/services/badging.service';

const state = {
  lastScannedBadge: null,
  error: null,
  currentMeal: null,
  lastScannedPerson: null,
};

const getters = {
  getLastScannedBadge: (state) => state.lastScannedBadge,
  getError: (state) => state.error,
  getCurrentMeal: (state) => state.currentMeal,
  getLastScannedPerson: (state) => state.lastScannedPerson,
};

const actions = {
  async verifyAndScanBadge({ commit }, { rfid, day }) {
    try {
      const verificationResult = await BadgingService.verifyBadge(rfid);
      if (!verificationResult.exists) {
        throw { response: { status: 404, data: { error: 'Badge not found in the system' } } };
      }

      const result = await BadgingService.scanBadge(verificationResult.badge_id, day);
      commit('SET_LAST_SCANNED_BADGE', result);
      commit('SET_LAST_SCANNED_PERSON', result.personName);
      commit('SET_ERROR', null);
      return result;
    } catch (error) {
      commit('SET_ERROR', error.response ? error.response.data.error : error.message);
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
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
