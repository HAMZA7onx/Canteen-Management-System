<template>
  <nav class="bg-gray-800">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="text-white font-bold">Your Logo</router-link>
        </div>
        <div class="flex items-center">
          <router-link to="/login" class="text-white hover:text-gray-300 mx-4">Login</router-link>
          <!-- Add more navigation links as needed -->
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
};
</script>
