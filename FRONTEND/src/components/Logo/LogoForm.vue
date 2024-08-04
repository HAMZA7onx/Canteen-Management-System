<template>
    <div>
      <input type="file" @change="handleFileChange" accept="image/*" class="mb-4">
      <button @click="handleUpload" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
        Upload Logo
      </button>
    </div>
  </template>
  
  <script>
  import { mapActions } from 'vuex';
  
  export default {
    name: 'LogoForm',
    data() {
      return {
        selectedFile: null,
      };
    },
    methods: {
      ...mapActions('logo', ['uploadLogo']),
      handleFileChange(event) {
        this.selectedFile = event.target.files[0];
      },
      async handleUpload() {
        if (!this.selectedFile) {
          alert('Please select a file first');
          return;
        }
        try {
          await this.uploadLogo(this.selectedFile);
          this.$emit('logo-uploaded');
        } catch (error) {
          console.error('Error uploading logo:', error);
          alert('Failed to upload logo. Please try again.');
        }
      },
    },
  };
  </script>
  