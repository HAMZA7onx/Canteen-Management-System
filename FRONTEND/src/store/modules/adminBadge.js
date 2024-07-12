import BadgeService from '@/services/adminBadge.service';

const state = {
  badges: [],
  usersWithAllRfidsLost: [],
  usersWithoutRfids: [],
  eligibleUsers: [],
};

const getters = {
  badges: (state) => state.badges,
  usersWithAllRfidsLost: (state) => state.usersWithAllRfidsLost,
  usersWithoutRfids: (state) => state.usersWithoutRfids,
  eligibleUsers: (state) => state.eligibleUsers,
};

const actions = {
  fetchBadges({ commit }) {
    return BadgeService.getAll()
      .then((response) => {
        console.log('badges: ', response.data);
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
      .then(response => {
        console.log('Users with all RFIDs lost:', response.data);
        commit('SET_USERS_WITH_ALL_RFIDS_LOST', response.data);
        return response.data;
      })
      .catch(error => {
        console.error('Error fetching users with all RFIDs lost:', error);
        throw error;
      });
  },

  fetchUsersWithoutRfids({ commit }) {
    return BadgeService.getUsersWithoutRfids()
      .then(response => {
        console.log('Users without RFIDs:', response.data);
        const usersWithoutRfids = response.data || [];
        commit('SET_USERS_WITHOUT_RFIDS', usersWithoutRfids);
        return usersWithoutRfids;
      })
      .catch(error => {
        console.error('Error fetching users without RFIDs:', error);
        throw error;
      });
  },

  updateBadgeStatus({ commit, dispatch }, { badgeId, status }) {
    return BadgeService.updateBadgeStatus(badgeId, status)
      .then(response => {
        commit('UPDATE_BADGE_STATUS', response.data);
        // Fetch the updated list of eligible users
        return dispatch('fetchEligibleUsers');
      })
      .catch(error => {
        console.error('Error updating badge status:', error);
        throw error;
      });
  },

  assignRfidToUser({ commit, dispatch }, { badgeId, userId }) {
    console.log('assignRfidToUser', badgeId, userId);
    return BadgeService.assignRfidToUser(badgeId, userId)
      .then(response => {
        commit('ASSIGN_RFID_TO_USER', response.data);
        // Fetch the updated list of eligible users
        return dispatch('fetchEligibleUsers');
      })
      .catch(error => {
        console.error('Error assigning RFID to user:', error);
        throw error;
      });
  },

  fetchEligibleUsers({ commit, dispatch }) {
    return Promise.all([
      dispatch('fetchUsersWithAllRfidsLost'),
      dispatch('fetchUsersWithoutRfids'),
    ])
      .then(([usersWithAllRfidsLost, usersWithoutRfids]) => {
        const eligibleUsers = [...usersWithAllRfidsLost, ...usersWithoutRfids];
        commit('UPDATE_ELIGIBLE_USERS', eligibleUsers);
        return eligibleUsers;
      })
      .catch(error => {
        console.error('Error fetching eligible users:', error);
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
  UPDATE_ELIGIBLE_USERS(state, eligibleUsers) {
    state.eligibleUsers = eligibleUsers;
  },
  };
  
  export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
  };
  