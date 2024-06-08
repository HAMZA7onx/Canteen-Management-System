  <template>
    <footer class="bg-gray-800 py-4">
      <div class="container mx-auto text-center text-white">
        &copy; {{ currentYear }} Your Company Name
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer',
    computed: {
      currentYear() {
        return new Date().getFullYear();
      },
    },
  };
  </script>
  