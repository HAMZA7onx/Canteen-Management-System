// src/services/badging.service.js
import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api'

class BadgingService {
  scanBadge(rfid, day) {
    return request.post(`${API_URL}/badging/${day}`, { badge_id: rfid })
  }
}

export default new BadgingService()
