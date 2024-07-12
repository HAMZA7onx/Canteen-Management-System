<template>
  <div>
    <AuthenticatedLayout v-if="isLoggedIn" />
    <Login v-else />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import AuthenticatedLayout from './components/shared/AuthenticatedLayout.vue';
import Login from './views/Auth/Login.vue';

export default {
  name: 'App',
  components: {
    AuthenticatedLayout,
    Login,
  },
  computed: {
    ...mapGetters('auth', ['isLoggedIn']),
  },
};
</script>