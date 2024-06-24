import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

class RecordService {
  getAll() {
    return request.get(`${API_URL}/meal-records`);
  }

  get(id) {
    return request.get(`${API_URL}/meal-records/${id}`);
  }

  create(mealRecord) {
    return request.post(`${API_URL}/meal-records`, mealRecord);
  }

  update(id, mealRecord) {
    return request.put(`${API_URL}/meal-records/${id}`, mealRecord);
  }

  delete(id) {
    return request.delete(`${API_URL}/meal-records/${id}`);
  }
}

export default new RecordService();
