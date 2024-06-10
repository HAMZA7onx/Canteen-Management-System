<template>
  <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label for="name" class="block text-gray-700 font-bold mb-2">Permission Name</label>
        <input
          type="text"
          id="name"
          v-model="permission.name"
          required
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        {{ isEditMode ? 'Update' : 'Create' }}
      </button>
    </form>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'PermissionForm',
  props: {
    permission: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      isEditMode: false,
    };
  },
  created() {
    this.isEditMode = !!this.permission.id;
  },
  methods: {
    ...mapActions('permission', ['createPermission', 'updatePermission']),
    submitForm() {
      if (this.isEditMode) {
        this.updatePermission(this.permission)
          .then(() => {
            this.$emit('update:permission', null);
          })
          .catch((error) => {
            console.error('Error updating permission:', error);
          });
      } else {
        this.createPermission(this.permission)
          .then(() => {
            this.$emit('update:permission', null);
          })
          .catch((error) => {
            console.error('Error creating permission:', error);
          });
      }
    },
  },
};
</script>
