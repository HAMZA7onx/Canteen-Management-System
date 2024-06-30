import BadgeService from '@/services/badge.service';

const state = {
  badges: [],
  usersWithAllRfidsLost: [],
  usersWithoutRfids: [],
};

const getters = {
  badges: (state) => state.badges,
  usersWithAllRfidsLost: (state) => state.usersWithAllRfidsLost,
  usersWithoutRfids: (state) => state.usersWithoutRfids,
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

  fetchUsersWithAllRfidsLost({ commit }) {
    return BadgeService.getUsersWithAllRfidsLost()
      .then((response) => {
        commit('SET_USERS_WITH_ALL_RFIDS_LOST', response.data);
      })
      .catch((error) => {
        console.error('Error fetching users with all RFIDs lost:', error);
        throw error;
      });
  },

  fetchUsersWithoutRfids({ commit }) {
    return BadgeService.getUsersWithoutRfids()
      .then((response) => {
        commit('SET_USERS_WITHOUT_RFIDS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching users without RFIDs:', error);
        throw error;
      });
  },

  updateBadgeStatus({ commit }, { badgeId, status }) {
    return BadgeService.updateBadgeStatus(badgeId, status)
      .then(response => {
        console.log('updateBadgeStatus: ', response.data);
        commit('UPDATE_BADGE_STATUS', response.data);
        return response.data; // Return only the data property
      })
      .catch(error => {
        console.error('Error updating badge status:', error);
        throw error;
      });
  },
  

  assignRfidToUser({ commit }, { badgeId, userId }) {
    return BadgeService.assignRfidToUser(badgeId, userId)
      .then((response) => {
        commit('ASSIGN_RFID_TO_USER', response.data);
      })
      .catch((error) => {
        console.error('Error assigning RFID to user:', error);
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
  SET_USERS_WITH_ALL_RFIDS_LOST(state, users) {
    state.usersWithAllRfidsLost = users;
  },
  SET_USERS_WITHOUT_RFIDS(state, users) {
    state.usersWithoutRfids = users;
  },
  UPDATE_BADGE_STATUS(state, updatedBadge) {
    const index = state.badges.findIndex((badge) => badge.id === updatedBadge.id);
    if (index !== -1) {
      state.badges.splice(index, 1, updatedBadge);
    }
  },
  ASSIGN_RFID_TO_USER(state, updatedBadge) {
    const index = state.badges.findIndex((badge) => badge.id === updatedBadge.id);
    if (index !== -1) {
      state.badges.splice(index, 1, updatedBadge);
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
