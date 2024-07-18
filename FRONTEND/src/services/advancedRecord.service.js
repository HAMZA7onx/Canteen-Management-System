import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class AdvancedRecordService {
  fetchAdvancedRecords(filters) {
    return request.get(`${API_URL}/records/advanced`, { params: filters });
  }
}

export default new AdvancedRecordService();