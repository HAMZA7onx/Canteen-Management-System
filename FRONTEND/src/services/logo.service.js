import request from '@/utils/request';
import { API_URL } from '@/config/config';

class LogoService {
  uploadLogo(file) {
    const formData = new FormData();
    formData.append('logo', file);
    return request.post(`${API_URL}/upload-logo`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  getLogo() {
    return request.get(`${API_URL}/get-logo`);
  }
}

export default new LogoService();
