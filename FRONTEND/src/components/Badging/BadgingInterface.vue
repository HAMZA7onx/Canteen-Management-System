<!-- src/components/Badging/BadgingInterface.vue -->
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md transition-colors duration-300">
      <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">Badge Scanner</h2>
      
      <div class="mb-6 text-center">
        <div 
          :class="[
            'w-48 h-48 mx-auto rounded-full flex items-center justify-center text-4xl',
            isScanning ? 'bg-green-500 animate-pulse' : 'bg-gray-300 dark:bg-gray-600'
          ]"
        >
          <i class="fas fa-wifi" :class="{ 'text-white': isScanning, 'text-gray-500 dark:text-gray-400': !isScanning }"></i>
        </div>
        <p class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
          {{ isScanning ? 'Scanning...' : 'Ready to Scan' }}
        </p>
      </div>
      
      <button
        @click="toggleScanner"
        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-300"
      >
        {{ isScanning ? 'Stop Scanning' : 'Start Scanning' }}
      </button>
      
      <div v-if="message" :class="['mt-4 p-3 rounded', messageClass]">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'

export default {
  name: 'BadgingInterface',
  setup() {
    const store = useStore()
    const isScanning = ref(false)
    const message = ref('')
    const messageType = ref('')

    const messageClass = computed(() => {
      return {
        'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100': messageType.value === 'success',
        'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100': messageType.value === 'error'
      }
    })

    let scannerInterval

    const toggleScanner = () => {
      isScanning.value = !isScanning.value
      if (isScanning.value) {
        startScanning()
      } else {
        stopScanning()
      }
    }

    const startScanning = () => {
      scannerInterval = setInterval(simulateScan, 5000) // Simulate a scan every 5 seconds
    }

    const stopScanning = () => {
      clearInterval(scannerInterval)
    }

    const simulateScan = async () => {
      const simulatedRFID = Math.random().toString(36).substr(2, 10) // Generate a random RFID
      try {
        const currentDay = new Date().toLocaleLowerCase().split(',')[0] // Get current day of the week
        const response = await store.dispatch('badging/scanBadge', { rfid: simulatedRFID, day: currentDay })
        setMessage(`Badge scanned: ${simulatedRFID}. ${response.message}`, 'success')
      } catch (error) {
        setMessage(error.response?.data?.error || 'An error occurred', 'error')
      }
    }

    const setMessage = (msg, type) => {
      message.value = msg
      messageType.value = type
      setTimeout(() => {
        message.value = ''
        messageType.value = ''
      }, 5000)
    }

    return {
      isScanning,
      message,
      messageClass,
      toggleScanner
    }
  }
}
</script>
