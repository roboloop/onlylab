<script setup lang="ts">
import ImagesComponent from '@/components/ImagesComponent.vue'
import ScreenlistsComponent from '@/components/ScreenlistsComponent.vue'
import * as global from '@/services/store/global'
</script>

<template>
  <ImagesComponent v-if="global.viewMode.value === 'images'" :imageLinks="global.imageLinks.value" />
  <ScreenlistsComponent v-if="global.viewMode.value === 'pack'" :imageLinks="global.pack.value" />
  <!--  <FilesComponent :files="files" :is-busy="isBusy" v-if="showFiles"></FilesComponent>-->
</template>

<style scoped lang="scss"></style>
