<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-100 to-indigo-100 dark:from-gray-800 dark:to-indigo-900 transition-colors duration-300">
    <div class="max-w-md w-full space-y-8 p-10 bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform transition-all duration-500 ease-in-out hover:scale-105">
      <div class="text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">
            Unauthorized Access
          </span>
        </h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
          You do not have permission to access this resource.
        </p>
      </div>
      <div class="mt-8">
        <router-link
          to="/"
          class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 dark:from-purple-500 dark:to-indigo-500 dark:hover:from-purple-600 dark:hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          Return to Home
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Unauthorized',
};
</script>

<style scoped>
@keyframes gradientAnimation {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.bg-gradient-to-r {
  background-size: 200% 200%;
  animation: gradientAnimation 15s ease infinite;
}
</style>