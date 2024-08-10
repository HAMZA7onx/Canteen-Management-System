import WeekScheduleService from '@/services/weekSchedule.service'

const state = {
  weekSchedules: [],
}

const getters = {
  weekSchedules: (state) => state.weekSchedules,
  getAssignedMenusForDay: (state) => (weekScheduleId, day) => {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    const assignedMenusData = weekSchedule ? weekSchedule[`${day}_menus`] || [] : []
    return assignedMenusData.map((menuData) => ({
      menu_id: menuData.id,
      meal_name: menuData.pivot?.meal_name,
      start_time: menuData.pivot?.start_time,
      end_time: menuData.pivot?.end_time,
      price: menuData.pivot?.price,
      discounts: menuData.discounts || {}
    }))
  },  
  activeWeekSchedule: (state) => state.weekSchedules.find(ws => ws.status === 'active'),
}

const actions = {
  fetchWeekSchedules({ commit }) {
    return WeekScheduleService.getWeekSchedules()
      .then((response) => {
        console.log('weekSchedules', response.data)
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
      .finally(() => {
        return dispatch('fetchWeekSchedules')
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

  assignMenu({ commit }, { weekScheduleId, day, menuData }) {
    console.log('assignMenu', weekScheduleId, day, menuData)
    return WeekScheduleService.assignMenu(weekScheduleId, day, menuData)
      .then((response) => {
        commit('ASSIGN_MENU', { weekScheduleId, day, menuData: response.data })
      })
      .catch((error) => {
        console.error('Error assigning menu:', error)
        throw error
      })
  },

  detachMenu({ commit }, { weekScheduleId, day, menuId }) {
    return WeekScheduleService.detachMenu(weekScheduleId, day, menuId)
      .then(() => {
        commit('DETACH_MENU', { weekScheduleId, day, menuId })
      })
      .catch((error) => {
        console.error('Error detaching menu:', error)
        throw error
      })
  },

  fetchDiscountsForMenu({ commit }, { weekScheduleId, day, menuId }) {
    return new Promise((resolve, reject) => {
      WeekScheduleService.getMenuDiscounts(weekScheduleId, day, menuId)
        .then(response => {
          console.log('DISCOUNTS DATA: ', response)
          const discounts = response.data
          console.log('Discounts received from API:', discounts)
          commit('SET_DISCOUNTS_FOR_MENU', { weekScheduleId, day, menuId, discounts })
          resolve(discounts)
        })
        .catch(error => {
          console.error('Error in fetchDiscountsForMenu:', error)
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

 
  ASSIGN_MENU(state, { weekScheduleId, day, menuData }) {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    if (weekSchedule) {
      const existingMenus = weekSchedule[`${day}_menus`] || []
      weekSchedule[`${day}_menus`] = [...existingMenus, menuData]
    }
  },
  
  DETACH_MENU(state, { weekScheduleId, day, menuId }) {
    const weekSchedule = state.weekSchedules.find((ws) => ws.id === weekScheduleId)
    if (weekSchedule) {
      const menusArray = weekSchedule[`${day}_menus`]
      if (menusArray) {
        weekSchedule[`${day}_menus`] = menusArray.filter(
          (menu) => menu.menu_id !== menuId
        )
      }
    }
  },
  

  SET_DISCOUNTS_FOR_MENU(state, { weekScheduleId, day, menuId, discounts }) {
    const weekSchedule = state.weekSchedules.find(ws => ws.id === weekScheduleId)
    if (weekSchedule) {
      const menus = weekSchedule[`${day}_menus`]  // Changed from `${day}Menus`
      if (menus) {
        const menu = menus.find(m => m.id === menuId)
        if (menu) {
          menu.discounts = discounts
          // Trigger reactivity
          state.weekSchedules = [...state.weekSchedules]
        } else {
          console.warn(`Menu with id ${menuId} not found for ${day}`)
        }
      } else {
        console.warn(`No menus found for ${day}`)
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
