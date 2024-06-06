import RoleService from '@/services/role.service';

const state = {
  roles: [],
  role: null,
  rolePermissions: [],
};

const getters = {
  roles: (state) => state.roles,
  role: (state) => state.role,
  rolePermissions: (state) => state.rolePermissions,
};

const actions = {
  async fetchRoles({ commit }) {
    try {
      const response = await RoleService.getRoles();
      commit('setRoles', response.data);
    } catch (error) {
      console.error('Error fetching roles:', error);
    }
  },

  async fetchRole({ commit }, roleId) {
    try {
      const response = await RoleService.getRole(roleId);
      commit('setRole', response.data);
    } catch (error) {
      console.error('Error fetching role:', error);
    }
  },

  async createRole({ commit }, roleData) {
    try {
      const response = await RoleService.createRole(roleData);
      commit('addRole', response.data);
    } catch (error) {
      console.error('Error creating role:', error);
    }
  },

  async updateRole({ commit }, { roleId, roleData }) {
    try {
      const response = await RoleService.updateRole(roleId, roleData);
      commit('updateRole', response.data);
    } catch (error) {
      console.error('Error updating role:', error);
    }
  },

  async deleteRole({ commit }, roleId) {
    try {
      await RoleService.deleteRole(roleId);
      commit('removeRole', roleId);
    } catch (error) {
      console.error('Error deleting role:', error);
    }
  },

  async fetchRolePermissions({ commit }, roleId) {
    try {
      const response = await RoleService.getRolePermissions(roleId);
      commit('setRolePermissions', response.data);
    } catch (error) {
      console.error('Error fetching role permissions:', error);
    }
  },

  async assignPermissionToRole({ commit }, { roleId, permissionId }) {
    try {
      await RoleService.assignPermissionToRole(roleId, permissionId);
      commit('addPermissionToRole', permissionId);
    } catch (error) {
      console.error('Error assigning permission to role:', error);
    }
  },

  async removePermissionFromRole({ commit }, { roleId, permissionId }) {
    try {
      await RoleService.removePermissionFromRole(roleId, permissionId);
      commit('removePermissionFromRole', permissionId);
    } catch (error) {
      console.error('Error removing permission from role:', error);
    }
  },
};

const mutations = {
  setRoles(state, roles) {
    state.roles = roles;
  },
  setRole(state, role) {
    state.role = role;
  },
  addRole(state, role) {
    state.roles.push(role);
  },
  updateRole(state, updatedRole) {
    const index = state.roles.findIndex((role) => role.id === updatedRole.id);
    if (index !== -1) {
      state.roles.splice(index, 1, updatedRole);
    }
  },
  removeRole(state, roleId) {
    state.roles = state.roles.filter((role) => role.id !== roleId);
  },
  setRolePermissions(state, permissions) {
    state.rolePermissions = permissions;
  },
  addPermissionToRole(state, permissionId) {
    state.rolePermissions.push(permissionId);
  },
  removePermissionFromRole(state, permissionId) {
    state.rolePermissions = state.rolePermissions.filter((id) => id !== permissionId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
