<template>
    <div>
      <h1>{{ isEditMode ? 'Edit Admin' : 'Create Admin' }}</h1>
      <form @submit.prevent="submitForm">
        <div>
          <label for="name">Name</label>
          <input type="text" id="name" v-model="admin.name" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" id="email" v-model="admin.email" required>
        </div>
        <div>
          <label for="password">Password</label>
          <input type="password" id="password" v-model="admin.password" :required="!isEditMode">
        </div>
        <button type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import { mapActions } from 'vuex';
  
  export default {
    name: 'AdminForm',
    data() {
      return {
        admin: {
          name: '',
          email: '',
          password: '',
        },
        isEditMode: false,
      };
    },
    mounted() {
      const adminId = this.$route.params.id;
      if (adminId) {
        this.isEditMode = true;
        this.fetchAdmin(adminId);
      }
    },
    methods: {
      ...mapActions('admin', ['createAdmin', 'updateAdmin', 'fetchAdmin']),
      submitForm() {
        if (this.isEditMode) {
          this.updateAdmin({ ...this.admin })
            .then(() => {
              // Handle success
              this.$router.push({ name: 'AdminList' });
            })
            .catch((error) => {
              console.error('Error updating admin:', error);
            });
        } else {
          this.createAdmin({ ...this.admin })
            .then(() => {
              // Handle success
              this.$router.push({ name: 'AdminList' });
            })
            .catch((error) => {
              console.error('Error creating admin:', error);
            });
        }
      },
    },
  };
  </script>
  