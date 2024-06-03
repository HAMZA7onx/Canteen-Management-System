<template>
  <div>
    <Navbar />
    <div class="flex">
      <Sidebar />
      <main class="ml-64 p-4">
        <router-view />
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from './components/shared/Navbar.vue';
import Sidebar from './components/shared/Sidebar.vue';
import Footer from './components/shared/Footer.vue';

export default {
  name: 'App',
  components: {
    Navbar,
    Sidebar,
    Footer,
  },
};
</script>
