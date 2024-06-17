import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class MealMenuService {
  getMealMenus() {
    return request.get(`${API_URL}/meal-menus`);
  }

  getMealMenu(id) {
    return request.get(`${API_URL}/meal-menus/${id}`);
  }

  createMealMenu(mealMenu) {
    return request.post(`${API_URL}/meal-menus`, mealMenu);
  }

  updateMealMenu(id, mealMenu) {
    return request.put(`${API_URL}/meal-menus/${id}`, mealMenu);
  }

  deleteMealMenu(id) {
    return request.delete(`${API_URL}/meal-menus/${id}`);
  }
}

export default new MealMenuService();
