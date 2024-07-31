<template>
    <div class="expandable-section mt-6">
      <button 
        @click="isExpanded = !isExpanded"
        class="w-full flex justify-between items-center p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        <span class="text-lg font-semibold">{{ title }}</span>
        <i :class="isExpanded ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-indigo-500"></i>
      </button>
      <transition name="expand">
        <div v-if="isExpanded" class="mt-4">
          <slot></slot>
        </div>
      </transition>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    props: {
      title: String,
    },
    setup() {
      const isExpanded = ref(false);
      return { isExpanded };
    },
  }
  </script>
  
  <style scoped>
  .expand-enter-active,
  .expand-leave-active {
    transition: max-height 0.3s ease-in-out;
    overflow: hidden;
  }
  
  .expand-enter-from,
  .expand-leave-to {
    max-height: 0;
  }
  
  .expand-enter-to,
  .expand-leave-from {
    max-height: 1000px;
  }
  </style>
  