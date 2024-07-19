import AdvancedRecordService from '@/services/advancedRecord.service';

const state = {
  advancedRecords: [],
};

const getters = {
  advancedRecords: (state) => state.advancedRecords,
};

const actions = {
  async fetchAdvancedRecords({ commit }, filters) {
    console.log('filters: ', filters);
    try {
      const response = await AdvancedRecordService.fetchAdvancedRecords(filters);
      console.log('Advanced records fetched:', response.data);
      commit('SET_ADVANCED_RECORDS', response.data);
      return response;
    } catch (error) {
      console.error('Error fetching advanced records:', error);
      throw error;
    } 
  },
};

const mutations = {
  SET_ADVANCED_RECORDS(state, records) {
    state.advancedRecords = records;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
