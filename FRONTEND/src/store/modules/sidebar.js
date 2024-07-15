export default {
    namespaced: true,
    state: {
      isOpen: true
    },
    mutations: {
      toggleSidebar(state) {
        state.isOpen = !state.isOpen;
      }
    },
    actions: {
      toggleSidebar({ commit }) {
        commit('toggleSidebar');
      }
    },
    getters: {
      isOpen: state => state.isOpen
    }
  };
  