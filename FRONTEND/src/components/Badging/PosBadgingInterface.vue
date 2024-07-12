<template>
    <div class="pos-badging-container">
      <h1>POS Badging Interface</h1>
      <BadgingInterface />
    </div>
  </template>
  
  <script>
  import BadgingInterface from './BadgingInterface.vue'
  
  export default {
    name: 'PosBadgingInterface',
    components: {
      BadgingInterface
    }
  }
  </script>
  