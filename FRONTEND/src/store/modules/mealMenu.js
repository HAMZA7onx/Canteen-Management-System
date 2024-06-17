import MealMenuService from '@/services/mealMenu.service';

const state = {
  mealMenus: [],
};

const getters = {
  mealMenus: (state) => state.mealMenus,
};

const actions = {
  fetchMealMenus({ commit }) {
    return MealMenuService.getMealMenus()
      .then((response) => {
        commit('SET_MEAL_MENUS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching meal menus:', error);
        throw error;
      });
  },

  createMealMenu({ commit }, mealMenu) {
    return MealMenuService.createMealMenu(mealMenu)
      .then((response) => {
        commit('ADD_MEAL_MENU', response.data);
      })
      .catch((error) => {
        console.error('Error creating meal menu:', error);
        throw error;
      });
  },

  updateMealMenu({ commit }, mealMenu) {
    return MealMenuService.updateMealMenu(mealMenu.id, mealMenu)
      .then((response) => {
        commit('UPDATE_MEAL_MENU', response.data);
      })
      .catch((error) => {
        console.error('Error updating meal menu:', error);
        throw error;
      });
  },

  deleteMealMenu({ commit }, mealMenuId) {
    return MealMenuService.deleteMealMenu(mealMenuId)
      .then(() => {
        commit('DELETE_MEAL_MENU', mealMenuId);
      })
      .catch((error) => {
        console.error('Error deleting meal menu:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_MEAL_MENUS(state, mealMenus) {
    state.mealMenus = mealMenus;
  },
  ADD_MEAL_MENU(state, mealMenu) {
    state.mealMenus.push(mealMenu);
  },
  UPDATE_MEAL_MENU(state, updatedMealMenu) {
    state.mealMenus = state.mealMenus.map((mealMenu) =>
      mealMenu.id === updatedMealMenu.id ? updatedMealMenu : mealMenu
    );
  },
  DELETE_MEAL_MENU(state, mealMenuId) {
    state.mealMenus = state.mealMenus.filter((mealMenu) => mealMenu.id !== mealMenuId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
