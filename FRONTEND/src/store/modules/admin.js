import AdminService from '@/services/admin.service';

const state = {
  admins: [],
};

const getters = {
  admins: (state) => state.admins,
};

const actions = {
  async fetchAdmins({ commit }) {
    try {
      const response = await AdminService.getAdmins();
      commit('setAdmins', response.data);
    } catch (error) {
      console.error(error);
    }
  },

  async createAdmin({ commit }, data) {
    try {
      const response = await AdminService.createAdmin(data);
      commit('addAdmin', response.data);
    } catch (error) {
      console.error(error);
    }
  },

  // Add other actions for updating, deleting, and managing admin roles/permissions
};

const mutations = {
  setAdmins(state, admins) {
    state.admins = admins;
  },

  addAdmin(state, admin) {
    state.admins.push(admin);
  },

  // Add other mutations for updating, deleting, and managing admin roles/permissions
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
