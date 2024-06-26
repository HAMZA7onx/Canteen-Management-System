import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api/daily-meals'

class DailyMealService {
  getDailyMeals() {
    return request.get(API_URL)
  }

  getDailyMeal(id) {
    return request.get(`${API_URL}/${id}`)
  }

  createDailyMeal(data) {
    return request.post(API_URL, data)
  }

  updateDailyMeal(id, data) {
    return request.put(`${API_URL}/${id}`, data)
  }

  deleteDailyMeal(id) {
    return request.delete(`${API_URL}/${id}`)
  }

  attachWeekSchedule(dailyMealId, weekScheduleId, day) {
    return request.post(`${API_URL}/${dailyMealId}/week-schedules/${day}`, { week_schedule_id: weekScheduleId })
  }

  detachWeekSchedule(dailyMealId, weekScheduleId, day) {
    return request.delete(`${API_URL}/${dailyMealId}/week-schedules/${weekScheduleId}/${day}`)
  }
}

export default new DailyMealService()
