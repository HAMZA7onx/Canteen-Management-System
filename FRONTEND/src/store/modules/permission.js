// FRONTEND/src/store/modules/permissions.js

import PermissionService from '@/services/permission.service';

const state = {
  userPermissions: []
};

const getters = {
  getUserPermissions: (state) => state.userPermissions
};

const actions = {
  async refreshPermissions({ commit }) {
    try {
      const response = await PermissionService.refreshPermissions();
      console.log('refresh permissions: ', response);
      commit('setUserPermissions', response.data.permissions);
      return response.data;
    } catch (error) {
      throw error;
    }
  }
};

const mutations = {
  setUserPermissions(state, permissions) {
    state.userPermissions = permissions;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
