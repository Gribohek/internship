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
        :class="{ following: isFollowing }"
        :disabled="followLoading"
      >
        {{ followLoading ? "..." : isFollowing ? "Отписаться" : "Подписаться" }}
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
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
    const isFollowing = ref(false);
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
      const segments = [];
      let remaining = props.post.content;
      const regex = /(@[^\s]+|#[^\s]+)/g;
      let lastIndex = 0;
      let match;

      while ((match = regex.exec(remaining)) !== null) {
        if (match.index > lastIndex) {
          segments.push({
            type: "text",
            value: remaining
              .substring(lastIndex, match.index)
              .replace(/\n/g, "<br>"),
          });
        }

        const fullMatch = match[0];
        if (fullMatch.startsWith("@")) {
          segments.push({
            type: "mention",
            value: fullMatch.substring(1),
          });
        } else if (fullMatch.startsWith("#")) {
          segments.push({
            type: "hashtag",
            value: fullMatch.substring(1),
          });
        }

        lastIndex = match.index + fullMatch.length;
      }

      if (lastIndex < remaining.length) {
        segments.push({
          type: "text",
          value: remaining.substring(lastIndex).replace(/\n/g, "<br>"),
        });
      }

      return segments;
    });

    const checkFollowStatus = async () => {
      if (!props.showFollowButton) return;

      try {
        const following = await api.getFollowing("current_user");
        isFollowing.value = following.some(
          (f) => f.username === props.post.user.username,
        );
      } catch (error) {
        console.error("Ошибка проверки подписки:", error);
      }
    };

    const toggleFollow = async () => {
      followLoading.value = true;
      try {
        if (isFollowing.value) {
          await api.unfollow(props.post.user.username);
          isFollowing.value = false;
        } else {
          await api.follow(props.post.user.username);
          isFollowing.value = true;
        }
      } catch (error) {
        console.error("Ошибка при изменении подписки:", error);
        alert("Не удалось изменить подписку. Попробуйте позже.");
      } finally {
        followLoading.value = false;
      }
    };

    onMounted(checkFollowStatus);

    return {
      formatTime,
      parsedContent,
      isFollowing,
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
}

.follow-btn:hover {
  background: #e8f5fe;
}

.follow-btn.following {
  background: #1da1f2;
  color: white;
}

.follow-btn.following:hover {
  background: #0d8bf0;
}
</style>
