import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class UserCategoryService {
  getUserCategories() {
    return request.get(`${API_URL}/user-categories`);
  }

  getUserCategory(id) {
    return request.get(`${API_URL}/user-categories/${id}`);
  }

  createUserCategory(userCategory) {
    return request.post(`${API_URL}/user-categories`, userCategory);
  }

  updateUserCategory(id, userCategory) {
    return request.put(`${API_URL}/user-categories/${id}`, userCategory);
  }

  deleteUserCategory(id) {
    return request.delete(`${API_URL}/user-categories/${id}`);
  }
}

export default new UserCategoryService();
