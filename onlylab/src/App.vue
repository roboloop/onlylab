<script setup lang="ts">
import { BModalOrchestrator, BToastOrchestrator } from 'bootstrap-vue-next'
import HelpComponent from '@/components/modals/HelpComponent.vue'
import SettingsComponent from '@/components/modals/SettingsComponent.vue'
import ForumPage from '@/pages/ForumPage.vue'
import TopicPage from '@/pages/TopicPage.vue'

const loadTopic = window.location.pathname === '/forum/viewtopic.php'
const loadForum = window.location.pathname === '/forum/viewforum.php'
const loadTracker = window.location.pathname === '/forum/tracker.php'
const ifLocalhost = window.location.host === 'localhost' // TODO: del
</script>

<template>
  <TopicPage v-if="loadTopic"></TopicPage>
  <ForumPage v-if="loadForum || loadTracker || ifLocalhost"></ForumPage>
  <SettingsComponent></SettingsComponent>
  <HelpComponent></HelpComponent>
  <BModalOrchestrator teleport-to="#app"></BModalOrchestrator>
  <BToastOrchestrator teleport-to="#app"></BToastOrchestrator>
</template>

<style scoped lang="scss"></style>
