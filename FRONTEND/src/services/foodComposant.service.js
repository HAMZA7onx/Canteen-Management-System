import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api/food-composants'

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

  attachToMenu(foodComposantId, menuId) {
    return request.post(`${API_URL}/${foodComposantId}/menus`, { menu_id: menuId })
  }

  detachFromMenu(foodComposantId, menuId) {
    return request.delete(`${API_URL}/${foodComposantId}/menus/${menuId}`)
  }
}

export default new FoodComposantService()
