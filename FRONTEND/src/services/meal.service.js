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
    state.dailyMeals = state.dailyMeals.map(dailyMeal =>
      dailyMeal.id === updatedDailyMeal.id ? updatedDailyMeal : dailyMeal
    )
  },

  DELETE_DAILY_MEAL(state, id) {
    state.dailyMeals = state.dailyMeals.filter(dailyMeal => dailyMeal.id !== id)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
