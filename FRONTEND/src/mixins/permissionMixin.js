import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters('auth', ['userPermissions']),
  },
  methods: {
    $can(permission) {
      console.log('Checking permission:', permission);
      console.log('User permissions:', this.userPermissions);
      return this.userPermissions.includes(permission);
    },
  },  
};
