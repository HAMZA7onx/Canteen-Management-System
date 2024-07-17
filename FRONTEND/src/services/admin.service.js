import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class AdminService {
  getAdmins() {
    return request.get(`${API_URL}/admins`);
  }

  getAdmin(id) {
    return request.get(`${API_URL}/admins/${id}`);
  }

  createAdmin(admin) {
    return request.post(`${API_URL}/admins`, admin);
  }

  updateAdmin(id, admin) {
    return request.put(`${API_URL}/admins/${id}`, admin);
  }

  deleteAdmin(id) {
    return request.delete(`${API_URL}/admins/${id}`);
  }

  getAdminRoles(adminId) {
    return request.get(`${API_URL}/admins/${adminId}/roles`);
  }

  getAdminPermissions(adminId) {
    return request.get(`${API_URL}/admins/${adminId}/permissions`);
  }

  getAvailableRoles() {
    return request.get(`${API_URL}/roles`);
  }

  getAvailablePermissions() {
    return request.get(`${API_URL}/permissions`);
  }

  assignRole(adminId, roleId) {
    return request.post(`${API_URL}/admins/${adminId}/roles/${roleId}`);
  }

  removeRole(adminId, roleId) {
    return request.delete(`${API_URL}/admins/${adminId}/roles/${roleId}`);
  }

  assignPermission(adminId, permissionId) {
    return request.post(`${API_URL}/admins/${adminId}/permissions/${permissionId}`);
  }

  removePermission(adminId, permissionId) {
    return request.delete(`${API_URL}/admins/${adminId}/permissions/${permissionId}`);
  }
}

export default new AdminService();
 