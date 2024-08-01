<template>
  <transition name="toast-fade">
    <div v-if="show" class="fixed top-4 right-4 z-50 bg-gradient-to-r from-green-400 via-blue-500 to-purple-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center transform hover:scale-105 transition-all duration-300 cursor-pointer" @click="$emit('close')">
      <div class="absolute inset-0 bg-white opacity-20 rounded-2xl animate-pulse"></div>
      <div class="mr-4 bg-white rounded-full p-2">
        <svg class="w-8 h-8 text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div>
        <h3 class="font-bold text-xl mb-1">Success!</h3>
        <p class="font-medium">{{ message }}</p>
      </div>
      <div class="ml-auto pl-4 border-l border-white border-opacity-50">
        <span class="text-xs uppercase tracking-wider">Click to dismiss</span>
      </div>
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
@keyframes shimmer {
  0% { background-position: -1000px 0; }
  100% { background-position: 1000px 0; }
}

.toast-fade-enter-active, .toast-fade-leave-active {
  transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.toast-fade-enter, .toast-fade-leave-to {
  opacity: 0;
  transform: translateY(-30px) scale(0.9) rotateX(-45deg);
}

.bg-gradient-to-r {
  background-size: 200% 200%;
  animation: shimmer 3s linear infinite;
}
</style>
