import LogoService from '@/services/logo.service';

const state = {
  currentLogo: null,
};

const getters = {
  currentLogo: (state) => state.currentLogo,
};

const actions = {
  uploadLogo({ commit }, file) {
    return LogoService.uploadLogo(file)
      .then((response) => {
        commit('SET_CURRENT_LOGO', response.data.path);
      })
      .catch((error) => {
        console.error('Error uploading logo:', error);
        throw error;
      });
  },
  fetchLogo({ commit }) {
    return LogoService.getLogo()
      .then((response) => {
        commit('SET_CURRENT_LOGO', response.data.path);
      })
      .catch((error) => {
        console.error('Error fetching logo:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_CURRENT_LOGO(state, logoPath) {
    state.currentLogo = logoPath;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
