import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api';

class AdminService {
  getAdmins() {
    return axios.get(`${API_URL}/admins`);
  }

  getAdminRoles(adminId) {
    return axios.get(`${API_URL}/admins/${adminId}/roles`);
  }

  getAdminPermissions(adminId) {
    return axios.get(`${API_URL}/admins/${adminId}/permissions`);
  }
}

export default new AdminService();
