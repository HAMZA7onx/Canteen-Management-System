<!-- Overlay.vue -->
<template>
<div class="fixed z-50 inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-start justify-center p-4 ">
    <slot></slot>
</div>
</template>

<script>
    export default {
        name: 'Overlay',
    };
</script>