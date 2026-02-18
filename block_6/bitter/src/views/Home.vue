<template>
  <div class="home">
    <h2 class="page-title">Моя лента</h2>

    <div v-if="loading" class="status-message">Загрузка...</div>
    <div v-else-if="!posts.length" class="status-message">
      Нет постов в ленте. Подпишитесь на пользователей!
    </div>
    <div v-else>
      <PostItem
        v-for="post in posts"
        :key="post.id"
        :post="post"
        :show-follow-button="post.user.username !== 'current_user'"
      />

      <div v-if="hasMorePages" class="load-more">
        <button @click="loadMore" class="load-more-btn">Загрузить еще</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import PostItem from "../components/PostItem.vue";
import api from "../services/api";

export default {
  components: { PostItem },
  props: ["newPost"],
  setup(props) {
    const posts = ref([]);
    const loading = ref(true);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const hasMorePages = ref(false);

    const loadFeed = async (page = 1) => {
      loading.value = true;
      try {
        const response = await api.getFeed(page);
        const newPosts = response.data;

        if (page === 1) {
          posts.value = newPosts;
        } else {
          posts.value = [...posts.value, ...newPosts];
        }

        currentPage.value = response.current_page;
        lastPage.value = response.last_page;
        hasMorePages.value = currentPage.value < lastPage.value;
      } catch (error) {
        console.error("Ошибка загрузки ленты:", error);
      } finally {
        loading.value = false;
      }
    };

    const loadMore = () => {
      if (hasMorePages.value) {
        loadFeed(currentPage.value + 1);
      }
    };

    watch(
      () => props.newPost,
      (post) => {
        if (post) {
          posts.value.unshift(post);
        }
      },
    );

    onMounted(() => {
      loadFeed();
    });

    return {
      posts,
      loading,
      hasMorePages,
      loadMore,
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
