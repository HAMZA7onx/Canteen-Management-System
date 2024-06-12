import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

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
}

export default new UserService();
