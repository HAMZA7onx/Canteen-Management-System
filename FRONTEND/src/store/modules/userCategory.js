import UserCategoryService from '@/services/userCategory.service';
import store from '@/store'; // Import the Vuex store instance

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
    const user = store.getters['auth/user']; // Get the authenticated user from the store
    const newCategory = {
      name: category.name,
      description: category.description,
      creator: user.email,
      editors: [],
    };

    return UserCategoryService.createUserCategory(newCategory)
      .then((response) => {
        commit('ADD_USER_CATEGORY', response.data);
      })
      .catch((error) => {
        console.error('Error creating user category:', error);
        throw error;
      });
  },
  updateUserCategory({ commit }, category) {
    const user = store.getters['auth/user']; // Get the authenticated user from the store
    const updatedCategory = {
      id: category.id,
      name: category.name,
      description: category.description,
      creator: category.creator,
      editors: category.editors,
    };

    // Add the current user's email to the editors array if not already present
    if (!updatedCategory.editors.includes(user.email)) {
      updatedCategory.editors.push(user.email);
    }

    return UserCategoryService.updateUserCategory(category.id, updatedCategory)
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
