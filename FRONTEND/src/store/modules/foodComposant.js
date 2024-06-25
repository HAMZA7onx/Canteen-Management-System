import FoodComposantService from '@/services/foodComposant.service'

const state = {
  foodComposants: []
}

const getters = {
  foodComposants: state => state.foodComposants
}

const actions = {
  fetchFoodComposants({ commit }) {
    return FoodComposantService.getFoodComposants()
      .then(response => {
        commit('SET_FOOD_COMPOSANTS', response.data)
      })
      .catch(error => {
        console.error('Error fetching food composants:', error)
        throw error
      })
  },

  createFoodComposant({ commit }, data) {
    return FoodComposantService.createFoodComposant(data)
      .then(response => {
        commit('ADD_FOOD_COMPOSANT', response.data)
      })
      .catch(error => {
        console.error('Error creating food composant:', error)
        throw error
      })
  },

  updateFoodComposant({ commit }, data) {
    return FoodComposantService.updateFoodComposant(data.id, data)
      .then(response => {
        commit('UPDATE_FOOD_COMPOSANT', response.data)
      })
      .catch(error => {
        console.error('Error updating food composant:', error)
        throw error
      })
  },

  deleteFoodComposant({ commit }, foodComposant) {
    console.log('item to delete:', foodComposant);
    return FoodComposantService.deleteFoodComposant(foodComposant.id)
      .then(() => {
        commit('DELETE_FOOD_COMPOSANT', foodComposant.id)
      })
      .catch(error => {
        console.error('Error deleting food composant:', error)
        throw error
      })
  },
  

  attachToMenu({ commit }, { foodComposantId, menuId }) {
    return FoodComposantService.attachToMenu(foodComposantId, menuId)
      .then(response => {
        // Optionally commit a mutation to update the state
      })
      .catch(error => {
        console.error('Error attaching food composant to menu:', error)
        throw error
      })
  },

  detachFromMenu({ commit }, { foodComposantId, menuId }) {
    return FoodComposantService.detachFromMenu(foodComposantId, menuId)
      .then(response => {
        // Optionally commit a mutation to update the state
      })
      .catch(error => {
        console.error('Error detaching food composant from menu:', error)
        throw error
      })
  }
}

const mutations = {
  SET_FOOD_COMPOSANTS(state, foodComposants) {
    state.foodComposants = foodComposants
  },

  ADD_FOOD_COMPOSANT(state, foodComposant) {
    state.foodComposants.push(foodComposant)
  },

  UPDATE_FOOD_COMPOSANT(state, updatedFoodComposant) {
    state.foodComposants = state.foodComposants.map(foodComposant =>
      foodComposant.id === updatedFoodComposant.id ? updatedFoodComposant : foodComposant
    )
  },

  DELETE_FOOD_COMPOSANT(state, id) {
    state.foodComposants = state.foodComposants.filter(foodComposant => foodComposant.id !== id)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
