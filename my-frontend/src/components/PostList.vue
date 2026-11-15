<script setup>
import PostCard from './PostCard.vue'

defineProps({
  posts: { type: Array, required: true },
})
</script>

<template>
  <ul class="list">
    <PostCard v-for="post in posts" :key="post.id" :post="post" />
  </ul>
</template>

<style scoped>
.list {
  padding: 0;
}
</style>