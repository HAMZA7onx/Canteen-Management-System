import DailyMealService from '@/services/dailyMeal.service'

const state = {
  dailyMeals: []
}

const getters = {
  dailyMeals: state => state.dailyMeals
}

const actions = {
  fetchDailyMeals({ commit }) {
    return DailyMealService.getDailyMeals()
      .then(response => {
        commit('SET_DAILY_MEALS', response.data)
      })
      .catch(error => {
        console.error('Error fetching daily meals:', error)
        throw error
      })
  },

  createDailyMeal({ commit }, data) {
    return DailyMealService.createDailyMeal(data)
      .then(response => {
        commit('ADD_DAILY_MEAL', response.data)
      })
      .catch(error => {
        console.error('Error creating daily meal:', error)
        throw error
      })
  },

  updateDailyMeal({ commit }, data) {
    return DailyMealService.updateDailyMeal(data.id, data)
      .then(response => {
        commit('UPDATE_DAILY_MEAL', response.data)
      })
      .catch(error => {
        console.error('Error updating daily meal:', error)
        throw error
      })
  },

  deleteDailyMeal({ commit }, dailyMeal) {
    return DailyMealService.deleteDailyMeal(dailyMeal.id)
      .then(() => {
        commit('DELETE_DAILY_MEAL', dailyMeal.id)
      })
      .catch(error => {
        console.error('Error deleting daily meal:', error)
        throw error
      })
  },

  attachMenus({ commit }, { dailyMealId, menuIds }) {
    return DailyMealService.attachMenus(dailyMealId, menuIds)
      .then(response => {
        commit('UPDATE_DAILY_MEAL', response.data.daily_meal)
        return response.data.daily_meal
      })
  },  

  detachMenu({ commit, state }, { dailyMealId, menuId }) {
    return DailyMealService.detachMenu(dailyMealId, menuId)
      .then(response => {
        const updatedDailyMeal = response.data.daily_meal
        commit('UPDATE_DAILY_MEAL', updatedDailyMeal)
        return updatedDailyMeal
      })
      .catch(error => {
        console.error('Error detaching menu:', error)
        throw error
      })
  }
}

const mutations = {
  SET_DAILY_MEALS(state, dailyMeals) {
    state.dailyMeals = dailyMeals
  },

  ADD_DAILY_MEAL(state, dailyMeal) {
    state.dailyMeals.push(dailyMeal)
  },

  UPDATE_DAILY_MEAL(state, updatedDailyMeal) {
    const index = state.dailyMeals.findIndex(meal => meal.id === updatedDailyMeal.id)
    if (index !== -1) {
      // Use Vue.set or this.$set in Vue 2, or direct assignment in Vue 3
      state.dailyMeals[index] = updatedDailyMeal
    }
  },

  DELETE_DAILY_MEAL(state, id) {
    state.dailyMeals = state.dailyMeals.filter(dailyMeal => dailyMeal.id !== id)
  },

  ATTACH_MENU(state, { dailyMealId, menuId }) {
    const dailyMeal = state.dailyMeals.find(meal => meal.id === dailyMealId)
    if (dailyMeal) {
      if (!dailyMeal.menus) {
        dailyMeal.menus = []
      }
      dailyMeal.menus.push({ id: menuId })
    }
  },

  DETACH_MENU(state, { dailyMealId, menuId }) {
    const dailyMeal = state.dailyMeals.find(meal => meal.id === dailyMealId)
    if (dailyMeal && dailyMeal.menus) {
      dailyMeal.menus = dailyMeal.menus.filter(menu => menu.id !== menuId)
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
