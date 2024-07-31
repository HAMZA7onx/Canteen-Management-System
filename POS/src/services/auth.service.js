import axios from 'axios';
import request from '@/utils/request';

import { API_URL } from '@/config/config';

class AuthService {
    loginWithBadge(rfid) {
        return axios.post(`${API_URL}/login-with-badge`, { rfid });
    }
    logout() {
        return request.post(`${API_URL}/logout`);
    }
}

export default new AuthService();
