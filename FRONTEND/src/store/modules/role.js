import RoleService from '@/services/role.service';

const state = {
  roles: [],
  assignedPermissions: [],
  availablePermissions: [],
};

const getters = {
  roles: (state) => state.roles,
  assignedPermissions: (state) => state.assignedPermissions,
  availablePermissions: (state) => state.availablePermissions,
};

const actions = {
  fetchRoles({ commit }) {
    return RoleService.getRoles()
      .then((response) => {
        commit('SET_ROLES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching roles:', error);
        throw error;
      });
  },

  createRole({ commit }, role) {
    return RoleService.createRole(role)
      .then((response) => {
        commit('ADD_ROLE', response.data);
      })
      .catch((error) => {
        console.error('Error creating role:', error);
        throw error;
      });
  },

  updateRole({ commit }, role) {
    return RoleService.updateRole(role.id, role)
      .then((response) => {
        commit('UPDATE_ROLE', response.data);
      })
      .catch((error) => {
        console.error('Error updating role:', error);
        throw error;
      });
  },

  deleteRole({ commit }, roleId) {
    return RoleService.deleteRole(roleId)
      .then(() => {
        commit('DELETE_ROLE', roleId);
      })
      .catch((error) => {
        console.error('Error deleting role:', error);
        throw error;
      });
  },

  fetchRolePermissions({ commit }, roleId) {
    return RoleService.getRolePermissions(roleId)
      .then((response) => {
        commit('SET_ASSIGNED_PERMISSIONS', response.data);
        return response.data;
      })
      .catch((error) => {
        console.error('Error fetching role permissions:', error);
        throw error;
      });
  },

  fetchAvailablePermissions({ commit }) {
    return RoleService.getAvailablePermissions()
      .then((response) => {
        commit('SET_AVAILABLE_PERMISSIONS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching available permissions:', error);
        throw error;
      });
  },

  assignPermission({ commit }, { roleId, permissionId }) {
    return RoleService.assignPermission(roleId, permissionId)
      .then(() => {
        commit('ADD_ASSIGNED_PERMISSION', permissionId);
      })
      .catch((error) => {
        console.error('Error assigning permission:', error);
        throw error;
      });
  },

  removePermission({ commit }, { roleId, permissionId }) {
    return RoleService.removePermission(roleId, permissionId)
      .then(() => {
        commit('REMOVE_ASSIGNED_PERMISSION', permissionId);
      })
      .catch((error) => {
        console.error('Error removing permission:', error);
        throw error;
      });
  },
};

const mutations = {
  SET_ROLES(state, roles) {
    state.roles = roles;
  },
  ADD_ROLE(state, role) {
    state.roles.push(role);
  },
  UPDATE_ROLE(state, updatedRole) {
    state.roles = state.roles.map((role) => (role.id === updatedRole.id ? updatedRole : role));
  },
  DELETE_ROLE(state, roleId) {
    state.roles = state.roles.filter((role) => role.id !== roleId);
  },
  SET_ASSIGNED_PERMISSIONS(state, permissions) {
    state.assignedPermissions = permissions;
  },
  SET_AVAILABLE_PERMISSIONS(state, permissions) {
    state.availablePermissions = permissions;
  },
  ADD_ASSIGNED_PERMISSION(state, permissionId) {
    state.assignedPermissions.push({ id: permissionId });
  },
  REMOVE_ASSIGNED_PERMISSION(state, permissionId) {
    state.assignedPermissions = state.assignedPermissions.filter((permission) => permission.id !== permissionId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
