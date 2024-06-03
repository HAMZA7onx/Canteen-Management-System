import apiClient from './api.service';

class AdminService {
  getAdmins() {
    return apiClient.get('/admins');
  }

  createAdmin(data) {
    return apiClient.post('/admins', data);
  }

  // Add other methods for updating, deleting, and managing admin roles/permissions
}

export default new AdminService();
