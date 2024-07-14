// src/services/badging.service.js
import request from '@/utils/request';

const API_URL = 'http://127.0.0.1:8000/api'; // Make sure this matches your backend URL

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

}

export default new BadgingService();