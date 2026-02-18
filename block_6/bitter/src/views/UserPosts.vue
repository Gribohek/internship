<template>
  <div class="user-posts">
    <div v-if="user" class="profile-header">
      <div class="profile-info">
        <h2>@{{ user.username }}</h2>
        <p class="user-name">{{ user.name }}</p>
        <div class="user-stats">
          <span>Ваших потов {{ user.posts_count || 0 }} </span>
          <span>На вас подписано {{ followersCount }}</span>
          <span>Ваших подписок {{ user.following_count || 0 }} </span>
        </div>
      </div>

      <button
        v-if="user.username !== 'current_user'"
        @click="toggleFollow"
        class="follow-btn"
        :class="{ following: isFollowing }"
        :disabled="followLoading"
      >
        <span v-if="followLoading" class="spinner"></span>
        {{ followLoading ? "" : isFollowing ? " Отписаться" : "Подписаться" }}
      </button>
    </div>

    <h3>Посты</h3>
    <div v-if="loading" class="status">Загрузка...</div>
    <div v-else-if="error" class="status error">{{ error }}</div>
    <div v-else-if="!posts.length" class="status">Нет постов</div>
    <PostItem v-else v-for="post in posts" :key="post.id" :post="post" />
  </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import PostItem from "../components/PostItem.vue";
import api from "../services/api";

export default {
  components: { PostItem },
  props: ["username", "newPost"],
  setup(props) {
    const route = useRoute();
    const posts = ref([]);
    const user = ref(null);
    const loading = ref(true);
    const error = ref(null);
    const isFollowing = ref(false);
    const followLoading = ref(false);
    const followersCount = ref(0);

    const loadData = async () => {
      const username = props.username || route.params.username;
      loading.value = true;
      error.value = null;

      try {
        const userResponse = await api.getUser(username);
        if (userResponse.user) {
          user.value = userResponse.user;
          isFollowing.value = userResponse.is_following || false;
        } else {
          user.value = userResponse;
          try {
            const followingList = await api.getFollowing("current_user");
            isFollowing.value = followingList.some(
              (u) => u.username === username,
            );
          } catch (e) {}
        }

        followersCount.value = user.value.followers_count || 0;

        const postsResponse = await api.getUserPosts(username);

        if (postsResponse && postsResponse.posts) {
          posts.value = postsResponse.posts;
        } else if (postsResponse && postsResponse.data) {
          posts.value = postsResponse.data;
        } else if (Array.isArray(postsResponse)) {
          posts.value = postsResponse;
        } else {
          posts.value = [];
        }
      } catch (err) {
        error.value = "Не удалось загрузить данные";
      } finally {
        loading.value = false;
      }
    };

    const toggleFollow = async () => {
      if (!user.value) return;

      followLoading.value = true;
      try {
        if (isFollowing.value) {
          await api.unfollow(user.value.username);
          isFollowing.value = false;
          followersCount.value = Math.max(0, followersCount.value - 1);
        } else {
          await api.follow(user.value.username);
          isFollowing.value = true;
          followersCount.value += 1;
        }
      } catch (err) {
        alert("Не удалось изменить подписку. Попробуйте позже.");
      } finally {
        followLoading.value = false;
      }
    };

    watch(
      () => props.newPost,
      (post) => {
        if (post && user.value) {
          const isUserPost = post.user_id === user.value.id;
          const isMentioned = post.mentions?.some(
            (m) => m.id === user.value.id,
          );

          if (isUserPost || isMentioned) {
            posts.value.unshift(post);
          }
        }
      },
    );

    watch(() => props.username || route.params.username, loadData);
    onMounted(loadData);

    return {
      posts,
      user,
      loading,
      error,
      isFollowing,
      followLoading,
      followersCount,
      toggleFollow,
    };
  },
};
</script>
<style scoped>
.user-posts {
  max-width: 600px;
  margin: 0 auto;
}

.profile-header {
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 30px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.profile-info {
  flex: 1;
}

.profile-info h2 {
  color: #1da1f2;
  margin-bottom: 5px;
}

.user-name {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 5px;
}

.user-bio {
  color: #14171a;
  margin-bottom: 15px;
  line-height: 1.4;
}

.user-stats {
  display: flex;
  gap: 20px;
  color: #657786;
  font-size: 14px;
}

.follow-btn {
  background: white;
  color: #1da1f2;
  border: 1px solid #1da1f2;
  padding: 8px 20px;
  border-radius: 20px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
  min-width: 120px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-left: 20px;
}

.follow-btn:hover:not(:disabled) {
  background: #e8f5fe;
}

.follow-btn.following {
  background: #1da1f2;
  color: white;
}

.follow-btn.following:hover:not(:disabled) {
  background: #0d8bf0;
}

.follow-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

h3 {
  margin-bottom: 20px;
  color: #14171a;
}

.status {
  padding: 40px;
  text-align: center;
  color: #657786;
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 8px;
}

.status.error {
  color: #e0245e;
  background: #ffebee;
  border-color: #ffcdd2;
}
</style>
