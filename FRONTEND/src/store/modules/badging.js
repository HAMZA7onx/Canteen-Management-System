// src/store/modules/badging.js
import BadgingService from '@/services/badging.service'

const state = {}

const getters = {}

const actions = {
  scanBadge({ commit }, { rfid, day }) {
    return BadgingService.scanBadge(rfid, day)
      .then((response) => {
        // You can add any additional logic here if needed
        return response.data
      })
      .catch((error) => {
        console.error('Error scanning badge:', error)
        throw error
      })
  }
}

const mutations = {}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
