// user.service.js
import request from '@/utils/request'

const getUsers = async () => {
  try {
    const response = await request.get('/users')
    return response.data
  } catch (error) {
    throw error
  }
}

export default {
  getUsers
}
