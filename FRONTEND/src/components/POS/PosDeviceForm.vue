<template>
    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label for="ip_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP Address</label>
        <input
          type="text"
          id="ip_address"
          v-model="formData.ip_address"
          @input="validateInput"
          required
          :class="{'border-red-500': !isValidIP}"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
        <p v-if="!isValidIP" class="mt-1 text-sm text-red-600">Please enter a valid IP address</p>
      </div>
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
        <select
          id="status"
          v-model="formData.status"
          required
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
          <option value="allowed">Allowed</option>
          <option value="unauthorized">Unauthorized</option>
        </select>
      </div>
      <div>
        <button
          type="submit"
          :disabled="!isValidIP"
          class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ device.id ? 'Update' : 'Create' }} POS Device
        </button>
      </div>
    </form>
  </template>
  
  <script>
  export default {
    name: 'PosDeviceForm',
    props: {
      device: {
        type: Object,
        default: () => ({
          ip_address: '',
          status: 'unauthorized'
        })
      }
    },
    data() {
      return {
        formData: { ...this.device },
        isValidIP: true
      }
    },
    methods: {
      validateIPAddress(ip) {
        const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        return ipRegex.test(ip);
      },
      validateInput() {
        this.isValidIP = this.validateIPAddress(this.formData.ip_address);
      },
      submitForm() {
        if (this.isValidIP) {
          this.$emit('update:device', this.formData);
        }
      }
    }
  }
  </script>
  