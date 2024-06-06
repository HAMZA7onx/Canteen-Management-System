import axios from 'axios';

const API_URL = 'http://your-api-url.com/api'; // Replace with your actual API URL

class RoleService {
  getRoles() {
    return axios.get(`${API_URL}/roles`);
  }

  getRole(roleId) {
    return axios.get(`${API_URL}/roles/${roleId}`);
  }

  createRole(roleData) {
    return axios.post(`${API_URL}/roles`, roleData);
  }

  updateRole(roleId, roleData) {
    return axios.put(`${API_URL}/roles/${roleId}`, roleData);
  }

  deleteRole(roleId) {
    return axios.delete(`${API_URL}/roles/${roleId}`);
  }

  getRolePermissions(roleId) {
    return axios.get(`${API_URL}/roles/${roleId}/permissions`);
  }

  assignPermissionToRole(roleId, permissionId) {
    return axios.post(`${API_URL}/roles/${roleId}/permissions/${permissionId}`);
  }

  removePermissionFromRole(roleId, permissionId) {
    return axios.delete(`${API_URL}/roles/${roleId}/permissions/${permissionId}`);
  }
}

export default new RoleService();
