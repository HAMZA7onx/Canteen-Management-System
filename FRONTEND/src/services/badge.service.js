import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class BadgeService {
  getAll() {
    return request.get(`${API_URL}/badges`);
  }

  get(id) {
    return request.get(`${API_URL}/badges/${id}`);
  }

  create(badge) {
    return request.post(`${API_URL}/badges`, badge);
  }

  update(id, badge) {
    return request.put(`${API_URL}/badges/${id}`, badge);
  }

  delete(id) {
    return request.delete(`${API_URL}/badges/${id}`);
  }

  importRfids(formData) {
    return request.post(`${API_URL}/badges/import`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  }

  getUsersWithAllRfidsLost() {
    return request.get(`${API_URL}/users/all-rfids-lost`);
  }

  getUsersWithoutRfids() {
    return request.get(`${API_URL}/users/without-rfids`);
  }

  updateBadgeStatus(badgeId, status) {
    return request.put(`${API_URL}/badges/${badgeId}/status`, { status });
  }

  assignRfidToUser(badgeId, userId) {
    return request.put(`${API_URL}/badges/${badgeId}/assign`, { userId });
  }
}

export default new BadgeService();
