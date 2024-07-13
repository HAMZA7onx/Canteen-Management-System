import axios from 'axios';
import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class AuthService {
    loginWithBadge(rfid) {
        return axios.post(`${API_URL}/login-with-badge`, { rfid })
            .catch(error => {
                if (error.response && error.response.status === 403) {
                    throw new Error('This device is not authorized to access the badging system.');
                }
                throw error;
            });
    }    

    logout() {
        return request.post(`${API_URL}/logout`);
    }
}

export default new AuthService();
