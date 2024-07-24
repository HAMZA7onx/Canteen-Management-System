// utils/request.js
import axios from 'axios'
import store from '@/store'

const request = axios.create({
  baseURL: '/api'
})

request.interceptors.request.use(config => {
  // Get the token from the Vuex store
  const token = store.getters['auth/token']
  // console.log('token', token)
  // If the token exists, add it to the request headers
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

export default request