<template>
  <div class="hashtag-posts">
    <h2 class="page-title">#{{ hashtag }}</h2>

    <div v-if="loading" class="status-message">Загрузка...</div>
    <div v-else-if="!posts.length" class="status-message">
      Нет постов с этим хэштегом
    </div>
    <div v-else class="posts-list">
      <PostItem v-for="post in posts" :key="post.id" :post="post" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import PostItem from "../components/PostItem.vue";

const allPosts = [
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
  {
    id: 3,
    content: "Встречаемся в 18:00 @current_user #встреча",
    author: { username: "organizer" },
    created_at: "2024-01-15T08:45:00Z",
    mentions: ["current_user"],
    hashtags: ["встреча"],
  },
];

export default {
  components: { PostItem },
  props: ["hashtag", "newPost"],
  setup(props) {
    const route = useRoute();
    const posts = ref([]);
    const loading = ref(true);
    const currentHashtag = ref("");

    const loadPosts = () => {
      const hashtag = props.hashtag || route.params.hashtag;
      currentHashtag.value = hashtag;

      posts.value = allPosts.filter((post) =>
        post.hashtags.some(
          (tag) => tag.toLowerCase() === hashtag.toLowerCase(),
        ),
      );

      loading.value = false;
    };

    watch(
      () => props.newPost,
      (post) => {
        if (post && currentHashtag.value) {
          const hasTag = post.hashtags.some(
            (tag) => tag.toLowerCase() === currentHashtag.value.toLowerCase(),
          );

          if (hasTag && !posts.value.some((p) => p.id === post.id)) {
            posts.value.unshift(post);
          }
        }
      },
    );

    watch(
      () => props.hashtag || route.params.hashtag,
      () => {
        loading.value = true;
        loadPosts();
      },
    );

    onMounted(loadPosts);

    return {
      posts,
      loading,
      hashtag: currentHashtag,
    };
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
