<template>
  <div>
    <h2>Посты @{{ username }}</h2>
    <button @click="goToHome" class="small-home-btn">На главную</button>

    <div v-if="!posts.length" class="empty">
      Нет постов у этого пользователя
    </div>
    <div v-else class="posts-list">
      <PostItem v-for="post in posts" :key="post.id" :post="post" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
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
  props: ["username", "newPost"],
  setup(props) {
    const route = useRoute();
    const router = useRouter();
    const posts = ref([]);
    const loading = ref(true);
    const currentUsername = ref("");

    const loadPosts = () => {
      const username = props.username || route.params.username;
      currentUsername.value = username;

      posts.value = allPosts.filter(
        (post) =>
          post.author.username === username || post.mentions.includes(username),
      );

      loading.value = false;
    };

    const goToHome = () => {
      router.push("/");
    };

    watch(
      () => props.newPost,
      (post) => {
        if (post && currentUsername.value) {
          const isUserPost = post.author.username === currentUsername.value;
          const isMentioned = post.mentions.includes(currentUsername.value);

          if (
            (isUserPost || isMentioned) &&
            !posts.value.some((p) => p.id === post.id)
          ) {
            posts.value.unshift(post);
          }
        }
      },
    );

    onMounted(loadPosts);

    return {
      posts,
      loading,
      username: currentUsername,
      goToHome,
    };
  },
};
</script>

<style scoped>
h2 {
  margin-bottom: 10px;
  color: #333;
}

.small-home-btn {
  margin-bottom: 20px;
  background: #657786;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
}

.small-home-btn:hover {
  background: #4a5c6b;
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
