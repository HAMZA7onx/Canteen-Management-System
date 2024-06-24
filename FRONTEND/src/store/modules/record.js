import RecordService from '@/services/record.service';

const state = {
  mealRecords: [],
};

const getters = {
  mealRecords: (state) => state.mealRecords,
};

const actions = {
  fetchMealRecords({ commit }) {
    return RecordService.getAll()
      .then((response) => {
        console.log('response', response.data);
        commit('SET_MEAL_RECORDS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching meal records:', error);
        throw error;
      });
  },

  createMealRecord({ commit }, mealRecord) {
    return RecordService.create(mealRecord)
      .then((response) => {
        commit('ADD_MEAL_RECORD', response.data);
      })
      .catch((error) => {
        console.error('Error creating meal record:', error);
        throw error;
      });
  },

  updateMealRecord({ commit }, { id, ...mealRecord }) {
    return RecordService.update(id, mealRecord)
      .then((response) => {
        commit('UPDATE_MEAL_RECORD', response.data);
      })
      .catch((error) => {
        console.error('Error updating meal record:', error);
        throw error;
      });
  },

  deleteMealRecord({ commit }, mealRecordId) {
    return RecordService.delete(mealRecordId)
      .then(() => {
        commit('DELETE_MEAL_RECORD', mealRecordId);
      })
      .catch((error) => {
        console.error('Error deleting meal record:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_MEAL_RECORDS(state, mealRecords) {
    state.mealRecords = mealRecords;
  },

  ADD_MEAL_RECORD(state, mealRecord) {
    state.mealRecords.push(mealRecord);
  },

  UPDATE_MEAL_RECORD(state, updatedMealRecord) {
    const index = state.mealRecords.findIndex((record) => record.id === updatedMealRecord.id);
    if (index !== -1) {
      state.mealRecords.splice(index, 1, updatedMealRecord);
    }
  },

  DELETE_MEAL_RECORD(state, mealRecordId) {
    const index = state.mealRecords.findIndex((record) => record.id === mealRecordId);
    if (index !== -1) {
      state.mealRecords.splice(index, 1);
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
