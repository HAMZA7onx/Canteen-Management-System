// src/store/modules/printer.js
import PrinterService from '@/services/printer.service';

const state = {
  printers: [],
};

const getters = {
  printers: (state) => state.printers,
};

const actions = {
  fetchPrinters({ commit }) {
    return PrinterService.getPrinters()
      .then((response) => {
        commit('SET_PRINTERS', response.data.data);
        console.log('Printers fetched:', response.data.data);
      })
      .catch((error) => {
        console.error('Error fetching printers:', error);
        throw error;
      });
  },
  createPrinter({ commit }, printer) {
    return PrinterService.createPrinter(printer)
      .then((response) => {
        commit('ADD_PRINTER', response.data.data);
      })
      .catch((error) => {
        console.error('Error creating printer:', error);
        throw error;
      });
  },
  updatePrinter({ commit }, printer) {
    return PrinterService.updatePrinter(printer.id, printer)
      .then((response) => {
        commit('UPDATE_PRINTER', response.data.data);
      })
      .catch((error) => {
        console.error('Error updating printer:', error);
        throw error;
      });
  },
  deletePrinter({ commit }, printerId) {
    return PrinterService.deletePrinter(printerId)
      .then(() => {
        commit('DELETE_PRINTER', printerId);
      })
      .catch((error) => {
        console.error('Error deleting printer:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_PRINTERS(state, printers) {
    state.printers = printers;
  },
  ADD_PRINTER(state, printer) {
    state.printers.push(printer);
  },
  UPDATE_PRINTER(state, updatedPrinter) {
    const index = state.printers.findIndex(printer => printer.id === updatedPrinter.id);
    if (index !== -1) {
      state.printers.splice(index, 1, updatedPrinter);
    }
  },
  DELETE_PRINTER(state, printerId) {
    state.printers = state.printers.filter(printer => printer.id !== printerId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
