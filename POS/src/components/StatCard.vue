<template>
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
      <div class="mr-4">
        <i :class="iconClass" class="text-3xl text-indigo-500"></i>
      </div>
      <div>
        <h4 class="text-lg font-semibold text-gray-700">{{ title }}</h4>
        <p class="text-2xl font-bold text-indigo-600">{{ value }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      value: [String, Number],
      icon: String,
    },
    computed: {
      iconClass() {
        return {
          'fas fa-users': this.icon === 'users',
          'fas fa-money-bill-wave': this.icon === 'cash',
          'fas fa-tag': this.icon === 'tag',
        };
      },
    },
  }
  </script>
  