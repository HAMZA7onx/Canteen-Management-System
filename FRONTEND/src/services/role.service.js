import request from '@/utils/request';

import { API_URL } from '@/config/config';

class RoleService {
  getRoles() {
    return request.get(`${API_URL}/roles`);
  }

  getRole(id) {
    return request.get(`${API_URL}/roles/${id}`);
  }

  createRole(role) {
    return request.post(`${API_URL}/roles`, role)
      .catch(error => {
        if (error.response && error.response.status === 422) {
          throw error.response;
        }
        throw error;
      });
  }

  updateRole(id, role) {
    return request.put(`${API_URL}/roles/${id}`, role)
      .catch(error => {
        if (error.response && error.response.status === 422) {
          throw error.response;
        }
        throw error;
      });
  }

  deleteRole(id) {
    return request.delete(`${API_URL}/roles/${id}`);
  }

  getRolePermissions(roleId) {
    return request.get(`${API_URL}/roles/${roleId}/permissions`);
  }

  getAvailablePermissions() {
    return request.get(`${API_URL}/permissions`);
  }

  assignPermissions(roleId, permissionIds) {
    return request.post(`${API_URL}/roles/${roleId}/permissions`, { permissionIds });
  }  

  removePermission(roleId, permissionId) {
    return request.delete(`${API_URL}/roles/${roleId}/permissions/${permissionId}`);
  }
}

export default new RoleService();
