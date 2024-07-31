import request from '@/utils/request';

import { API_URL } from '@/config/config';

class MealStatsService {
  async getDailyStats() {
    try {
      const response = await request.get(`${API_URL}/daily-meal-stats`);
      return response.data;
    } catch (error) {
      console.error('Error fetching daily meal stats:', error);
      throw error;
    }
  }
}

export default new MealStatsService();
