<template>
    <transition name="toast-fade">
      <div v-if="show" class="fixed top-4 right-4 z-50 bg-gradient-to-r from-green-400 to-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="font-medium">{{ message }}</span>
      </div>
    </transition>
  </template>
  
  <script>
  export default {
    props: {
      show: Boolean,
      message: String,
    },
  }
  </script>
  
  <style scoped>
  .toast-fade-enter-active, .toast-fade-leave-active {
    transition: opacity 0.5s, transform 0.5s;
  }
  .toast-fade-enter, .toast-fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
  }
  </style>
  