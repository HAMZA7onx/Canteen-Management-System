import request from '@/utils/request';

import { API_URL } from '@/config/config';

class UserService {
  getUsers() {
    return request.get(`${API_URL}/users`);
  }

  getUser(id) {
    return request.get(`${API_URL}/users/${id}`);
  }

  createUser(user) {
    return request.post(`${API_URL}/users`, user);
  }

  updateUser(id, user) {
    return request.put(`${API_URL}/users/${id}`, user);
  }

  deleteUser(id) {
    return request.delete(`${API_URL}/users/${id}`);
  }

  importUsers(formData) {
    return request.post(`${API_URL}/users/import`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  }
}

export default new UserService();
