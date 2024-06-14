import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api'

class BadgeService {
  getBadges() {
    return request.get(`${API_URL}/badges`)
  }

  getBadge(id) {
    return request.get(`${API_URL}/badges/${id}`)
  }

  getUsers() {
    return request.get(`${API_URL}/users`)
  }

  createBadge(badge) {
    return request.post(`${API_URL}/badges`, badge)
  }

  updateBadge(id, badge) {
    return request.put(`${API_URL}/badges/${id}`, badge)
  }

  deleteBadge(id) {
    return request.delete(`${API_URL}/badges/${id}`)
  }
}

export default new BadgeService()
