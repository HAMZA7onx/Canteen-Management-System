<template>
    <div>
      <AdminList />
    </div>
  </template>
  
  <script>
  import AdminList from '@/components/Admin/AdminList.vue';
  import { mapActions } from 'vuex';
  
  export default {
    name: 'AdminListView',
    components: {
      AdminList,
    },
    created() {
      this.fetchAdmins();
    },
    methods: {
      ...mapActions('admin', ['fetchAdmins']),
    },
  };
  </script>
  