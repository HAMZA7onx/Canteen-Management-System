<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all sm:max-w-lg sm:w-full"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-headline"
      >
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-700 dark:to-purple-800 px-4 py-3 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-white" id="modal-headline">{{ title }}</h3>
          <button
            type="button"
            class="text-white hover:text-gray-200 focus:outline-none transition-colors duration-200"
            @click="$emit('close')"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="bg-white dark:bg-gray-800 px-6 py-4">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Modal Title',
    },
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>

