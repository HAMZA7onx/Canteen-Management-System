import WeekScheduleService from '@/services/weekSchedule.service'

const state = {
  weekSchedules: [],
}

const getters = {
  weekSchedules: (state) => state.weekSchedules,
  getAssignedDailyMealsForDay: (state) => (weekScheduleId, day) => {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    const assignedDailyMealsData = weekSchedule ? weekSchedule[`${day}_daily_meals`] : []
    const assignedDailyMeals = assignedDailyMealsData.map((dailyMealData) => ({
      daily_meal_id: dailyMealData.id,
      start_time: dailyMealData.pivot.start_time,
      end_time: dailyMealData.pivot.end_time,
      price: dailyMealData.pivot.price,
    }))
    console.log(`Assigned daily meals for weekScheduleId ${weekScheduleId} and day ${day}:`, assignedDailyMeals)
    return assignedDailyMeals
  },
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

  createWeekSchedule({ commit }, weekScheduleData) {
    console.log('weekScheduleData', weekScheduleData)
    return WeekScheduleService.createWeekSchedule(weekScheduleData)
      .then((response) => {
        commit('ADD_WEEK_SCHEDULE', response.data)
      })
      .catch((error) => {
        console.error('Error creating week schedule:', error)
        throw error
      })
  },
  updateWeekSchedule({ commit }, { id, data }) {
    return WeekScheduleService.updateWeekSchedule(id, data)
      .then((response) => {
        commit('UPDATE_WEEK_SCHEDULE', response.data)
      })
      .catch((error) => {
        console.error('Error updating week schedule:', error)
        throw error
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
}

const mutations = {
  SET_WEEK_SCHEDULES(state, weekSchedules) {
    state.weekSchedules = weekSchedules
  },

  ADD_WEEK_SCHEDULE(state, newWeekSchedule) {
    state.weekSchedules.push(newWeekSchedule)
  },

  UPDATE_WEEK_SCHEDULE(state, updatedWeekSchedule) {
    state.weekSchedules = state.weekSchedules.map((weekSchedule) =>
      weekSchedule.id === updatedWeekSchedule.id ? updatedWeekSchedule : weekSchedule
    )
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
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
