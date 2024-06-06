<template>
  <nav class="bg-gray-800">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="text-white font-bold">DASHBOARD</router-link>
        </div>
        <div class="flex items-center">
          <div class="font-semibold text-white cursor-pointer">
            PROFILE
          </div>
          <!-- <router-link to="/logout" class="text-white hover:text-gray-300 mx-4">logout</router-link> -->
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