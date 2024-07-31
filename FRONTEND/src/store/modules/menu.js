import MenuService from '@/services/menu.service'

const state = {
  menus: []
}

const getters = {
  menus: state => state.menus,
  getMenuById: (state) => (id) => state.menus.find(menu => menu.id === id)
}

const actions = {
  fetchMenus({ commit }) {
    return MenuService.getMenus()
      .then(response => {
        console.log('Menus fetched:', response.data)
        commit('SET_MENUS', response.data)
      })
      .catch(error => {
        console.error('Error fetching menus:', error)
        throw error
      })
  },

  createMenu({ commit }, menu) {
    return MenuService.createMenu(menu)
      .then(response => {
        commit('ADD_MENU', response.data)
      })
      .catch(error => {
        console.error('Error creating menu:', error)
        throw error
      })
  },

  updateMenu({ commit }, menu) {
    return MenuService.updateMenu(menu.id, menu)
      .then(response => {
        commit('UPDATE_MENU', response.data)
      })
      .catch(error => {
        console.error('Error updating menu:', error)
        throw error
      })
  },

  deleteMenu({ commit }, menu) {
    return MenuService.deleteMenu(menu.id)
      .then(() => {
        commit('DELETE_MENU', menu.id)
      })
      .catch(error => {
        console.error('Error deleting menu:', error)
        throw error
      })
  },

  attachFoodComposants({ commit }, { menuId, foodComposantIds }) {
    return MenuService.attachFoodComposants(menuId, foodComposantIds)
      .then(response => {
        commit('UPDATE_MENU', response.data)
        return response.data
      })
  },
    
  detachFoodComposant({ commit }, { menuId, foodComposantId }) {
    return MenuService.detachFoodComposant(menuId, foodComposantId)
      .then(response => {
        commit('UPDATE_MENU', response.data)
        return response.data
      })
  }
}

const mutations = {
  SET_MENUS(state, menus) {
    state.menus = menus
  },
  ADD_MENU(state, menu) {
    state.menus.push(menu)
  },
  UPDATE_MENU(state, updatedMenu) {
    const index = state.menus.findIndex(menu => menu.id === updatedMenu.id)
    if (index !== -1) {
      state.menus.splice(index, 1, updatedMenu)
    }
  },
  DELETE_MENU(state, menuId) {
    state.menus = state.menus.filter(menu => menu.id !== menuId)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
