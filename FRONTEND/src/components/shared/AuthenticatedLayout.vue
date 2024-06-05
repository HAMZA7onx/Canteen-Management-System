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

export default {
    name: 'AuthenticatedLayout',
    components: {
        Navbar,
        Sidebar,
    },
};
</script>