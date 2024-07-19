import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class AdminReportSubscriptionService {
  getSubscriptions() {
    return request.get(`${API_URL}/admin-report-subscriptions`);
  }

  getAdmins() {
    return request.get(`${API_URL}/admins`);
  }

  addSubscription(subscription) {
    return request.post(`${API_URL}/admin-report-subscriptions`, subscription);
  }

  updateSubscription(id, subscription) {
    return request.put(`${API_URL}/admin-report-subscriptions/${id}`, subscription);
  }

  deleteSubscription(id) {
    return request.delete(`${API_URL}/admin-report-subscriptions/${id}`);
  }
}

export default new AdminReportSubscriptionService();
