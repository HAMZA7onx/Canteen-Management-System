import request from '@/utils/request';

import { API_URL } from '@/config/config';

class BadgeService {
  getAll() {
    return request.get(`${API_URL}/admins-badges`);
  }

  get(id) {
    return request.get(`${API_URL}/admins-badges/${id}`);
  }

  create(badge) {
    return request.post(`${API_URL}/admins-badges`, badge);
  }

  update(id, badge) {
    return request.put(`${API_URL}/admins-badges/${id}`, badge);
  }

  delete(id) {
    return request.delete(`${API_URL}/admins-badges/${id}`);
  }

  importRfids(formData) {
    return request.post(`${API_URL}/admins-badges/import`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    }); 
  }

  getUsersWithAllRfidsLost() {
    return request.get(`${API_URL}/admins-badges/admins/all-rfids-lost`);
  }

  getUsersWithoutRfids() {
    return request.get(`${API_URL}/admins-badges/admins/without-rfids`);
  }

  updateBadgeStatus(badgeId, status) {
    return request.put(`${API_URL}/admins-badges/${badgeId}/status`, { status });
  }

  assignRfidToUser(badgeId, userId) {
    return request.put(`${API_URL}/admins-badges/${badgeId}/assign`, { userId });
  }
}

export default new BadgeService();
