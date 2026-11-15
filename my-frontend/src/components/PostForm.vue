<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['created'])

const title = ref('')
const content = ref('')
const submitting = ref(false)
const errors = ref([])

const submit = async () => {
  submitting.value = true
  errors.value = []
  try {
    await axios.post('http://localhost:80/api/posts', {
      title: title.value,
      content: content.value,
      category: '一般',
    })
    title.value = ''
    content.value = ''
    emit('created')
  } catch (e) {
    if (e.response && e.response.status === 422) {
      errors.value = Object.values(e.response.data.errors).flat()
    } else {
      errors.value = ['投稿に失敗しました']
    }
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <form class="form" @submit.prevent="submit">
    <h2>新規投稿</h2>
    <ul v-if="errors.length" class="errors">
      <li v-for="(message, index) in errors" :key="index">{{ message }}</li>
    </ul>
    <input v-model="title" placeholder="タイトル" />
    <textarea v-model="content" placeholder="本文" rows="4"></textarea>
    <button type="submit" :disabled="submitting">
      {{ submitting ? '送信中...' : '投稿する' }}
    </button>
  </form>
</template>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
}
.errors {
  color: #c00;
  font-size: 0.9rem;
}
</style>