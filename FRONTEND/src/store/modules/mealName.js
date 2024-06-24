import MealNameService from '@/services/mealName.service';
console.log('meal name');
const state = {
  mealNames: [],
};

const getters = {
  mealNames: (state) => state.mealNames,
};

const actions = {
  fetchMealNames({ commit }) {
    return MealNameService.getMealNames()
      .then((response) => {
        commit('SET_MEAL_NAMES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching meal names:', error);
        throw error;
      });
  },

  createMealName({ commit }, mealName) {
    return MealNameService.createMealName(mealName)
      .then((response) => {
        commit('ADD_MEAL_NAME', response.data);
      })
      .catch((error) =>
        {
          console.error('Error creating meal name:', error);
          throw error;
        });
        },
        
        updateMealName({ commit }, mealName) {
          return MealNameService.updateMealName(mealName.id, mealName)
            .then((response) => {
              commit('UPDATE_MEAL_NAME', response.data);
            })
            .catch((error) => {
              console.error('Error updating meal name:', error);
              throw error;
            });
        },
        
        deleteMealName({ commit }, mealNameId) {
          return MealNameService.deleteMealName(mealNameId)
            .then(() => {
              commit('DELETE_MEAL_NAME', mealNameId);
            })
            .catch((error) => {
              console.error('Error deleting meal name:', error);
              throw error;
            });
        },
        };
        
        const mutations = {
        SET_MEAL_NAMES(state, mealNames) {
          state.mealNames = mealNames;
        },
        ADD_MEAL_NAME(state, mealName) {
          state.mealNames.push(mealName);
        },
        UPDATE_MEAL_NAME(state, updatedMealName) {
          state.mealNames = state.mealNames.map((mealName) =>
            mealName.id === updatedMealName.id ? updatedMealName : mealName
          );
        },
        DELETE_MEAL_NAME(state, mealNameId) {
          state.mealNames = state.mealNames.filter((mealName) => mealName.id !== mealNameId);
        },
        };
        
        export default {
        namespaced: true,
        state,
        getters,
        actions,
        mutations,
        };
        