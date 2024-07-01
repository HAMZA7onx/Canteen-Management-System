<template>
    <div class="bg-white rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Edit Badge</h2>
      <form @submit.prevent="handleSubmit">
        <div v-if="badge.status === 'assigned'">
          <p>Are you sure you want to mark this RFID as lost?</p>
          <button
            type="button"
            class="bg-red-500 text-white px-4 py-2 rounded-md mt-4"
            @click="markAsLost"
          >
            Mark as Lost
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      badge: {
        type: Object,
        required: true,
      },
      users: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        selectedUser: null,
      };
    },
    methods: {
      markAsLost() {
        this.updateBadgeStatus('lost');
      },
      updateBadgeStatus(status) {
  return this.$store.dispatch('badge/updateBadgeStatus', {
    badgeId: this.badge.id,
    status: status,
  })
    .then(response => {
      console.log('Response from server:', response);
      const updatedBadge = {
        id: response.id,
        rfid: response.rfid,
        status: response.status,
        user: response.user, // Assuming the user data is in a different property
      };
      this.$emit('update-success', updatedBadge);
    })
    .catch(error => {
      console.error('Error updating badge status:', error);
      this.$emit('update-error', error);
    });
},

      assignRfidToUser(userId) {
        this.$store.dispatch('badge/assignRfidToUser', {
          badgeId: this.badge.id,
          userId: userId,
        })
          .then(response => {
            this.$emit('update-success', response.data);
          })
          .catch(error => {
            console.error('Error assigning RFID to user:', error);
            this.$emit('update-error', error);
          });
      },
    },
  };
  </script>
  