import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

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
}

export default new RecordService();