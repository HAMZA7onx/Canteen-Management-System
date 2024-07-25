import axios from 'axios'
import store from '@/store'
import router from '@/router'

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

request.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 403) {
      router.push({ name: 'unauthorized' });
    }
    return Promise.reject(error);
  }
)

export default request
