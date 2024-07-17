import WeekScheduleService from '@/services/weekSchedule.service'

const state = {
  weekSchedules: [],
} 

const getters = {
  weekSchedules: (state) => state.weekSchedules,
  getAssignedDailyMealsForDay: (state) => (weekScheduleId, day) => {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    const assignedDailyMealsData = weekSchedule ? weekSchedule[`${day}_daily_meals`] || [] : []
    return assignedDailyMealsData.map((dailyMealData) => ({
      daily_meal_id: dailyMealData.id,
      start_time: dailyMealData.pivot?.start_time,
      end_time: dailyMealData.pivot?.end_time,
      price: dailyMealData.pivot?.price,
      discounts: dailyMealData.discounts || {}  // Change this to an object
    }))
  },  
  activeWeekSchedule: (state) => state.weekSchedules.find(ws => ws.status === 'active'),
}

const actions = {
  fetchWeekSchedules({ commit }) {
    return WeekScheduleService.getWeekSchedules()
      .then((response) => {
        commit('SET_WEEK_SCHEDULES', response.data)
      })
      .catch((error) => {
        console.error('Error fetching week schedules:', error)
        throw error
      })
  },

  createWeekSchedule({ commit, dispatch }, weekScheduleData) {
    console.log('weekScheduleData', weekScheduleData)
    return WeekScheduleService.createWeekSchedule(weekScheduleData)
      .then((response) => {
        commit('ADD_WEEK_SCHEDULE', response.data)
        if (response.data.status === 'active') {
          dispatch('updateOtherSchedulesStatus', response.data.id)
        }
      })
      .catch((error) => {
        console.error('Error creating week schedule:', error)
        throw error
      })
  },

  updateWeekSchedule({ commit, dispatch }, { id, data }) {
    return WeekScheduleService.updateWeekSchedule(id, data)
      .then((response) => {
        commit('UPDATE_WEEK_SCHEDULE', response.data)
        if (response.data.status === 'active') {
          dispatch('updateOtherSchedulesStatus', id)
        }
      })
      .catch((error) => {
        console.error('Error updating week schedule:', error)
        throw error
      })
  },

  updateOtherSchedulesStatus({ state, commit }, activeId) {
    state.weekSchedules.forEach(schedule => {
      if (schedule.id !== activeId && schedule.status === 'active') {
        commit('UPDATE_WEEK_SCHEDULE', { ...schedule, status: 'inactive' })
      }
    })
  },

  deleteWeekSchedule({ commit }, id) {
    return WeekScheduleService.deleteWeekSchedule(id)
      .then(() => {
        commit('DELETE_WEEK_SCHEDULE', id)
      })
      .catch((error) => {
        console.error('Error deleting week schedule:', error)
        throw error
      })
  },

  assignDailyMeals({ commit }, { weekScheduleId, day, dailyMealData }) {
    console.log('assignDailyMeals', weekScheduleId, day, dailyMealData)
    return WeekScheduleService.assignDailyMeals(weekScheduleId, day, dailyMealData)
      .then((response) => {
        commit('ASSIGN_DAILY_MEAL', { weekScheduleId, day, dailyMealData: response.data })
      })
      .catch((error) => {
        console.error('Error assigning daily meal:', error)
        throw error
      })
  },
  

  detachDailyMeal({ commit }, { weekScheduleId, day, dailyMealId }) {
    return WeekScheduleService.detachDailyMeal(weekScheduleId, day, dailyMealId)
      .then(() => {
        commit('DETACH_DAILY_MEAL', { weekScheduleId, day, dailyMealId })
      })
      .catch((error) => {
        console.error('Error detaching daily meal:', error)
        throw error
      })
  },

  fetchDiscountsForDailyMeal({ commit }, { weekScheduleId, day, dailyMealId }) {
    return new Promise((resolve, reject) => {
      WeekScheduleService.getDailyMealDiscounts(weekScheduleId, day, dailyMealId)
        .then(response => {
          console.log('DISCOUNTS DATA: ', response)
          const discounts = response.data
          console.log('Discounts received from API:', discounts)
          commit('SET_DISCOUNTS_FOR_DAILY_MEAL', { weekScheduleId, day, dailyMealId, discounts })
          resolve(discounts)
        })
        .catch(error => {
          console.error('Error in fetchDiscountsForDailyMeal:', error)
          reject(error)
        })
    })
  }
  
}

const mutations = {
  SET_WEEK_SCHEDULES(state, weekSchedules) {
    state.weekSchedules = weekSchedules
  },

  ADD_WEEK_SCHEDULE(state, newWeekSchedule) {
    state.weekSchedules.push(newWeekSchedule)
  },

  UPDATE_WEEK_SCHEDULE(state, updatedWeekSchedule) {
    const index = state.weekSchedules.findIndex(ws => ws.id === updatedWeekSchedule.id)
    if (index !== -1) {
      state.weekSchedules.splice(index, 1, updatedWeekSchedule)
    }
  },

  DELETE_WEEK_SCHEDULE(state, id) {
    state.weekSchedules = state.weekSchedules.filter((weekSchedule) => weekSchedule.id !== id)
  },

  ASSIGN_DAILY_MEAL(state, { weekScheduleId, day, dailyMealData }) {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    if (weekSchedule) {
      const existingDailyMeals = weekSchedule[`${day}DailyMeals`] || []
      weekSchedule[`${day}DailyMeals`] = [...existingDailyMeals, dailyMealData]
    }
  },
  

  DETACH_DAILY_MEAL(state, { weekScheduleId, day, dailyMealId }) {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    if (weekSchedule) {
      const dailyMealsArray = weekSchedule[`${day}DailyMeals`]
      if (dailyMealsArray) {
        weekSchedule[`${day}DailyMeals`] = dailyMealsArray.filter(
          (dailyMeal) => dailyMeal.daily_meal_id !== dailyMealId
        )
      }
    }
  },

  SET_DISCOUNTS_FOR_DAILY_MEAL(state, { weekScheduleId, day, dailyMealId, discounts }) {
    const weekSchedule = state.weekSchedules.find(ws => ws.id === weekScheduleId)
    if (weekSchedule) {
      const dailyMeals = weekSchedule[`${day}_daily_meals`]
      if (dailyMeals) {
        const dailyMeal = dailyMeals.find(dm => dm.id === dailyMealId)
        if (dailyMeal) {
          dailyMeal.discounts = discounts
          // Trigger reactivity
          state.weekSchedules = [...state.weekSchedules]
        } else {
          console.warn(`Daily meal with id ${dailyMealId} not found for ${day}`)
        }
      } else {
        console.warn(`No daily meals found for ${day}`)
      }
    } else {
      console.warn(`Week schedule with id ${weekScheduleId} not found`)
    }
  }

}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
