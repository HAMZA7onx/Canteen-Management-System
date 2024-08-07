import request from '@/utils/request';
import { API_URL } from '@/config/config';

class PosDeviceService {
  getPosStatus() {
    return request.get(`${API_URL}/getStatus`);
  }
}

export default new PosDeviceService();