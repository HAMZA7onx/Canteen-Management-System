<template>
  <div>
    <input type="file" ref="fileInput" @change="handleFileChange" accept=".xlsx,.xls" />
    <button @click="importRfids" :disabled="!file">Import RFIDs</button>
    <div v-if="error" class="text-red-500">{{ error }}</div>
  </div>
</template>

<script>
import BadgeService from '@/services/badge.service';

export default {
  data() {
    return {
      file: null,
      error: null,
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
    },
    importRfids() {
      const formData = new FormData();
      formData.append('file', this.file);

      BadgeService.importRfids(formData)
        .then(() => {
          this.$emit('import-success');
          this.file = null;
          this.error = null;
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