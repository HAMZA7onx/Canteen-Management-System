import PosDeviceService from '@/services/posDevice.service';

const state = {
  posDevices: [],
};

const getters = {
  posDevices: (state) => state.posDevices,
};

const actions = {
  fetchPosDevices({ commit }) {
    return PosDeviceService.getPosDevices()
      .then((response) => {
        commit('SET_POS_DEVICES', response.data);
        console.log('POS devices fetched:', response.data);
      })
      .catch((error) => {
        console.error('Error fetching POS devices:', error);
        throw error;
      });
  },
  createPosDevice({ commit }, posDevice) {
    return PosDeviceService.createPosDevice(posDevice)
      .then((response) => {
        commit('ADD_POS_DEVICE', response.data);
      })
      .catch((error) => {
        console.error('Error creating POS device:', error);
        throw error;
      });
  },
  updatePosDevice({ commit }, posDevice) {
    return PosDeviceService.updatePosDevice(posDevice.id, posDevice)
      .then((response) => {
        commit('UPDATE_POS_DEVICE', response.data);
      })
      .catch((error) => {
        console.error('Error updating POS device:', error);
        throw error;
      });
  },
  deletePosDevice({ commit }, posDeviceId) {
    return PosDeviceService.deletePosDevice(posDeviceId)
      .then(() => {
        commit('DELETE_POS_DEVICE', posDeviceId);
      })
      .catch((error) => {
        console.error('Error deleting POS device:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_POS_DEVICES(state, posDevices) {
    state.posDevices = posDevices;
  },
  ADD_POS_DEVICE(state, posDevice) {
    state.posDevices.push(posDevice);
  },
  UPDATE_POS_DEVICE(state, updatedPosDevice) {
    const index = state.posDevices.findIndex(device => device.id === updatedPosDevice.id);
    if (index !== -1) {
      state.posDevices.splice(index, 1, updatedPosDevice);
    }
  },
  DELETE_POS_DEVICE(state, posDeviceId) {
    state.posDevices = state.posDevices.filter(device => device.id !== posDeviceId);
  },
}; 

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
