<template>
  <div class="post">
    <div class="post-header">
      <router-link :to="`/posts/${post.author.username}`" class="author">
        @{{ post.author.username }}
      </router-link>
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
  </div>
</template>

<script>
import { computed } from "vue";

export default {
  props: ["post"],
  setup(props) {
    const formatTime = (timestamp) => {
      return new Date(timestamp).toLocaleString("ru-RU", {
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

      // ПРОСТОЕ РЕШЕНИЕ: @ или #, затем любые символы кроме пробела
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

    return {
      formatTime,
      parsedContent,
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
  margin-bottom: 10px;
  font-size: 14px;
}

.author {
  font-weight: bold;
  color: #1da1f2;
  cursor: pointer;
}

.author:hover {
  text-decoration: underline;
}

.time {
  color: #657786;
}

.post-content {
  line-height: 1.5;
  white-space: pre-wrap;
  word-wrap: break-word;
  font-size: 15px;
}

/* Стили для ссылок внутри текста */
::v-deep .mention-link {
  color: #1da1f2;
  font-weight: 500;
  cursor: pointer;
}

::v-deep .mention-link:hover {
  text-decoration: underline;
}

::v-deep .hashtag-link {
  color: #1da1f2;
  cursor: pointer;
}

::v-deep .hashtag-link:hover {
  text-decoration: underline;
}
</style>
