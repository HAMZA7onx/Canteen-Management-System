import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class HomeService {
  getUsers() {
    return request.get(`${API_URL}/home/users`);
  }

  getBadges() {
    return request.get(`${API_URL}/home/badges`);
  }

  getMenus() {
    return request.get(`${API_URL}/home/menus`)
  }

  getWeekSchedules() {
    return request.get(`${API_URL}/home/week-schedules`)
  }
}

export default new HomeService();
