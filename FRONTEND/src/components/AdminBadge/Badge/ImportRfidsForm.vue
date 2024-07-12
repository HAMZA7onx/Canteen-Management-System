<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Import RFIDs</h2>

    <!-- Download template section -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Download Template</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Download the Excel template, fill it with RFID values, then import.</p>
      <button
        @click="downloadTemplate"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
      >
        Download Excel Template
      </button>
    </div>

    <!-- File input section -->
    <div class="mb-4">
      <label for="file-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Choose Excel File
      </label>
      <div class="flex items-center">
        <input
          type="file"
          id="file-input"
          ref="fileInput"
          @change="handleFileChange"
          accept=".xlsx,.xls"
          class="hidden"
        />
        <label
          for="file-input"
          class="cursor-pointer bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors duration-300"
        >
          Select File
        </label>
        <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">{{ fileName || "No file chosen" }}</span>
      </div>
    </div>

    <!-- Import button -->
    <div class="mt-4">
      <button
        @click="importRfids"
        :disabled="!file"
        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-300 dark:bg-green-700 dark:hover:bg-green-800 dark:focus:ring-green-600"
      >
        Import RFIDs
      </button>
    </div>

    <!-- Error and success messages -->
    <div v-if="error" class="mt-4 text-sm text-red-600 dark:text-red-400">{{ error }}</div>
    <div v-if="success" class="mt-4 text-sm text-green-600 dark:text-green-400">{{ success }}</div>
  </div>
</template>

<script>
import BadgeService from '@/services/adminBadge.service';
import * as XLSX from 'xlsx';
import FileSaver from 'file-saver';

export default {
  data() {
    return {
      file: null,
      error: null,
      success: null,
      fileName: '',
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
      this.fileName = this.file ? this.file.name : '';
      this.error = null;
      this.success = null;
    },
    importRfids() {
      const formData = new FormData();
      formData.append('file', this.file);

      this.error = null;
      this.success = null;

      BadgeService.importRfids(formData)
        .then((response) => {
          this.success = 'RFIDs imported successfully';
          this.$emit('import-success', response.data);
          this.file = null;
          this.fileName = '';
        })
        .catch((error) => {
          console.error('Error importing RFIDs:', error);
          if (error.response && error.response.data && error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'An error occurred while importing RFIDs';
          }
        });
    },
    downloadTemplate() {
      const headers = ['RFID'];
      const ws = XLSX.utils.aoa_to_sheet([headers]);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'RFIDs');
      const excelBuffer = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
      const data = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8' });
      FileSaver.saveAs(data, 'rfid_template.xlsx');
    },
  },
};
</script>
