// src/services/printer.service.js
import request from '@/utils/request';
import { API_URL } from '@/config/config';

class PrinterService {
  getPrinters() {
    return request.get(`${API_URL}/printers`);
  }

  getPrinter(id) {
    return request.get(`${API_URL}/printers/${id}`);
  }

  createPrinter(printer) {
    return request.post(`${API_URL}/printers`, printer);
  }

  updatePrinter(id, printer) {
    return request.put(`${API_URL}/printers/${id}`, printer);
  }

  deletePrinter(id) {
    return request.delete(`${API_URL}/printers/${id}`);
  }
}

export default new PrinterService();
