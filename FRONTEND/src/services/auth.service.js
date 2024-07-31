import axios from 'axios';
import request from '@/utils/request';

import { API_URL } from '@/config/config';


class AuthService {
  login(credentials) {
    return axios.post(`${API_URL}/login`, credentials);
  }

  logout() {
    return request.post(`${API_URL}/logout`);
  }
}

export default new AuthService();