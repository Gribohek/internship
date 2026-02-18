<template>
  <div class="hashtag-posts">
    <div class="hashtag-header">
      <h2 class="page-title">#{{ displayHashtag }}</h2>
      <span class="posts-count">{{ totalPosts }} постов</span>
    </div>

    <div v-if="loading" class="status-message">Загрузка...</div>
    <div v-else-if="error" class="status-message error">{{ error }}</div>
    <div v-else-if="!posts.length" class="status-message">
      Нет постов с хэштегом #{{ displayHashtag }}
    </div>
    <div v-else>
      <PostItem v-for="post in posts" :key="post.id" :post="post" />

      <div v-if="hasMorePages" class="load-more">
        <button @click="loadMore" class="load-more-btn">Загрузить еще</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import PostItem from "../components/PostItem.vue";
import api from "../services/api";

export default {
  components: { PostItem },
  props: ["hashtag", "newPost"],
  setup(props) {
    const route = useRoute();
    const posts = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const displayHashtag = ref("");
    const totalPosts = ref(0);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const hasMorePages = ref(false);

    const loadPosts = async (page = 1) => {
      const hashtag = props.hashtag || route.params.hashtag;
      displayHashtag.value = hashtag;

      loading.value = true;
      error.value = null;

      try {
        const response = await api.getHashtagPosts(hashtag, page);

        if (response && response.posts && response.posts.data) {
          const newPosts = response.posts.data;
          posts.value = page === 1 ? newPosts : [...posts.value, ...newPosts];
          totalPosts.value = response.posts.total || newPosts.length;
          currentPage.value = response.posts.current_page || page;
          lastPage.value = response.posts.last_page || 1;
        }

        hasMorePages.value = currentPage.value < lastPage.value;
      } catch (err) {
        console.error("Ошибка загрузки постов:", err);
        error.value = "Не удалось загрузить посты";
      } finally {
        loading.value = false;
      }
    };

    const loadMore = () => {
      if (hasMorePages.value) {
        loadPosts(currentPage.value + 1);
      }
    };

    watch(
      () => props.newPost,
      (post) => {
        if (post && displayHashtag.value) {
          const hasTag = post.hashtags?.some(
            (tag) =>
              tag.name?.toLowerCase() === displayHashtag.value.toLowerCase(),
          );

          if (hasTag) {
            posts.value.unshift(post);
            totalPosts.value++;
          }
        }
      },
    );

    watch(
      () => props.hashtag || route.params.hashtag,
      () => loadPosts(1),
    );

    onMounted(() => loadPosts(1));

    return {
      posts,
      loading,
      error,
      displayHashtag,
      totalPosts,
      hasMorePages,
      loadMore,
    };
  },
};
</script>

<style scoped>
.hashtag-header {
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-title {
  color: #14171a;
  font-size: 24px;
  margin: 0;
}

.posts-count {
  color: #657786;
  font-size: 14px;
}

.status-message {
  padding: 40px;
  text-align: center;
  color: #657786;
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 8px;
}

.status-message.error {
  color: #e0245e;
  background: #ffebee;
  border-color: #ffcdd2;
}

.load-more {
  margin-top: 20px;
  text-align: center;
}

.load-more-btn {
  background: white;
  border: 1px solid #1da1f2;
  color: #1da1f2;
  padding: 10px 20px;
  border-radius: 20px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
}

.load-more-btn:hover {
  background: #e8f5fe;
}
</style>
