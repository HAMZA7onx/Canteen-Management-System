import BadgeService from '@/services/badge.service'

const state = {
  badges: [],
  users: []
}

const getters = {
  badges: state => state.badges,
  users: state => state.users
}

const actions = {
  fetchBadges({ commit }) {
    return BadgeService.getBadges('?with=user')
      .then(response => {
        console.log('Badges:', response.data)
        commit('SET_BADGES', response.data)
      })
      .catch(error => {
        console.error('Error fetching badges:', error)
        throw error
      })
  },
  fetchUsers({ commit }) {
    return BadgeService.getUsers()
      .then(response => {
        console.log('Users:', response.data)
        commit('SET_USERS', response.data)
      })
      .catch(error => {
        console.error('Error fetching users:', error)
        throw error
      })
  },
  createBadge({ commit }, badge) {
    return BadgeService.createBadge(badge)
      .then(() => {
        // Instead of committing the ADD_BADGE mutation,
        // fetch the updated badges from the server
        return BadgeService.getBadges('?with=user')
      })
      .then(response => {
        commit('SET_BADGES', response.data)
      })
      .catch(error => {
        console.error('Error creating badge:', error)
        throw error
      })
  },
  updateBadge({ commit }, badge) {
    return BadgeService.updateBadge(badge.id, badge)
      .then(() => {
        // Instead of committing the UPDATE_BADGE mutation,
        // fetch the updated badges from the server
        return BadgeService.getBadges('?with=user')
      })
      .then(response => {
        commit('SET_BADGES', response.data)
      })
      .catch(error => {
        console.error('Error updating badge:', error.response.data)
        throw error
      })
  },
  deleteBadge({ commit }, id) {
    return BadgeService.deleteBadge(id)
      .then(() => {
        commit('DELETE_BADGE', id)
      })
      .catch(error => {
        console.error('Error deleting badge:', error)
        throw error
      })
  }
}

const mutations = {
  SET_BADGES(state, badges) {
    state.badges = badges
  },
  DELETE_BADGE(state, id) {
    state.badges = state.badges.filter(badge => badge.id !== id)
  },
  SET_USERS(state, users) {
    state.users = users
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
