import UserService from '@/services/user.service';

const state = {
  users: [],
};

const getters = {
  users: (state) => state.users,
};

const actions = {
  fetchUsers({ commit }) { 
    return UserService.getUsers()
      .then((response) => {
        console.log('response.data: ', response.data);
        commit('SET_USERS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching users:', error);
        throw error;
      });
  },

  createUser({ commit }, user) {
    console.log('user: ', user);
    return UserService.createUser(user)
      .then((response) => {
        console.log('response.data.user: ', response);
        commit('ADD_USER', response.data.user);
      })
      .catch((error) => {
        console.error('Error creating user:', error);
        throw error;
      });
  },

  updateUser({ commit, state }, user) { // Access the state via the second argument
    console.log('user: ', user); // Correctly log the user object
    console.log('state users: ', state.users); // Correctly log the users state
    return UserService.updateUser(user.id, user)
      .then((response) => {
        commit('UPDATE_USER', response.data.user);
      })
      .catch((error) => {
        console.error('Error updating user:', error);
        throw error;
      });
  },

  deleteUser({ commit }, userId) {
    return UserService.deleteUser(userId)
      .then(() => {
        commit('DELETE_USER', userId);
      })
      .catch((error) => {
        console.error('Error deleting user:', error);
        throw error;
      });
  },
  importUsers({ dispatch }, formData) {
    return UserService.importUsers(formData)
      .then((response) => {
        dispatch('fetchUsers');
        return response.data;
      })
      .catch((error) => {
        console.error('Error importing users:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_USERS(state, users) {
    state.users = users;
  },
  ADD_USER(state, user) {
    state.users.push(user);
  },
  UPDATE_USER(state, updatedUser) {
    state.users = state.users.map((user) => (user.id === updatedUser.id ? updatedUser : user));
  },
  DELETE_USER(state, userId) {
    state.users = state.users.filter((user) => user.id !== userId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
