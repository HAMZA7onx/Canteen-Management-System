import axios from 'axios';

const API_URL = 'http://your-api-url.com/api'; // Replace with your actual API URL

class PermissionService {
  getPermissions() {
    return axios.get(`${API_URL}/permissions`);
  }

  getPermission(permissionId) {
    return axios.get(`${API_URL}/permissions/${permissionId}`);
  }

  createPermission(permissionData) {
    return axios.post(`${API_URL}/permissions`, permissionData);
  }

  updatePermission(permissionId, permissionData) {
    return axios.put(`${API_URL}/permissions/${permissionId}`, permissionData);
  }

  deletePermission(permissionId) {
    return axios.delete(`${API_URL}/permissions/${permissionId}`);
  }
}

export default new PermissionService();
