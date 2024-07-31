import request from '@/utils/request';

import { API_URL } from '@/config/config';

class PermissionService {
  refreshPermissions() {
    return request.get(`${API_URL}/permissions/refresh-permissions`);
  }
}

export default new PermissionService();