import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class MealNameService {
  getMealNames() {
    return request.get(`${API_URL}/meal-names`);
  }

  getMealName(id) {
    return request.get(`${API_URL}/meal-names/${id}`);
  }

  createMealName(mealName) {
    return request.post(`${API_URL}/meal-names`, mealName);
  }

  updateMealName(id, mealName) {
    return request.put(`${API_URL}/meal-names/${id}`, mealName);
  }

  deleteMealName(id) {
    return request.delete(`${API_URL}/meal-names/${id}`);
  }
}

export default new MealNameService();
