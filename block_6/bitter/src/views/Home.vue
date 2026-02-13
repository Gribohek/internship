<template>
  <div class="home">
    <h2 class="page-title">Моя лента</h2>

    <div v-if="loading" class="status-message">Загрузка...</div>
    <div v-else-if="!posts.length" class="status-message">
      Нет постов в ленте
    </div>
    <div v-else class="posts-list">
      <PostItem v-for="post in posts" :key="post.id" :post="post" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import PostItem from "../components/PostItem.vue";

const mockPosts = [
  {
    id: 1,
    content: "Привет всем! Это мой первый пост @testuser #первыйпост",
    author: { username: "current_user" },
    created_at: "2024-01-15T10:30:00Z",
    mentions: ["testuser"],
    hashtags: ["первыйпост"],
  },
  {
    id: 2,
    content: "Сегодня отличный день для программирования! #код #vue",
    author: { username: "developer" },
    created_at: "2024-01-15T09:15:00Z",
    mentions: [],
    hashtags: ["код", "vue"],
  },
];

export default {
  components: { PostItem },
  props: ["newPost"],
  setup(props) {
    const posts = ref([]);
    const loading = ref(true);

    const loadPosts = () => {
      posts.value = [...mockPosts];
      loading.value = false;
    };

    onMounted(loadPosts);

    watch(
      () => props.newPost,
      (post) => {
        if (post) {
          posts.value.unshift(post);
        }
      },
    );

    return { posts, loading };
  },
};
</script>

<style scoped>
.page-title {
  margin-bottom: 20px;
  color: #14171a;
  font-size: 24px;
}

.status-message {
  padding: 40px;
  text-align: center;
  color: #657786;
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 8px;
}

.posts-list {
  margin-top: 10px;
}
</style>
