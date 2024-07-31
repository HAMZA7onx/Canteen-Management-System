import request from '@/utils/request';

import { API_URL } from '@/config/config';

class BadgingService {
  async scanBadge(rfid, day) {
    try {
      const response = await request.post(`${API_URL}/badging/${day}`, { rfid: rfid });
      return response.data;
    } catch (error) {
      console.error('Error scanning badge:', error);
      throw error;
    }
  }
  
  async getCurrentMeal() {
    try {
      const response = await request.get(`${API_URL}/badging/current-meal`);
      return response.data;
    } catch (error) {
      console.error('Error fetching current meal:', error);
      throw error;
    }
  }

  // Add this method to the BadgingService class
  async getDiscountsForMeal(day, mealId) {
    try {
      const response = await request.get(`${API_URL}/discounts/${day}/${mealId}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching discounts:', error);
      throw error;
    }
  }

  async getCurrentMealBadgeCount() {
    try {
      const response = await request.get(`${API_URL}/badging/current-meal-count`);
      return response.data.count;
    } catch (error) {
      console.error('Error fetching current meal badge count:', error);
      throw error;
    }
  }
}

export default new BadgingService();