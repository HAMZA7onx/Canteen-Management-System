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
    </div>
</template>

<script>
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';
import store from '@/store';

export default {
    name: 'AuthenticatedLayout',
    components: {
        Navbar,
        Sidebar,
    },
    mounted() {
        // Access the response value from the auth module
        const response = store.state.auth;
        console.log(response);
    },
    // updated() {
    //     // Access the response value from the auth module
    //     const response = store.state.auth;
    //     console.log(response);
    // },
};
</script>