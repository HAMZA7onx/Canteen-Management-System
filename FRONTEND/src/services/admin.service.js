import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api';

class AdminService {
  getAdmins() {
    return axios.get(`${API_URL}/admins`);
  }

  getAdmin(id) {
    return axios.get(`${API_URL}/admins/${id}`);
  }

  createAdmin(admin) {
    return axios.post(`${API_URL}/admins`, admin);
  }

  updateAdmin(id, admin) {
    return axios.put(`${API_URL}/admins/${id}`, admin);
  }

  deleteAdmin(id) {
    return axios.delete(`${API_URL}/admins/${id}`);
  }

  getAdminRoles(adminId) {
    return axios.get(`${API_URL}/admins/${adminId}/roles`);
  }

  getAdminPermissions(adminId) {
    return axios.get(`${API_URL}/admins/${adminId}/permissions`);
  }

  assignRole(adminId, roleId) {
    return axios.post(`${API_URL}/admins/${adminId}/roles/${roleId}`);
  }

  removeRole(adminId, roleId) {
    return axios.delete(`${API_URL}/admins/${adminId}/roles/${roleId}`);
  }

  assignPermission(adminId, permissionId) {
    return axios.post(`${API_URL}/admins/${adminId}/permissions/${permissionId}`);
  }

  removePermission(adminId, permissionId) {
    return axios.delete(`${API_URL}/admins/${adminId}/permissions/${permissionId}`);
  }

  getAvailableRoles() {
    return axios.get(`${API_URL}/roles`);
  }

  getAvailablePermissions() {
    return axios.get(`${API_URL}/permissions`);
  }
}

export default new AdminService();