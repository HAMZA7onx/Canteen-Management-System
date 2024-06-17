import request from '@/utils/request'

const getAll = () => {
  return request.get('/meal-schedules')
}

const get = (id) => {
  return request.get(`/meal-schedules/${id}`)
}

const create = (data) => {
  return request.post('/meal-schedules', data)
}

const update = (id, data) => {
  return request.put(`/meal-schedules/${id}`, data)
}

const remove = (id) => {
  return request.delete(`/meal-schedules/${id}`)
}

export default {
  getAll,
  get,
  create,
  update,
  delete: remove
}
