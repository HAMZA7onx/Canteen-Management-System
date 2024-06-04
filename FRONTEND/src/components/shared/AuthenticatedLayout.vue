<!-- components/shared/AuthenticatedLayout.vue -->
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
// import store from '@/store';
// const token = store.getters['auth/token'];
// console.log('Token:', token);


import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

export default {
    name: 'AuthenticatedLayout',
    components: {
        Navbar,
        Sidebar,
        Footer,
    },
};
</script>