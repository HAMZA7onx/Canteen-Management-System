import request from '@/utils/request';

import { API_URL } from '@/config/config';

class PosDeviceService {
  getPosDevices() {
    return request.get(`${API_URL}/pos-devices`);
  }

  getPosDevice(id) {
    return request.get(`${API_URL}/pos-devices/${id}`);
  }

  createPosDevice(posDevice) {
    return request.post(`${API_URL}/pos-devices`, posDevice);
  }

  updatePosDevice(id, posDevice) {
    return request.put(`${API_URL}/pos-devices/${id}`, posDevice);
  }

  deletePosDevice(id) {
    return request.delete(`${API_URL}/pos-devices/${id}`);
  }
}

export default new PosDeviceService();
