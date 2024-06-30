<template>
    <div class="bg-white rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Edit Badge</h2>
      <form @submit.prevent="handleSubmit">
        <div v-if="badge.status === 'assigned'">
          <p>Are you sure you want to mark this RFID as lost?</p>
          <button
            type="submit"
            class="bg-red-500 text-white px-4 py-2 rounded-md mt-4"
          >
            Mark as Lost
          </button>
        </div>
        <div v-else-if="badge.status === 'available'">
          <div class="mb-4">
            <label for="user" class="block font-bold mb-2">Assign to User:</label>
            <select
              id="user"
              v-model="selectedUser"
              class="border border-gray-300 rounded-md px-3 py-2 w-full"
            >
              <option value="">Select a user</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
          <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded-md"
          >
            Assign RFID
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      show: {
        type: Boolean,
        required: true,
      },
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
      handleSubmit() {
        if (this.badge.status === 'assigned') {
          this.updateBadgeStatus('lost');
        } else if (this.badge.status === 'available') {
          this.assignRfidToUser(this.selectedUser);
        }
      },
      updateBadgeStatus(status) {
        this.$store.dispatch('badge/updateBadgeStatus', {
          badgeId: this.badge.id,
          status: status,
        })
          .then(response => {
            this.$emit('update-success', response.data);
          })
          .catch(error => {
            console.error('Error updating badge status:', error);
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
          });
      },
    },
  };
  </script>
  