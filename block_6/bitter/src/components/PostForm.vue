<template>
  <div class="post-form">
    <textarea
      v-model="content"
      placeholder="Напишите пост"
      maxlength="280"
    ></textarea>

    <div class="controls">
      <span :class="{ 'text-red': charsLeft <= 0 }"> {{ charsLeft }}/280 </span>
      <button @click="submitPost" :disabled="!canSubmit || loading" class="btn">
        {{ loading ? "Отправка..." : "Опубликовать" }}
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";

export default {
  emits: ["post-created"],
  setup(_, { emit }) {
    const content = ref("");
    const loading = ref(false);

    const charsLeft = computed(() => 280 - content.value.length);
    const canSubmit = computed(
      () => content.value.trim().length > 0 && content.value.length <= 280,
    );

    const submitPost = async () => {
      if (!canSubmit.value) return;

      loading.value = true;

      const mentions =
        content.value.match(/@(\w+)/g)?.map((m) => m.slice(1)) || [];
      const hashtags =
        content.value.match(/#(\w+)/g)?.map((h) => h.slice(1)) || [];

      const newPost = {
        id: Date.now(),
        content: content.value,
        author: { username: "current_user" },
        created_at: new Date().toISOString(),
        mentions,
        hashtags,
      };

      emit("post-created", newPost);
      content.value = "";
      loading.value = false;
    };

    return { content, loading, charsLeft, canSubmit, submitPost };
  },
};
</script>

<style scoped>
.post-form {
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 5px;
  background: white;
}

textarea {
  width: 100%;
  min-height: 80px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
  font-size: 14px;
  resize: vertical;
  margin-bottom: 10px;
}

textarea:focus {
  outline: none;
  border-color: #1da1f2;
}

.controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.text-red {
  color: #e0245e;
}

.btn {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
}

.btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.btn:not(:disabled):hover {
  background: #0d8bf0;
}
</style>
