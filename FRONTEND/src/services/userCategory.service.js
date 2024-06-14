import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api/user-categories';

class UserCategoryService {
  getUserCategories() {
    return request.get(`${API_URL}`);
  }

  createUserCategory(category) {
    return request.post(`${API_URL}`, category);
  }

  updateUserCategory(id, category) {
    return request.put(`${API_URL}/${id}`, category);
  }

  deleteUserCategory(id) {
    return request.delete(`${API_URL}/${id}`);
  }
}

export default new UserCategoryService();
