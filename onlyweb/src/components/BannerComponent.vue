<script setup>
const emit = defineEmits(['click'])
emit('click')
</script>

<template>
  <div></div>
</template>

<style scoped lang="scss">
a.banner {
  //background-image: `url(${BANNER_IMAGE})`;
  background-size: contain;
  background-repeat: no-repeat;
  width: 32px;
  height: 128px;
  top: -128px;
  left: 8px;
  outline: none;
  cursor: pointer;
  position: fixed;
  z-index: 9999999999;
  transition: top 0.2s ease;
}
</style>
