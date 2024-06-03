<template>
<div>
    <h2>Admins</h2>
    <ul>
    <li v-for="admin in admins" :key="admin.id">{{ admin.name }}</li>
    </ul>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'AdminList',
    computed: {
        ...mapGetters('admin', ['admins']),
    },
    created() {
        this.fetchAdmins();
    },
    methods: {
        ...mapActions('admin', ['fetchAdmins']),
    },
};
</script>
