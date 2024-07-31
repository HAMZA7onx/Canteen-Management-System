import request from '@/utils/request'

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/food-composants`;

class FoodComposantService {
  getFoodComposants() {
    return request.get(API_URL)
  }

  getFoodComposant(id) {
    return request.get(`${API_URL}/${id}`)
  }

  createFoodComposant(data) {
    return request.post(API_URL, data)
  }

  updateFoodComposant(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteFoodComposant(id) {
    return request.delete(`${API_URL}/${id}`)
  }
}

export default new FoodComposantService()
