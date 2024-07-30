import axios from 'axios'
import store from '@/store'

const request = axios.create({
  baseURL: '/api'
})

request.interceptors.request.use(config => {
  const token = store.getters['auth/token']

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

export default request

