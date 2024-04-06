<script setup>
import TopicComponent from './pages/TopicComponent.vue'
import ForumComponent from './pages/ForumComponent.vue'

const loadTopic = window.location.pathname === '/forum/viewtopic.php'
const loadForum = window.location.pathname === '/forum/viewforum.php'
const loadTracker = window.location.pathname === '/forum/tracker.php'
</script>

<template>
  <TopicComponent v-if="loadTopic"></TopicComponent>
  <ForumComponent v-if="loadForum || loadTracker"></ForumComponent>
</template>

<style scoped></style>
