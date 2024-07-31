import request from '@/utils/request';

import { API_URL } from '@/config/config';


class AdvancedRecordService {
  fetchAdvancedRecords(filters) {
    return request.get(`${API_URL}/records/advanced`, { params: filters });
  }
}

export default new AdvancedRecordService(); 