import BadgeService from '@/services/badge.service';

const state = {
  badges: [],
};

const getters = {
  badges: (state) => state.badges,
};

const actions = {
  fetchBadges({ commit }) {
    return BadgeService.getAll()
      .then((response) => {
        console.log('Badges fetched successfully:', response.data);
        commit('SET_BADGES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching badges:', error);
        throw error;
      });
  },
  deleteBadge({ commit }, badgeId) {
    return BadgeService.delete(badgeId)
      .then(() => {
        commit('DELETE_BADGE', badgeId);
      })
      .catch((error) => {
        console.error('Error deleting badge:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_BADGES(state, badges) {
    state.badges = badges;
  },
  DELETE_BADGE(state, badgeId) {
    state.badges = state.badges.filter((badge) => badge.id !== badgeId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
