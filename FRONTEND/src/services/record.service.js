import request from '@/utils/request';

import { API_URL } from '@/config/config';

class RecordService {
  getYears() {
    return request.get(`${API_URL}/records/years`);
  }

  getMonths(year) {
    return request.get(`${API_URL}/records/${year}/months`);
  }

  getDays(year, month) {
    return request.get(`${API_URL}/records/${year}/${month}/days`);
  } 

  getDayRecords(year, month, day) {
    return request.get(`${API_URL}/records/${year}/${month}/${day}`);
  }

  getMonthlyTotals(year, month) {
    return request.get(`${API_URL}/records/${year}/${month}/monthly-totals`);
  }
  
}

export default new RecordService();