<template>
  <div class="post">
    <div class="post-header">
      <span class="author">@{{ post.author.username }}</span>
      <span class="time">{{ formatTime(post.created_at) }}</span>
    </div>

    <div class="post-content">{{ post.content }}</div>

    <div v-if="post.mentions.length" class="post-tags">
      <span>Упомянуты: </span>
      <span
        v-for="mention in post.mentions"
        :key="mention"
        class="tag mention"
        @click="$router.push(`/${mention}`)"
      >
        @{{ mention }}
      </span>
    </div>

    <div v-if="post.hashtags.length" class="post-tags">
      <span>Хэштеги: </span>
      <span
        v-for="tag in post.hashtags"
        :key="tag"
        class="tag hashtag"
        @click="$router.push(`/hash/${tag}`)"
      >
        #{{ tag }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post"],
  methods: {
    formatTime(timestamp) {
      return new Date(timestamp).toLocaleString("ru-RU", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    },
  },
};
</script>

<style scoped>
.post {
  border: 1px solid #ddd;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  background: white;
}

.post-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  font-size: 14px;
}

.author {
  font-weight: bold;
  color: #333;
}

.time {
  color: #777;
}

.post-content {
  margin-bottom: 10px;
  line-height: 1.4;
  white-space: pre-wrap;
}

.post-tags {
  margin-top: 8px;
  font-size: 13px;
  color: #666;
}

.tag {
  margin-left: 5px;
  cursor: pointer;
  padding: 2px 4px;
  border-radius: 3px;
}

.mention {
  color: #1da1f2;
}

.hashtag {
  color: #17bf63;
}

.tag:hover {
  text-decoration: underline;
  background: #f5f8fa;
}
</style>
