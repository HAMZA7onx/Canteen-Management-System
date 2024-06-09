import axios from 'axios';
import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class AuthService {
  login(credentials) {
    return axios.post(`${API_URL}/login`, credentials);
  }

  logout() {
    // return axios.post(`${API_URL}/logout`);
    return request.post(`${API_URL}/logout`);
  }
}

export default new AuthService();