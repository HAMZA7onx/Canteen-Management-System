import AuthService from '@/services/auth.service';

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
  async login({ commit }, credentials) {
    try {
      const response = await AuthService.login(credentials);
      commit('setUser', response.data.data.admin);
      commit('setToken', response.data.data.token);
      commit('setLoggedIn', true);
      commit('setUserRoles', response.data.data.roles);
      commit('setUserPermissions', response.data.data.permissions);
      commit('setAvailableRoles', response.data.data.availableRoles);
      commit('setAvailablePermissions', response.data.data.availablePermissions);
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
