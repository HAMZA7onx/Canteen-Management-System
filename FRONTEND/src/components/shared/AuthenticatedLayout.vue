<!-- components/shared/AuthenticatedLayout.vue -->
<template>
    <div class="h-screen">
        <Navbar />
        <div class="flex h-full">
            <Sidebar />
            <main class="ml-64 p-4 bg-red-500 h-full w-full ">
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
        const response = store.state.auth;
        console.log(response);
    },
};
</script>