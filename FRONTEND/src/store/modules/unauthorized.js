const state = {
    requiredPermission: null,
  };
  
  const getters = {
    requiredPermission: (state) => state.requiredPermission,
  };
  
  const mutations = {
    setRequiredPermission(state, permission) {
      state.requiredPermission = permission;
    },
  };
  
  export default {
    namespaced: true,
    state,
    getters,
    mutations,
  };
  