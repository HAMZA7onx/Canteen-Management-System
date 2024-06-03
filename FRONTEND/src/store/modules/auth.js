import AuthService from '@/services/auth.service';

const state = {
  user: null,
  isLoggedIn: false,
};

const getters = {
  isLoggedIn: (state) => state.isLoggedIn,
  user: (state) => state.user,
};

const actions = {
  async login({ commit }, credentials) {
    try {
      const response = await AuthService.login(credentials);
      commit('setUser', response.data.data.admin);
      commit('setToken', response.data.data.token);
      commit('setLoggedIn', true);
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
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};