import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api';

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
