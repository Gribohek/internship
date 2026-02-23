<template>
  <div class="post">
    <div class="post-header">
      <div class="post-author">
        <router-link :to="`/posts/${post.user.username}`" class="author-link">
          @{{ post.user.username }}
        </router-link>
        <span class="author-name">{{ post.user.name }}</span>
      </div>
      <span class="time">{{ formatTime(post.created_at) }}</span>
    </div>

    <div class="post-content">
      <template v-for="(segment, index) in parsedContent" :key="index">
        <router-link
          v-if="segment.type === 'mention'"
          :to="`/posts/${segment.value}`"
          class="mention-link"
        >
          @{{ segment.value }}
        </router-link>

        <router-link
          v-else-if="segment.type === 'hashtag'"
          :to="`/hash/${segment.value}`"
          class="hashtag-link"
        >
          #{{ segment.value }}
        </router-link>

        <span v-else v-html="segment.value"></span>
      </template>
    </div>

    <div v-if="showFollowButton" class="post-footer">
      <button
        @click="toggleFollow"
        class="follow-btn"
        :class="{ following: post.user.is_following }"
        :disabled="followLoading"
      >
        <span v-if="followLoading" class="spinner"></span>
        <template v-else>
          {{ post.user.is_following ? "Отписаться" : "Подписаться" }}
        </template>
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import api from "../services/api";

export default {
  props: {
    post: {
      type: Object,
      required: true,
    },
    showFollowButton: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const followLoading = ref(false);

    const formatTime = (timestamp) => {
      const date = new Date(timestamp);
      const now = new Date();
      const diff = now - date;

      if (diff < 60000) return "только что";
      if (diff < 3600000) {
        const minutes = Math.floor(diff / 60000);
        return `${minutes} мин. назад`;
      }
      if (diff < 86400000) {
        const hours = Math.floor(diff / 3600000);
        return `${hours} ч. назад`;
      }
      return date.toLocaleString("ru-RU", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    };

    const parsedContent = computed(() => {
      const text = props.post.content;
      const parts = text.split(/(@[^\s]+|#[^\s]+)/g).filter(Boolean);

      return parts.map((part) => ({
        type: part.startsWith("@")
          ? "mention"
          : part.startsWith("#")
            ? "hashtag"
            : "text",
        value:
          part.startsWith("@") || part.startsWith("#")
            ? part.substring(1)
            : part.replace(/\n/g, "<br>"),
      }));
    });

    const toggleFollow = async () => {
      followLoading.value = true;
      try {
        if (props.post.user.is_following) {
          await api.unfollow(props.post.user.username);
          props.post.user.is_following = false;
          props.post.user.followers_count = Math.max(
            0,
            (props.post.user.followers_count || 0) - 1,
          );
        } else {
          await api.follow(props.post.user.username);
          props.post.user.is_following = true;
          props.post.user.followers_count =
            (props.post.user.followers_count || 0) + 1;
        }
      } catch (error) {
        console.error("Ошибка при изменении подписки:", error);
        alert("Не удалось изменить подписку. Попробуйте позже.");
      } finally {
        followLoading.value = false;
      }
    };

    return {
      formatTime,
      parsedContent,
      followLoading,
      toggleFollow,
    };
  },
};
</script>

<style scoped>
.post {
  border: 1px solid #e1e8ed;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 8px;
  background: white;
  transition: background 0.2s;
}

.post:hover {
  background: #fafbfc;
}

.post-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  font-size: 14px;
}

.post-author {
  display: flex;
  align-items: center;
  gap: 8px;
}

.author-link {
  font-weight: bold;
  color: #1da1f2;
  text-decoration: none;
}

.author-link:hover {
  text-decoration: underline;
}

.author-name {
  color: #657786;
  font-size: 13px;
}

.time {
  color: #657786;
  font-size: 13px;
}

.post-content {
  line-height: 1.5;
  white-space: pre-wrap;
  word-wrap: break-word;
  font-size: 15px;
  margin-bottom: 10px;
}

.mention-link {
  color: #1da1f2;
  font-weight: 500;
  text-decoration: none;
}

.mention-link:hover {
  text-decoration: underline;
}

.hashtag-link {
  color: #1da1f2;
  text-decoration: none;
}

.hashtag-link:hover {
  text-decoration: underline;
}

.post-footer {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid #e1e8ed;
}

.follow-btn {
  background: white;
  color: #1da1f2;
  border: 1px solid #1da1f2;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
  min-width: 100px;
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
  border: 2px solid rgba(29, 161, 242, 0.3);
  border-radius: 50%;
  border-top-color: #1da1f2;
  animation: spin 1s ease-in-out infinite;
}

.following .spinner {
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
