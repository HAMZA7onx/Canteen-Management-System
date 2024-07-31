import request from '@/utils/request';

import { API_URL } from '@/config/config';

class BadgingService {
  async verifyBadge(rfid) {
    try {
      const response = await request.get(`${API_URL}/badging/verify/${rfid}`);
      return response.data;
    } catch (error) {
      console.error('Error verifying badge:', error);
      throw error;
    }
  }
  
  async scanBadge(badgeId, day) {
    try {
      const response = await request.post(`${API_URL}/badging/${day}`, { badge_id: badgeId });
      return response.data;
    } catch (error) {
      console.error('Error scanning badge:', error);
      throw error;
    }
  }
  
}

export default new BadgingService();
