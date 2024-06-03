<template>
    <aside class="bg-gray-800 text-white h-screen w-64 fixed">
      <div class="p-4">
        <h2 class="text-xl font-bold mb-4">Menu</h2>
        <nav>
          <ul>
            <li class="mb-2">
              <router-link to="/admin" class="hover:text-gray-300">Admin</router-link>
            </li>
            <li class="mb-2">
              <router-link to="/users" class="hover:text-gray-300">Users</router-link>
            </li>
            <!-- Add more menu items as needed -->
          </ul>
        </nav>
      </div>
    </aside>
  </template>
  
  <script>
  export default {
    name: 'Sidebar',
  };
  </script>
  