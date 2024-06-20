<!-- Overlay.vue -->
<template>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
    <slot></slot>
</div>
</template>

<script>
    export default {
        name: 'Overlay',
    };
</script>