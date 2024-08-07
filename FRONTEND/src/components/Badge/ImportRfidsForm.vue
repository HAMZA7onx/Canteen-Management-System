<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">
    <!-- Download template section -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Télécharger le modèle</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Téléchargez le modèle Excel, remplissez-le avec les valeurs RFID, puis importez.</p>
      <button
        @click="downloadTemplate"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
      >
      Télécharger le modèle Excel
      </button>
    </div>

    <!-- File input section -->
    <div class="mb-4">
      <label for="file-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Choisissez un fichier Excel
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
        Choisir le fichier
        </label>
        <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">{{ fileName || "Aucun fichier choisi" }}</span>
      </div>
    </div>

    <!-- Import button -->
    <div class="mt-4">
      <button
        @click="importRfids"
        :disabled="!file || isLoading"
        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-300 dark:bg-green-700 dark:hover:bg-green-800 dark:focus:ring-green-600"
      >
        <span v-if="!isLoading">Importer RFIDs</span>
        <span v-else class="flex items-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Importation...
        </span>
      </button>
    </div>

    <!-- Error and success messages -->
    <div v-if="error" class="mt-4 text-sm text-red-600 dark:text-red-400">{{ error }}</div>
    <div v-if="success" class="mt-4 text-sm text-green-600 dark:text-green-400">{{ success }}</div>
    <div v-if="importResult" class="mt-4 text-sm text-green-600 dark:text-green-400">
      Importation terminée: {{ importResult.inserted }} RFID insérées,
      {{ importResult.ignored }} RFIDs ignorées (existe déjà),
      hors de {{ importResult.total }} du total RFIDs.
    </div>
  </div>
</template>

<script>
import BadgeService from '@/services/badge.service';
import * as XLSX from 'xlsx';
import FileSaver from 'file-saver';

export default {
  data() {
    return {
      file: null,
      error: null,
      success: null,
      fileName: '',
      importResult: null,
      isLoading: false,
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
      this.fileName = this.file ? this.file.name : '';
      this.error = null;
      this.success = null;
      this.importResult = null;
    },
    importRfids() {
      const formData = new FormData();
      formData.append('file', this.file);

      this.error = null;
      this.success = null;
      this.importResult = null;
      this.isLoading = true;

      BadgeService.importRfids(formData)
        .then((response) => {
          this.importResult = response.data.result;
          if (this.importResult.inserted > 0) {
            this.success = 'RFIDs imported successfully';
          }
          this.$emit('import-success', response.data);
        })
        .catch((error) => {
          console.error('Error importing RFIDs:', error);
          if (error.response && error.response.data && error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'An error occurred while importing RFIDs';
          }
        })
        .finally(() => {
          this.isLoading = false;
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
