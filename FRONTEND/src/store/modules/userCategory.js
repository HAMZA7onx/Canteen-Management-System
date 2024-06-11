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
  createUserCategory({ commit }, userCategory) {
    return UserCategoryService.createUserCategory(userCategory)
      .then((response) => {
        commit('ADD_USER_CATEGORY', response.data);
      })
      .catch((error) => {
        console.error('Error creating user category:', error);
        throw error;
      });
  },
  updateUserCategory({ commit }, userCategory) {
    return UserCategoryService.updateUserCategory(userCategory.id, userCategory)
      .then((response) => {
        commit('UPDATE_USER_CATEGORY', response.data);
      })
      .catch((error) => {
        console.error('Error updating user category:', error);
        throw error;
      });
  },
  deleteUserCategory({ commit }, userCategoryId) {
    return UserCategoryService.deleteUserCategory(userCategoryId)
      .then(() => {
        commit('DELETE_USER_CATEGORY', userCategoryId);
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
  ADD_USER_CATEGORY(state, userCategory) {
    state.userCategories.push(userCategory);
  },
  UPDATE_USER_CATEGORY(state, updatedUserCategory) {
    state.userCategories = state.userCategories.map((userCategory) =>
      userCategory.id === updatedUserCategory.id ? updatedUserCategory : userCategory
    );
  },
  DELETE_USER_CATEGORY(state, userCategoryId) {
    state.userCategories = state.userCategories.filter(
      (userCategory) => userCategory.id !== userCategoryId
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
