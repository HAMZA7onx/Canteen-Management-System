import AdminService from '@/services/admin.service';
 

const state = {
  admins: [],
  assignedRoles: [],
  assignedPermissions: [],
  availableRoles: [],
  availablePermissions: [],
};

const getters = {
  admins: (state) => state.admins,
  assignedRoles: (state) => state.assignedRoles,
  assignedPermissions: (state) => state.assignedPermissions,
  availableRoles: (state) => state.availableRoles,
  availablePermissions: (state) => state.availablePermissions,
};

const actions = {
  fetchAdmins({ commit }) {
    return AdminService.getAdmins()
      .then((response) => {
        commit('SET_ADMINS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching admins:', error);
        throw error;
      });
  },

  createAdmin({ commit }, admin) {
    return AdminService.createAdmin(admin)
      .then((response) => {
        commit('ADD_ADMIN', response.data);
        return response.data;
      })
      .catch((error) => {
        if (error.response && error.response.data && error.response.data.errors) {
          throw error.response.data;
        }
        throw error;
      });
  },
  

  updateAdmin({ commit }, admin) {
    return AdminService.updateAdmin(admin.id, admin)
      .then((response) => {
        commit('UPDATE_ADMIN', response.data);
        return response.data;
      })
      .catch((error) => {
        if (error.response && error.response.status === 422) {
          throw error.response.data;
        }
        console.error('Error updating admin:', error);
        throw error;
      });
  },

  deleteAdmin({ commit }, adminId) {
    return AdminService.deleteAdmin(adminId)
      .then(() => {
        commit('DELETE_ADMIN', adminId);
      })
      .catch((error) => {
        console.error('Error deleting admin:', error);
        throw error;
      });
  },

  fetchAdminRoles({ commit }, adminId) {
    return AdminService.getAdminRoles(adminId)
      .then((response) => {
        commit('SET_ASSIGNED_ROLES', response.data);
        return response.data; // Return the fetched data
      })
      .catch((error) => {
        console.error('Error fetching admin roles:', error);
        throw error;
      });
  },

  fetchAdminPermissions({ commit }, adminId) {
    return AdminService.getAdminPermissions(adminId)
      .then((response) => {
        commit('SET_ASSIGNED_PERMISSIONS', response.data);
        return response.data; // Return the fetched data
      })
      .catch((error) => {
        console.error('Error fetching admin permissions:', error);
        throw error;
      });
  },

  fetchAvailableRoles({ commit }) {
    return AdminService.getAvailableRoles()
      .then((response) => {
        
        commit('SET_AVAILABLE_ROLES', response.data);
      })
      .catch((error) => {
        console.error('Error fetching available roles:', error);
        throw error;
      });
  },

  fetchAvailablePermissions({ commit }) {
    return AdminService.getAvailablePermissions()
      .then((response) => {
        commit('SET_AVAILABLE_PERMISSIONS', response.data);
      })
      .catch((error) => {
        console.error('Error fetching available permissions:', error);
        throw error;
      });
  },

  assignRole({ commit }, { adminId, roleId }) {
    return AdminService.assignRole(adminId, roleId)
      .then(() => {
        commit('ADD_ASSIGNED_ROLE', roleId);
      })
      .catch((error) => {
        console.error('Error assigning role:', error);
        throw error;
      });
  },

  removeRole({ commit }, { adminId, roleId }) {
    return AdminService.removeRole(adminId, roleId)
      .then(() => {
        commit('REMOVE_ASSIGNED_ROLE', roleId);
      })
      .catch((error) => {
        console.error('Error removing role:', error);
        throw error;
      });
  },

  assignPermission({ commit }, { adminId, permissionId }) {
    return AdminService.assignPermission(adminId, permissionId)
      .then(() => {
        commit('ADD_ASSIGNED_PERMISSION', permissionId);
      })
      .catch((error) => {
        console.error('Error assigning permission:', error);
        throw error;
      });
  },

  removePermission({ commit }, { adminId, permissionId }) {
    return AdminService.removePermission(adminId, permissionId)
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
  SET_ADMINS(state, admins) {
    state.admins = admins;
  },
  ADD_ADMIN(state, admin) {
    state.admins.push(admin);
  },
  UPDATE_ADMIN(state, updatedAdmin) {
    state.admins = state.admins.map((admin) => (admin.id === updatedAdmin.id ? updatedAdmin : admin));
  },
  DELETE_ADMIN(state, adminId) {
    state.admins = state.admins.filter((admin) => admin.id !== adminId);
  },
  SET_ASSIGNED_ROLES(state, roles) {
    state.assignedRoles = roles;
  },
  SET_ASSIGNED_PERMISSIONS(state, permissions) {
    state.assignedPermissions = permissions;
  },
  SET_AVAILABLE_ROLES(state, roles) {
    state.availableRoles = roles;
  },
  SET_AVAILABLE_PERMISSIONS(state, permissions) {
    state.availablePermissions = permissions;
  },
  ADD_ASSIGNED_ROLE(state, roleId) {
    state.assignedRoles.push({ id: roleId });
  },
  REMOVE_ASSIGNED_ROLE(state, roleId) {
    state.assignedRoles = state.assignedRoles.filter((role) => role.id !== roleId);
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
