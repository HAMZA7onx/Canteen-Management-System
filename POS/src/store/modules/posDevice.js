import PosDeviceService from '@/services/posDevice.service';

const state = {
  posDevice: [],
};

const getters = {
  posDevice: (state) => state.posDevice,
};

const actions = {
  fetchPosDevice({ commit }) {
    return PosDeviceService.getPosStatus()
      .then((response) => {
        commit('SET_POS_DEVICE', response.data);
        console.log('POS device fetched:', response.data);
      })
      .catch((error) => {
        console.error('Error fetching POS device:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_POS_DEVICE(state, posDevice) {
    state.posDevice = posDevice;
  },
}; 

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
