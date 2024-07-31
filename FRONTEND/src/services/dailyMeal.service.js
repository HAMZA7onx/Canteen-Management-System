import request from '@/utils/request'

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/daily-meals`;

class DailyMealService {
  getDailyMeals() {
    return request.get(API_URL)
  }

  getDailyMeal(id) {
    return request.get(`${API_URL}/${id}`)
  }

  createDailyMeal(data) {
    return request.post(API_URL, data)
  }

  updateDailyMeal(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteDailyMeal(id) {
    return request.delete(`${API_URL}/${id}`)
  }

  attachMenus(dailyMealId, menuIds) {
    return request.post(`${API_URL}/${dailyMealId}/menus`, { menuIds })
  }  

  detachMenu(dailyMealId, menuId) {
    return request.delete(`${API_URL}/${dailyMealId}/menus/${menuId}`)
  }
}

export default new DailyMealService()
