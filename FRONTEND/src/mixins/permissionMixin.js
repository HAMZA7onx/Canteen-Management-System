import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters('auth', ['userPermissions']),
  },
  methods: {
    $can(permission) {
      return this.userPermissions.includes(permission);
    },
  },  
};