import request from '@/utils/request'

const API_URL = 'http://127.0.0.1:8000/api/week-schedules'

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
}

export default new WeekScheduleService()
