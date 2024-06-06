import PermissionService from '@/services/permission.service';

const state = {
  permissions: [],
  permission: null,
};

const getters = {
  permissions: (state) => state.permissions,
  permission: (state) => state.permission,
};

const actions = {
  async fetchPermissions({ commit }) {
    try {
      const response = await PermissionService.getPermissions();
      commit('setPermissions', response.data);
    } catch (error) {
      console.error('Error fetching permissions:', error);
    }
  },

  async fetchPermission({ commit }, permissionId) {
    try {
      const response = await PermissionService.getPermission(permissionId);
      commit('setPermission', response.data);
    } catch (error) {
      console.error('Error fetching permission:', error);
    }
  },

  async createPermission({ commit }, permissionData) {
    try {
      const response = await PermissionService.createPermission(permissionData);
      commit('addPermission', response.data);
    } catch (error) {
      console.error('Error creating permission:', error);
    }
  },

  async updatePermission({ commit }, { permissionId, permissionData }) {
    try {
      const response = await PermissionService.updatePermission(permissionId, permissionData);
      commit('updatePermission', response.data);
    } catch (error) {
      console.error('Error updating permission:', error);
    }
  },

  async deletePermission({ commit }, permissionId) {
    try {
      await PermissionService.deletePermission(permissionId);
      commit('removePermission', permissionId);
    } catch (error) {
      console.error('Error deleting permission:', error);
    }
  },
};

const mutations = {
  setPermissions(state, permissions) {
    state.permissions = permissions;
  },
  setPermission(state, permission) {
    state.permission = permission;
  },
  addPermission(state, permission) {
    state.permissions.push(permission);
  },
  updatePermission(state, updatedPermission) {
    const index = state.permissions.findIndex((permission) => permission.id === updatedPermission.id);
    if (index !== -1) {
      state.permissions.splice(index, 1, updatedPermission);
    }
  },
  removePermission(state, permissionId) {
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
