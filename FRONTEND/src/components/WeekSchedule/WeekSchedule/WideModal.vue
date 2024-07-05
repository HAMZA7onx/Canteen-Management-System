

  <template>
    <slot></slot>
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
  
  