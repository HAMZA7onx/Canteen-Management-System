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
      // First, verify if the badge exists
      const verificationResult = await BadgingService.verifyBadge(rfid);
      if (!verificationResult.exists) {
        throw new Error('Badge not found in the system');
      }
  
      // If badge exists, proceed with scanning
      const result = await BadgingService.scanBadge(verificationResult.badge_id, day);
      console.log('verificationResult.badge_id: ',verificationResult.badge_id,' day: ', day);
      commit('SET_LAST_SCANNED_BADGE', result);
      commit('SET_ERROR', null);
      return result;
    } catch (error) {
      commit('SET_ERROR', error.message);
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
