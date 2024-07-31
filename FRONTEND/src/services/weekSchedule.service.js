import request from '@/utils/request'

import { API_URL as BASE_API_URL } from '@/config/config';
const API_URL = `${BASE_API_URL}/week-schedules`;

class WeekScheduleService {
  getWeekSchedules() {
    return request.get(API_URL)
  }

  createWeekSchedule(data) {
    return request.post(API_URL, data)
  }

  updateWeekSchedule(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteWeekSchedule(id) {
    return request.delete(`${API_URL}/${id}`)
  }

  assignDailyMeals(weekScheduleId, day, dailyMealData) {
    return request.post(`${API_URL}/${weekScheduleId}/daily-meals/${day}`, dailyMealData)
  }  

  detachDailyMeal(weekScheduleId, day, dailyMealId) {
    return request.delete(`${API_URL}/${weekScheduleId}/daily-meals/${dailyMealId}/${day}`)
  }

  getDailyMealDiscounts(weekScheduleId, day, dailyMealId) {
    return request.get(`${API_URL}/${weekScheduleId}/daily-meals/${day}/${dailyMealId}/discounts`)
  }
}

export default new WeekScheduleService()
