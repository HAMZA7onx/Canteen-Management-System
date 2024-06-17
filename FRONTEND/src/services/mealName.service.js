import request from '@/utils/request'

const getAll = () => {
  return request.get('/meal-names')
}

const get = (id) => {
  return request.get(`/meal-names/${id}`)
}

const create = (data) => {
  return request.post('/meal-names', data)
}

const update = (id, data) => {
  return request.put(`/meal-names/${id}`, data)
}

const remove = (id) => {
  return request.delete(`/meal-names/${id}`)
}

export default {
  getAll,
  get,
  create,
  update,
  delete: remove
}
