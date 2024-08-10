import request from '@/utils/request'

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/week-schedules`;

class WeekScheduleService {
  getWeekSchedules() {
    return request.get(API_URL)
  }

  createWeekSchedule(data) {
    return request.post(API_URL, data)
  }

  updateWeekSchedule(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteWeekSchedule(id) {
    return request.delete(`${API_URL}/${id}`)
  }

  assignMenu(weekScheduleId, day, menuData) {
    return request.post(`${API_URL}/${weekScheduleId}/menus/${day}`, menuData)
  }  

  detachMenu(weekScheduleId, day, menuId) {
    return request.delete(`${API_URL}/${weekScheduleId}/menus/${menuId}/${day}`)
  }

  getMenuDiscounts(weekScheduleId, day, menuId) {
    return request.get(`${API_URL}/${weekScheduleId}/menus/${day}/${menuId}/discounts`)
  }
}
 
export default new WeekScheduleService()
