import request from '@/utils/request';

import { API_URL } from '@/config/config';

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
