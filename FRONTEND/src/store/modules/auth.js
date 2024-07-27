import AuthService from '@/services/auth.service';
import PermissionService from '@/services/permission.service';

const state = {
  user: null,
  isLoggedIn: false,
  token: null,
  userRoles: [],
  userPermissions: [], 
  availableRoles: [],
  availablePermissions: [],
};

const getters = {
  isLoggedIn: (state) => state.isLoggedIn,
  user: (state) => state.user,
  token: (state) => state.token,
  userRoles: (state) => state.userRoles,
  userPermissions: (state) => state.userPermissions,
  availableRoles: (state) => state.availableRoles,
  availablePermissions: (state) => state.availablePermissions,
};

const actions = {
  async login({ commit, dispatch }, credentials) {
    try {
      /**
       * Logs the user in using the provided credentials.
       *
       * @param {Object} credentials - The user's login credentials.
       * @param {string} credentials.username - The user's username.
       * @param {string} credentials.password - The user's password.
       * @returns {Promise<Object>} - The user data and authentication token from the server.
       */
      const response = await AuthService.login(credentials);
      commit('setUser', response.data.data.admin);
      commit('setToken', response.data.data.token);
      commit('setLoggedIn', true);
      commit('setUserRoles', response.data.data.roles);
      commit('setUserPermissions', response.data.data.permissions);
      commit('setAvailableRoles', response.data.data.availableRoles);
      commit('setAvailablePermissions', response.data.data.availablePermissions);

      // Dispatch the refreshPermissions action after successful login
      await dispatch('refreshPermissions');

      return Promise.resolve(response.data);
    } catch (error) {
      return Promise.reject(error);
    }
  },

  async logout({ commit }) {
    try {
      await AuthService.logout();
      commit('setUser', null);
      commit('setToken', null);
      commit('setLoggedIn', false);
      commit('setUserRoles',[]);
      commit('setUserPermissions', []);
      commit('setAvailableRoles', []);
      commit('setAvailablePermissions', []);
    } catch (error) {
      console.error(error);
    }
  },

  async refreshPermissions({ commit }) {
    try {
      const response = await PermissionService.refreshPermissions();
      console.log('refresh permissions: ', response);
      commit('setUserPermissions', response.data.permissions);
      return response.data;
    } catch (error) {
      throw error;
    }
  },
};

const mutations = {
  setUser(state, user) {
    state.user = user;
  },
  setToken(state, token) {
    state.token = token;
  },
  setLoggedIn(state, isLoggedIn) {
    state.isLoggedIn = isLoggedIn;
  },
  setUserRoles(state, roles) {
    state.userRoles = roles;
  },
  setUserPermissions(state, permissions) {
    state.userPermissions = permissions;
  },
  setAvailableRoles(state, roles) {
    state.availableRoles = roles;
  },
  setAvailablePermissions(state, permissions) {
    state.availablePermissions = permissions;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
