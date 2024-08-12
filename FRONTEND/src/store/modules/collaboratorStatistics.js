// src/store/modules/collaboratorStatistics.js

import CollaboratorStatisticsService from '@/services/collaboratorStatistics.service';

const state = {
  statistics: {},
  isLoading: false,
  error: null,
};

const getters = {
  statistics: (state) => state.statistics,
  isLoading: (state) => state.isLoading,
  error: (state) => state.error,
};

const actions = {
  async fetchStatistics({ commit }, { startDate, endDate }) {
    console.log('etchStatistics called with startDate:', startDate, 'and endDate:', endDate);
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    try {
      const response = await CollaboratorStatisticsService.getStatistics(startDate, endDate);
      commit('SET_STATISTICS', response.data);
    } catch (error) {
      commit('SET_ERROR', error.response?.data?.message || 'An error occurred while fetching statistics');
    } finally {
      commit('SET_LOADING', false);
    }
  },
};

const mutations = {
  SET_STATISTICS(state, statistics) {
    state.statistics = statistics;
  },
  SET_LOADING(state, isLoading) {
    state.isLoading = isLoading;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
