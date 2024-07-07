

  <template>
    <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-start justify-center p-4 z-50">
      <slot></slot>

    </div>
  </template>
  
  
  <script>
  export default {
    name: 'WideModal',
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
  
  