<template>
  <div>
    <p v-if="loading">読み込み中...</p>
    <p v-else-if="error">エラーが発生しました</p>
    <ul v-else>
      <li v-for="post in posts" :key="post.id">
        {{ post.title }}({{ post.user.name }})
        <br />
        {{ post.content }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const posts = ref([])
const loading = ref(true)
const error = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:80/api/posts')
    posts.value = response.data.data  // APIリソースは data キーに入る
  } catch (e) {
    error.value = true
    console.error('取得失敗:', e)
  } finally {
    loading.value = false
  }
})
</script>