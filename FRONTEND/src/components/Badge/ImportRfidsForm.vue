<template>
  <div>
    <input
      type="file"
      ref="fileInput"
      @change="handleFileChange"
      accept=".xlsx,.xls"
      class="mb-4"
    />
    <button
      @click="importRfids"
      :disabled="!file"
      class="bg-blue-500 text-white px-4 py-2 rounded-md"
    >
      Import RFIDs
    </button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      file: null,
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
    },
    async importRfids() {
      const formData = new FormData();
      formData.append('file', this.file);

      try {
        await axios.post('/api/badges/import', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        console.log('RFIDs imported successfully');
        this.$emit('import-success');
        this.$refs.fileInput.value = null;
        this.file = null;
      } catch (error) {
        console.error('Error importing RFIDs:', error);
      }
    },
  },
};
</script>
