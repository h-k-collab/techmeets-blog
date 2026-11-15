<script setup>
defineProps({
  post: { type: Object, required: true },
})
</script>

<template>
  <li class="card">
    <h3>{{ post.title }}</h3>
    <p class="meta">{{ post.user.name }} ・ {{ post.created_at }}</p>
    <p>{{ post.content }}</p>
  </li>
</template>

<style scoped>
.card {
  list-style: none;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 12px;
}
.meta {
  color: #666;
  font-size: 0.85rem;
}
</style>