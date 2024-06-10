import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class PermissionService {
  getPermissions() {
    return request.get(`${API_URL}/permissions`);
  }

  getPermission(id) {
    return request.get(`${API_URL}/permissions/${id}`);
  }

  createPermission(permission) {
    return request.post(`${API_URL}/permissions`, permission);
  }

  updatePermission(id, permission) {
    return request.put(`${API_URL}/permissions/${id}`, permission);
  }

  deletePermission(id) {
    return request.delete(`${API_URL}/permissions/${id}`);
  }
}

export default new PermissionService();
