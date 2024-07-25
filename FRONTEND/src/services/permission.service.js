import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class PermissionService {
  refreshPermissions() {
    return request.get(`${API_URL}/permissions/refresh-permissions`);
  }
}

export default new PermissionService();