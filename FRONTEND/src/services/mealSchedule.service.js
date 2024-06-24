import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class MealScheduleService {
  getMealSchedules() {
    return request.get(`${API_URL}/meal-schedules`);
  }

  getMealSchedule(id) {
    return request.get(`${API_URL}/meal-schedules/${id}`);
  }

  createMealSchedule(mealSchedule) {
    return request.post(`${API_URL}/meal-schedules`, mealSchedule);
  }

  updateMealSchedule(id, mealSchedule) {
    return request.put(`${API_URL}/meal-schedules/${id}`, mealSchedule);
  }

  deleteMealSchedule(id) {
    return request.delete(`${API_URL}/meal-schedules/${id}`);
  }

  getCategoryDiscounts(mealScheduleId) {
    return request.get(`${API_URL}/meal-schedules/${mealScheduleId}/category-discounts`);
  }

  updateCategoryDiscount(mealScheduleId, categoryDiscount) {
    return request.put(`${API_URL}/meal-schedules/${mealScheduleId}/category-discounts`, categoryDiscount);
  }
}

export default new MealScheduleService();
