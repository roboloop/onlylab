<script setup lang="ts">
import { BModalOrchestrator, BToastOrchestrator } from 'bootstrap-vue-next'
import HelpComponent from '@/components/modals/HelpComponent.vue'
import SettingsComponent from '@/components/modals/SettingsComponent.vue'
import ForumPage from '@/pages/ForumPage.vue'
import TopicPage from '@/pages/TopicPage.vue'
import { baseId } from '@/services/dom/injector'

const loadTopic = window.location.pathname === '/forum/viewtopic.php'
const loadForum = window.location.pathname === '/forum/viewforum.php'
const loadTracker = window.location.pathname === '/forum/tracker.php'
</script>

<template>
  <Suspense><TopicPage v-if="loadTopic"></TopicPage></Suspense>
  <ForumPage v-if="loadForum || loadTracker"></ForumPage>
  <Suspense><SettingsComponent></SettingsComponent></Suspense>
  <Suspense><HelpComponent></HelpComponent></Suspense>
  <BModalOrchestrator :teleport-to="baseId"></BModalOrchestrator>
  <BToastOrchestrator :teleport-to="baseId"></BToastOrchestrator>
</template>

<style scoped lang="scss"></style>
