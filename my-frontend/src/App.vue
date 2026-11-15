<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import PostForm from './components/PostForm.vue'
import PostList from './components/PostList.vue'

const posts = ref([])
const loading = ref(true)
const error = ref(null)

const fetchPosts = async () => {
  try {
    const response = await axios.get('http://localhost:80/api/posts')
    posts.value = response.data.data
    error.value = null
  } catch (e) {
    error.value = '投稿の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

onMounted(fetchPosts)
</script>

<template>
  <main>
    <h1>投稿一覧</h1>
    <PostForm @created="fetchPosts" />
    <p v-if="loading">読み込み中...</p>
    <p v-else-if="error">{{ error }}</p>
    <PostList v-else :posts="posts" />
  </main>
</template>