<template>
    <form @submit.prevent="submitForm" class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <div class="space-y-4">
        <div class="relative">
          <input
            id="name"
            v-model="formData.name"
            type="text"
            required
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-cyan-500 dark:focus:border-cyan-400 focus:outline-none transition duration-300 placeholder-transparent"
            placeholder="Device Name"
          />
          <label
            for="name"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-cyan-500 dark:peer-focus:text-cyan-400"
          >
          Nom de l'appareil
          </label>
        </div>

        <div class="relative">
          <input
            id="ip_address"
            v-model="formData.ip_address"
            @input="validateInput"
            type="text"
            required
            :class="{'border-red-500': !isValidIP}"
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-cyan-500 dark:focus:border-cyan-400 focus:outline-none transition duration-300 placeholder-transparent"
            placeholder="IP Address"
          />
          <label
            for="ip_address"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-cyan-500 dark:peer-focus:text-cyan-400"
          >
            addresse IP 
          </label>
          <p v-if="!isValidIP" class="mt-1 text-sm text-red-600 dark:text-red-400">Please enter a valid IP address</p>
        </div>

        <div class="relative">
          <select
            id="status"
            v-model="formData.status"
            required
            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-cyan-500 dark:focus:border-cyan-400 focus:outline-none transition duration-300"
          >
            <option value="allowed">Allowed</option>
            <option value="unauthorized">Unauthorized</option>
          </select>
          <label
            for="status"
            class="absolute left-4 -top-2.5 text-sm text-gray-600  dark:text-gray-400"
          >
            Status
          </label>
        </div>

        <div class="relative">
          <select
            id="print_statistics"
            v-model="formData.print_statistics"
            required
            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-cyan-500 dark:focus:border-cyan-400 focus:outline-none transition duration-300"
          >
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <label
            for="print_statistics"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400"
          >
          print_statistics Status
          </label>
        </div>

        <div class="relative">
          <select
            id="print_tickets"
            v-model="formData.print_tickets"
            required
            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-cyan-500 dark:focus:border-cyan-400 focus:outline-none transition duration-300"
          >
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <label
            for="print_tickets"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400"
          >
          print_tickets Status
          </label>
        </div>
      </div>

      <button
        type="submit"
        :disabled="!isValidIP"
        class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500 text-white hover:from-cyan-600 hover:to-blue-700 dark:hover:from-cyan-500 dark:hover:to-blue-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ device.id ? 'Modifier' : 'Créer' }} l'appareil
      </button>
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
      formData: {
        name: this.device.name || '',
        ip_address: this.device.ip_address || '',
        status: this.device.status || 'unauthorized',
        print_statistics: this.device.print_statistics || 'inactive',
        print_tickets: this.device.print_tickets || 'inactive'
      },
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
