<template>
  <div class="">
    <div class="">
      <form @submit.prevent="handleSubmit">
        <div v-if="badge.status === 'assigned'" class="text-center">
          <p class="text-gray-700 dark:text-gray-300 mb-6">Etes-vous sûr de vouloir marquer cette RFID comme perdue ?</p>
          
          <button
            type="button"
            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-3 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105"
            @click="markAsLost"
          >
          Marquer comme perdu
          </button>
        </div>
      </form>
    </div>
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
    ...mapGetters('badge', ['eligibleUsers']),
  },
  methods: {
    ...mapActions('badge', ['updateBadgeStatus', 'fetchEligibleUsers']),
    markAsLost() {
      this.updateBadgeStatus({ badgeId: this.badge.id, status: 'lost' })
        .then(updatedBadge => {
          this.fetchEligibleUsers();
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

<style scoped>
/* Add any additional styles here if needed */
</style>
