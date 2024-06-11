import PermissionService from '@/services/permission.service';

const state = {
  permissions: [],
};

const getters = {
  permissions: (state) => state.permissions,
};

const actions = {
  fetchPermissions({ commit }) {
    return PermissionService.getPermissions()
      .then((response) => {
        commit('SET_PERMISSIONS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching permissions:', error);
        throw error;
      });
  },

  createPermission({ commit }, permission) {
    return PermissionService.createPermission(permission)
      .then((response) => {
        console.log('ADD_PERMISSION: ', response.data);
        commit('ADD_PERMISSION', response.data);
      })
      .catch((error) => {
        console.error('Error creating permission:', error);
        throw error;
      });
  },

  updatePermission({ commit }, permission) {
    return PermissionService.updatePermission(permission.id, permission)
      .then((response) => {
        commit('UPDATE_PERMISSION', response.data);
      })
      .catch((error) => {
        console.error('Error updating permission:', error);
        throw error;
      });
  },

  deletePermission({ commit }, permissionId) {
    return PermissionService.deletePermission(permissionId)
      .then(() => {
        commit('DELETE_PERMISSION', permissionId);
      })
      .catch((error) => {
        console.error('Error deleting permission:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_PERMISSIONS(state, permissions) {
    state.permissions = permissions;
  },
  ADD_PERMISSION(state, permission) {
    state.permissions.push(permission);
  },
  UPDATE_PERMISSION(state, updatedPermission) {
    state.permissions = state.permissions.map((permission) => (permission.id === updatedPermission.id ? updatedPermission : permission));
  },
  DELETE_PERMISSION(state, permissionId) {
    console.log(state, permissionId);
    state.permissions = state.permissions.filter((permission) => permission.id !== permissionId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};