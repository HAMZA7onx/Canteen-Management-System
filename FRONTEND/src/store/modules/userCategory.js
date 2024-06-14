import UserCategoryService from '@/services/userCategory.service';

const state = {
  userCategories: [],
};

const getters = {
  userCategories: (state) => state.userCategories,
};

const actions = {
  fetchUserCategories({ commit }) {
    return UserCategoryService.getUserCategories()
      .then((response) => {
        commit('SET_USER_CATEGORIES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching user categories:', error);
        throw error;
      });
  },
  createUserCategory({ commit }, category) {
    return UserCategoryService.createUserCategory(category)
      .then((response) => {
        commit('ADD_USER_CATEGORY', response.data);
      })
      .catch((error) => {
        console.error('Error creating user category:', error);
        throw error;
      });
  },
  updateUserCategory({ commit }, category) {
    return UserCategoryService.updateUserCategory(category.id, category)
      .then((response) => {
        commit('UPDATE_USER_CATEGORY', response.data);
      })
      .catch((error) => {
        console.error('Error updating user category:', error);
        throw error;
      });
  },
  deleteUserCategory({ commit }, id) {
    return UserCategoryService.deleteUserCategory(id)
      .then(() => {
        commit('DELETE_USER_CATEGORY', id);
      })
      .catch((error) => {
        console.error('Error deleting user category:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_USER_CATEGORIES(state, userCategories) {
    state.userCategories = userCategories;
  },
  ADD_USER_CATEGORY(state, category) {
    state.userCategories.push(category);
  },
  UPDATE_USER_CATEGORY(state, updatedCategory) {
    state.userCategories = state.userCategories.map((category) =>
      category.id === updatedCategory.id ? updatedCategory : category
    );
  },
  DELETE_USER_CATEGORY(state, id) {
    state.userCategories = state.userCategories.filter(
      (category) => category.id !== id
    );
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
