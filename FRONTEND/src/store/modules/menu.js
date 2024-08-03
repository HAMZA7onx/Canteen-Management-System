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
        commit('SET_MENUS', response.data)
      })
      .catch(error => {
        console.error('Error fetching menus:', error)
        throw error
      })
  },

  createMenu({ commit }, menuData) {
    return MenuService.createMenu(menuData)
      .then(response => {
        commit('ADD_MENU', response.data)
        return response.data
      })
  },

  updateMenu({ commit }, { id, menuData }) {
    return MenuService.updateMenu(id, menuData)
      .then(response => {
        commit('UPDATE_MENU', response.data)
        return response.data
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
