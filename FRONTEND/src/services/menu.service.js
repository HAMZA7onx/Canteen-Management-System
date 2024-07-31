import request from '@/utils/request'

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/menus`;

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

  attachFoodComposants(menuId, foodComposantIds) {
    return request.post(`${API_URL}/${menuId}/food-composants`, { foodComposantIds })
  }  

  detachFoodComposant(menuId, foodComposantId) {
    return request.delete(`${API_URL}/${menuId}/food-composants/${foodComposantId}`)
  }
}

export default new MenuService()
