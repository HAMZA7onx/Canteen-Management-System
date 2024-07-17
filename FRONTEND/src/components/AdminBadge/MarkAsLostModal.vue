<template>
      <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-center bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">Edit Badge</h2>

      <form @submit.prevent="handleSubmit">
        <div v-if="badge.status === 'assigned'" class="text-center">
          <p class="text-gray-700 dark:text-gray-300 mb-6">Are you sure you want to mark this RFID as lost?</p>
          
          <button
            type="button"
            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-3 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
            @click="markAsLost"
          >
            Mark as Lost
          </button>
        </div>
      </form>
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
  