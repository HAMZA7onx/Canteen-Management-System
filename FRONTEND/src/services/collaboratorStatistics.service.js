// src/services/collaboratorStatistics.service.js

import request from '@/utils/request';
import { API_URL } from '@/config/config';

class CollaboratorStatisticsService {
  getStatistics(startDate, endDate) {
    return request.get(`${API_URL}/collaborator-statistics`, {
      params: { start_date: startDate, end_date: endDate }
    });
  }
}

export default new CollaboratorStatisticsService();
