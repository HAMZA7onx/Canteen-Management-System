<template>
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Import RFIDs</h2>
    <div class="mb-4">
      <label for="file-input" class="block text-sm font-medium text-gray-700 mb-2">
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
          class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Select File
        </label>
        <span class="ml-3 text-sm text-gray-500">{{ fileName || "No file chosen" }}</span>
      </div>
    </div>
    <div class="mt-4">
      <button
        @click="importRfids"
        :disabled="!file"
        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Import RFIDs
      </button>
    </div>
    <div v-if="error" class="mt-4 text-sm text-red-600">{{ error }}</div>
    <div v-if="success" class="mt-4 text-sm text-green-600">{{ success }}</div>
  </div>
</template>

<script>
import BadgeService from '@/services/badge.service';

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
  },
};
</script>
