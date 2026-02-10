<template>
  <div>
    <h2>Моя лента</h2>

    <div v-if="!posts.length" class="empty">Нет постов в ленте</div>
    <div v-else class="posts-list">
      <PostItem v-for="post in posts" :key="post.id" :post="post" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import PostItem from "../components/PostItem.vue";

const allPosts = [
  {
    id: 1,
    content: "Привет всем! Это мой первый пост @testuser #первыйпост",
    author: { username: "current_user" },
    created_at: "2026-01-15T10:30:00Z",
    mentions: ["testuser"],
    hashtags: ["первыйпост"],
  },
  {
    id: 2,
    content: "Здравствуй #код #vue",
    author: { username: "developer" },
    created_at: "2026-01-15T10:30:00Z",
    mentions: [],
    hashtags: ["код", "vue"],
  },
  {
    id: 3,
    content: "Встречаемся в 18:00 @current_user #встреча",
    author: { username: "organizer" },
    created_at: "2026-01-15T08:45:00Z",
    mentions: ["current_user"],
    hashtags: ["встреча"],
  },
];

export default {
  components: { PostItem },
  props: ["newPost"],
  setup(props) {
    const posts = ref([]);
    const loading = ref(true);

    const loadPosts = () => {
      posts.value = [...allPosts];
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
h2 {
  margin-bottom: 20px;
  color: #333;
}

.loading,
.empty {
  padding: 20px;
  text-align: center;
  color: #657786;
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 5px;
}

.posts-list {
  margin-top: 10px;
}
</style>
