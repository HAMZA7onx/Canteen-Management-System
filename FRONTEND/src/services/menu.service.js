import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api/menus'

class MenuService {
  getMenus() {
    return request.get(API_URL)
  }

  getMenu(id) {
    return request.get(`${API_URL}/${id}`)
  }

  createMenu(data) {
    return request.post(API_URL, data)
  }

  updateMenu(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteMenu(id) {
    return request.delete(`${API_URL}/${id}`)
  }

  attachFoodComposant(menuId, foodComposantId) {
    return request.post(`${API_URL}/${menuId}/food-composants/${foodComposantId}`)
  }

  detachFoodComposant(menuId, foodComposantId) {
    return request.delete(`${API_URL}/${menuId}/food-composants/${foodComposantId}`)
  }
}

export default new MenuService()