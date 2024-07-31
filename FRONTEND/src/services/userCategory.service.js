import request from '@/utils/request';

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/user-categories`;


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
