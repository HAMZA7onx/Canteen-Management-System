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
  import { mapGetters, mapActions } from 'vuex';
  
  export default {
    props: {
      badge: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        selectedUser: null,
      };
    },
    computed: {
      ...mapGetters('adminBadge', ['eligibleUsers']),
    },
    methods: {
      ...mapActions('adminBadge', ['updateBadgeStatus', 'fetchEligibleUsers']),
      markAsLost() {
        this.updateBadgeStatus({ badgeId: this.badge.id, status: 'lost' })
          .then(updatedBadge => {
            // Fetch the updated list of eligible users
            this.fetchEligibleUsers();
            // Close the modal after successful update
            this.$emit('update-success', updatedBadge);
          })
          .catch(error => {
            console.error('Error updating badge status:', error);
            // Handle the error here (e.g., display an error message)
          });
      },
    },
  };
  </script>
  