<script setup>
import TopicComponent from './pages/TopicComponent.vue'
import ForumComponent from './pages/ForumComponent.vue'
import TrackerComponent from './pages/TrackerComponent.vue'

const loadTopic = window.location.pathname === '/forum/viewtopic.php'
const loadForum = window.location.pathname === '/forum/viewforum.php'
const loadTracker = window.location.pathname === '/forum/tracker.php'
</script>

<template>
  <TopicComponent v-if="loadTopic"></TopicComponent>
  <ForumComponent v-if="loadForum"></ForumComponent>
  <TrackerComponent v-if="loadTracker"></TrackerComponent>
</template>

<style scoped></style>
