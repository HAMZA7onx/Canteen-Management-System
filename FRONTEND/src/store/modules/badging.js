import BadgingService from '@/services/badging.service';

const state = {
  lastScannedBadge: null,
  error: null,
};

const getters = {
  getLastScannedBadge: (state) => state.lastScannedBadge,
  getError: (state) => state.error,
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
      commit('SET_ERROR', null);
      return result;
    } catch (error) {
      commit('SET_ERROR', error.response ? error.response.data.error : error.message);
      throw error;
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
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
